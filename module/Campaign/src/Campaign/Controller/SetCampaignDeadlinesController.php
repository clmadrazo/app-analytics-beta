<?php
namespace Campaign\Controller;

use App\Mvc\Controller\RestfulController;
use Campaign\Entity\Campaign;
use Campaign\Entity\CampaignDeadline;


/**
 * This controller handles all campaign module requests.
 * 
 */
class SetCampaignDeadlinesController extends RestfulController
{  
    protected $_allowedMethod = "post";
    

    public function indexAction()
    {
        $em = $this->getEntityManager();
        $request = $this->getRequest();
        $requestData = $this->processBodyContent($request);

        $deadlinesObj = json_decode($requestData[0]['deadlines']);

        $campaign = $em->find('Campaign\Entity\Campaign', $requestData[0]['campaignId']);
        if($deadlinesObj === null) {
            $this->getResponse()->setStatusCode(400);
            $this->setResponse(array("errors" => Campaign::ERR_DEADLINES_INVALID_JSON));
        } else if (!empty($campaign)) {
            if ($this->_checkDeadlinesDetails($deadlinesObj)) {
                foreach ($deadlinesObj as $deadlineObj) {
                    $role = $this->getEntityManager()->getRepository('User\Entity\Role')
                            ->find($deadlineObj->role);

                    $topic=  $this->getEntityManager()->getRepository('Post\Entity\Topic')
                        ->findOneBy(array('title' => $deadlineObj->topic));

                    $postType=$this->getEntityManager()->getRepository('Post\Entity\PostType')
                        ->find($deadlineObj->postType);

                    $campaignDeadline = $this->getEntityManager()->getRepository('Campaign\Entity\CampaignDeadline')
                           ->findOneBy(array('campaign' => $campaign, 'role' => $role, 'topic'=>$topic, 'postType'=>$postType));
                    
                   if (empty($campaignDeadline)) {
                        $campaignDeadline = new CampaignDeadline();
                    }
                    
                    $campaignDeadline->setCampaign($campaign);
                    $campaignDeadline->setRole($role);
                    $campaignDeadline->setDeadline($deadlineObj->deadline);
                    $campaignDeadline->setTopic($topic);
                    $campaignDeadline->setPostType($postType);

                    $em->persist($campaignDeadline);
                    $em->flush();
                }                

                $this->getResponse()->setStatusCode(200);
                $this->setResponse(array($campaign->getExpectedArray()));
            }
        } else {
            $this->getResponse()->setStatusCode(404);
            $this->setResponse(array("errors" => \Campaign\Entity\Campaign::ERR_CAMPAIGN_NOT_FOUND));
        }
        
        return $this->getJsonResponse();
    }
    
    private function _checkDeadlinesDetails($deadlinesObj)
    {
        $requireds = array('role', 'topic', 'postType', 'deadline');
        $return = true;

        foreach ($deadlinesObj as $deadlineObj) {
            foreach($deadlineObj as $key=>$value) {
                if (!in_array($key, $requireds)) {
                    $return = false;
                    $this->getResponse()->setStatusCode(400);
                    $this->setResponse(array("errors" => \Campaign\Entity\CampaignDeadline::ERR_DEADLINES_NOT_VALID));
                } else {
                    if ($key === 'role') {
                        $role = $this->getEntityManager()->getRepository('User\Entity\Role')
                            ->find($value);
                        if (empty($role)) {
                            $return = false;
                            $this->getResponse()->setStatusCode(400);
                            $this->setResponse(array("errors" => \User\Entity\Role::ERR_ROLE_NOT_FOUND));
                        }
                    }
                }
            }
            foreach ($requireds as $required) {
                foreach ($deadlinesObj as $deadlineObj) {
                    $deadlineArr = (array) $deadlineObj;
                    if (!key_exists($required, $deadlineArr)) {
                        $return = false;
                        $this->getResponse()->setStatusCode(400);
                        $this->setResponse(array("errors" => \Campaign\Entity\CampaignDeadline::ERR_DEADLINES_NOT_VALID));
                    }
                }
            }
        }
        
        return $return;
    }
}