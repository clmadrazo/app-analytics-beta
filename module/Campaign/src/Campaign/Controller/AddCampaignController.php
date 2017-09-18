<?php
namespace Campaign\Controller;

use App\Mvc\Controller\RestfulController;
use Campaign\Entity\Campaign;
use Post\Entity\Post;
use Client\Entity\Client;
/**
 * This controller handles all campaign module requests.
 * 
 */
class AddCampaignController extends RestfulController
{  
    protected $_allowedMethod = "post";
    

    public function indexAction()
    {
        $em = $this->getEntityManager();
        $request = $this->getRequest();
        $requestData = $this->processBodyContent($request);

        $detailsObj = json_decode($requestData[0]['details']);

        $client = $em->find('Client\Entity\Client', $requestData[0]['customerId']);
        $loggedInUser = $this->getLoggedUser();
        $customer = $loggedInUser->getCustomer();
        $name = $requestData[0]['name'];
        $guidelines = $requestData[0]['guidelines'];
        
        if($detailsObj === null) {
            $this->getResponse()->setStatusCode(400);
            $this->setResponse(array("errors" => Campaign::ERR_DETAILS_INVALID_JSON));
        } else if (!empty($client)) {
            if ($this->_checkCampaignDetails($detailsObj)) {
                $campaign = new Campaign($this->getEntityManager());
                $campaign->setClient($client);
                $campaign->setCustomer($customer);
                $campaign->setName($name);
                $campaign->setGuidelines($guidelines);
                $campaign->setStatus(Campaign::STATUS_ACTIVE);
                $campaign->setCreatedBy($this->getLoggedUser());
                $campaign->setCampaignDetails($detailsObj, $campaign, $customer);
                $em->persist($campaign);
                $em->flush();
                $this->_createEmptyPosts($detailsObj, $campaign);
                $this->getResponse()->setStatusCode(200);
                $this->setResponse(array($campaign->getExpectedArray()));
            }
        } else {
            $this->getResponse()->setStatusCode(404);
            $this->setResponse(array("errors" => \Campaign\Entity\Customer::ERR_CUSTOMER_NOT_FOUND));
        }
        
        return $this->getJsonResponse();
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
    
    private function _createEmptyPosts($detailsObj, $campaign)
    {
        $em = $this->getEntityManager();
        
        foreach ($detailsObj as $detailObj) {
            $amount = $detailObj->postsAmount;
            for ($i=0; $i<$amount; $i++) {
                $post = new Post();
                $post->setCampaign($campaign);
                $postType = $this->getEntityManager()->getRepository('Post\Entity\PostType')
                    ->find($detailObj->postType);
                $post->setPostType($postType);
                $postStatus = $this->getEntityManager()->getRepository('Post\Entity\PostStatus')
                    ->find(Post::STATUS_CREATED);
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