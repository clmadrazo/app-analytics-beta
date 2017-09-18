<?php
namespace Campaign\Controller;

use App\Mvc\Controller\RestfulController;
use Zend\View\Model\JsonModel;
use Campaign\Entity\Campaign;


/**
 * This controller handles all campaign module requests.
 *
 */
class UpdateCampaignController extends RestfulController
{
    protected $_allowedMethod = "post";

    /**
     * @example
     *  [Request]
     *      GET /campaign/list
     *      Content-Type: application/json
     *
     * @return \Zend\View\Model\JsonModel
     */
    public function indexAction()
    {
        $em = $this->getEntityManager();
        $request = $this->getRequest();
        $requestData = $this->processBodyContent($request);
        $campaign = $em->find('Campaign\Entity\Campaign', $requestData[0]['campaignId']);
        $detailsObj = json_decode($requestData[0]['details']);

        $customer = $this->getLoggedUser()->getCustomer();
        if($detailsObj === null) {
            $this->getResponse()->setStatusCode(400);
            $this->setResponse(array("errors" => Campaign::ERR_DETAILS_INVALID_JSON));
        } else if (!empty($campaign)) {
            if ($this->_checkCampaignDetails($detailsObj)) {
            //   if (isset($requestData[0]['customerId'])){
                    $client = $em->find('Client\Entity\Client', $requestData[0]['customerId']);
                    $campaign->setClient($client);

                    $campaign->setCustomer($customer);

                $campaign->setCampaignDetails($detailsObj, $campaign, $customer);
         //       }

          //      if (isset($requestData[0]['name'])) {
                    $name = $requestData[0]['name'];
                    $campaign->setName($name);
           //     }

                if (isset($requestData[0]['guidelines'])) {
                    $guidelines = $requestData[0]['guidelines'];
                    $campaign->setGuidelines($guidelines);
                }

                $em->persist($campaign);
                $em->flush();

                $this->_createNeededEmptyPosts($detailsObj, $campaign);

                $this->getResponse()->setStatusCode(200);
                $return = $campaign->getExpectedArray();
            }
        } else{
            $this->getResponse()->setStatusCode(404);
            $return = array("errors" => Campaign::ERR_CAMPAIGN_NOT_FOUND);
        }

        return new JsonModel(array("result" => $return));
    }

    private function _checkCampaignDetails($detailsObj)
    {
        $requireds = array('topic', 'postType', 'postsAmount');
        $return = true;
        foreach ($detailsObj as $detailObj) {
            foreach($detailObj as $key=>$value) {
                if (!in_array($key, $requireds)) {
                    $return = false;
                    $this->getResponse()->setStatusCode(400);
                    $this->setResponse(array("errors" => \Campaign\Entity\CampaignDetail::ERR_DETAILS_NOT_VALID));
                } else {
                    if ($key === 'postType') {
                        $postType = $this->getEntityManager()->getRepository('Post\Entity\PostType')
                            ->find($value);
                        if (empty($postType)) {
                            $return = false;
                            $this->getResponse()->setStatusCode(400);
                            $this->setResponse(array("errors" => \Post\Entity\PostType::ERR_POST_TYPE_NOT_FOUND));
                        }
                    }
                }
            }
            foreach ($requireds as $required) {
                foreach ($detailsObj as $detailObj) {
                    $detailArr = (array) $detailObj;
                    if (!key_exists($required, $detailArr)) {
                        $return = false;
                        $this->getResponse()->setStatusCode(400);
                        $this->setResponse(array("errors" => \Campaign\Entity\CampaignDetail::ERR_DETAILS_NOT_VALID));
                    }
                }
            }
        }
        
        return $return;
    }

    private function _createNeededEmptyPosts($detailsObj, $campaign)
    {
        $em = $this->getEntityManager();
        
        foreach ($detailsObj as $detailObj) {
            $desiredAmount = $detailObj->postsAmount;
            
            $originalPosts = $this->getEntityManager()->getRepository('Post\Entity\Post')
                ->findBy(array('campaign_id' => $campaign->getId(), 'post_type_id' => $detailObj->postType));
            
            $amount = intval($desiredAmount) - count($originalPosts);
            
            for ($i=0; $i<$amount; $i++) {
                $post = new \Post\Entity\Post;
                $post->setCampaign($campaign);
                $postType = $this->getEntityManager()->getRepository('Post\Entity\PostType')
                    ->find($detailObj->postType);
                $post->setPostType($postType);
                $postStatus = $this->getEntityManager()->getRepository('Post\Entity\PostStatus')
                    ->find(\Post\Entity\Post::STATUS_CREATED);
                $post->setPostStatus($postStatus);
                $post->setDraftFlag(0);
                $post->setRejectedFlag(0);

                $topicEntity = $this->getEntityManager()->getRepository('Post\Entity\Topic')
                    ->findOneBy(array('title' => $detailObj->topic));


                $post->setTopic($topicEntity);
                $post->setCreatedBy($this->getLoggedUser());
                $em->persist($post);
                $em->flush();
            }
        }
    }
}
