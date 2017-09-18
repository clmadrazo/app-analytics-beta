<?php
namespace Campaign\Controller;

use App\Mvc\Controller\RestfulController;
use Zend\View\Model\JsonModel;


/**
 * This controller handles
 *
 */
class GetCampaignInformationController extends RestfulController
{
    protected $_allowedMethod = "post";


    public function indexAction()
    {
        $em = $this->getEntityManager();
        $request = $this->getRequest();
        $requestData = $this->processBodyContent($request);
        $campaignId = $requestData[0]['campaignId'];
        $campaign = $em->find('Campaign\Entity\Campaign', $campaignId);
        $campaignTopics = $this->getCampaignTopics($campaignId);

        $return = array();
        $return = $campaign->getExpectedArray();
        $i = 0;
        if (count($campaignTopics)==0){
          //  $return['details'][$i]=(object) [];
            $return['details'][$i]['writers'] = (object) [];
            $return['details'][$i]['editors'] = (object) [];
            $return['details'][$i]['auditors'] = (object) [];
        }

        foreach($campaignTopics as $campaignTopic){
            $return['details'][$i]['topicTitle']=$campaignTopic['title'];
            $writersAssignments = $this->getAssignedUsers($campaignTopic, 2);
            $editorsAssignments = $this->getAssignedUsers($campaignTopic, 3);
            $auditorsAssignments = $this->getAssignedUsers($campaignTopic, 4);
            $return['details'][$i]['writers'] = $writersAssignments;
            $return['details'][$i]['editors'] = $editorsAssignments;
            $return['details'][$i]['auditors'] = $auditorsAssignments;
            $fbPostType = $em->find('Post\Entity\PostType', 1);
            $twPostType = $em->find('Post\Entity\PostType', 2);
            $campaignDetail = $em->getRepository('Campaign\Entity\CampaignDetail')->findOneBy(array('campaign' => $campaign->getId(), 'topic'=>$campaignTopic['id'], 'postType'=>$fbPostType));
            $fbPostAmount = ($campaignDetail) ? $campaignDetail->getPostsAmount() : 0;
            $return['details'][$i]['fbPostAmount'] = $fbPostAmount;
            $campaignDetail= $em->getRepository('Campaign\Entity\CampaignDetail')->findOneBy(array('campaign' => $campaign, 'topic'=>$campaignTopic['id'], 'postType'=>$twPostType));
            $twPostAmount = ($campaignDetail) ? $campaignDetail->getPostsAmount() : 0;
            $return['details'][$i]['twPostAmount'] = $twPostAmount;
            $campaignDeadlines = $em->getRepository('Campaign\Entity\CampaignDeadline')->findBy(array('campaign'=>$campaign, 'topic'=>$campaignTopic['id'], 'postType'=>$fbPostType));
            foreach($campaignDeadlines as $campaignDeadline){
                $return['details'][$i]['fbDeadlines'][]=$campaignDeadline->getExpectedArray();
            }
            $campaignDeadlines = $em->getRepository('Campaign\Entity\CampaignDeadline')->findBy(array('campaign'=>$campaign, 'topic'=>$campaignTopic['id'], 'postType'=>$twPostType));
            foreach($campaignDeadlines as $campaignDeadline){
                $return['details'][$i]['twDeadlines'][] = $campaignDeadline->getExpectedArray();
            }

            $i++;
        }

        $this->getResponse()->setStatusCode(200);
        return new JsonModel(array("result" => $return));
    }

    private function getCampaignTopics($campaignId)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery("SELECT cd FROM Campaign\Entity\CampaignDetail cd
                                        JOIN cd.topic t
                                        JOIN cd.postType pt
                                        JOIN cd.campaign c
                                        WHERE
                                        c.id=$campaignId GROUP BY t");

        $queryResult =  $query->getResult();
        foreach ($queryResult as $rec) {
            $resultArray[] = $rec->getTopic()->getExpectedArray();
        }
        return $resultArray;
    }

    private function getAssignedUsers($topic, $roleId)
    {
        $em = $this->getEntityManager();
        $topicUserAssignments=$em->getRepository('Post\Entity\TopicUserAssignment')->findBy(array('topic'=>$topic['id']));
        foreach ($topicUserAssignments as $topicUserAssignment){
            $userRolesObjectArray=$topicUserAssignment->getUser()->getRoles();
            $userRolesIdArray=array();
            foreach ($userRolesObjectArray as $userRoleObject){
                $userRolesIdArray[]=$userRoleObject->getId();
            }
            if (in_array($roleId, $userRolesIdArray)) {
                $resultArray[] = $topicUserAssignment->getUser()->getExpectedArray();
            }
        }
        return $resultArray;
    }
}
