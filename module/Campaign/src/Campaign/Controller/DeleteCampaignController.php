<?php
namespace Campaign\Controller;

use App\Mvc\Controller\RestfulController;
use Zend\View\Model\JsonModel;


/**
 * This controller handles all campaign module requests.
 *
 */
class DeleteCampaignController extends RestfulController
{
    protected $_allowedMethod = "post";

    /**
     * @example
     *  [Request]
     *      GET /campaign/delete
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
        if (!empty($campaign)) {

        $campaignDetails=$em->getRepository('Campaign\Entity\CampaignDetail')->findBy(array('campaign'=>$campaign));
            foreach($campaignDetails as $campaignDetail){
               $em->remove($campaignDetail);
            }
        $campaignDeadlines=$em->getRepository('Campaign\Entity\CampaignDeadline')->findBy(array('campaign'=>$campaign));
            foreach($campaignDeadlines as $campaignDeadline){
                $em->remove($campaignDeadline);
            }
        $posts=$em->getRepository('Post\Entity\Post')->findBy(array('campaign'=>$campaign));
        foreach ($posts as $post) {
            $em->remove($post);
            $postscomments=$em->getRepository('Post\Entity\PostComment')->findBy(array('post'=>$post));
            foreach($postscomments as $postcomment){
                $em->remove($postcomment);
            }
            $postsuserassignments=$em->getRepository('Post\Entity\PostUserAssignment')->findBy(array('post'=>$post));
            foreach($postsuserassignments as $postuserassignment){
                $em->remove($postuserassignment);
            }
            $postsworkflows=$em->getRepository('Post\Entity\PostWorkflow')->findBy(array('post'=>$post));
            foreach($postsworkflows as $postworkflow){
                $em->remove($postworkflow);
            }
            $postimages = $em->getRepository('Post\Entity\PostImage')->findBy(array('post'=>$post));
            foreach($postimages as $postimage){
                $fileName = PUBLIC_PATH . DIRECTORY_SEPARATOR . $postimage->getRelativePath();
                if (realpath($fileName)) {
                    if (unlink(realpath($fileName))) {
                        $return = true;
                    }
                }

                $em->remove($postimage);
            }
            $postvideos=$em->getRepository('Post\Entity\PostVideo')->findBy(array('post'=>$post));
            foreach($postvideos as $postvideo){
                $fileName = PUBLIC_PATH . DIRECTORY_SEPARATOR . $postvideo->getRelativePath();
                if (realpath($fileName)) {
                    if (unlink(realpath($fileName))) {
                        $return = true;
                    }
                }
                $em->remove($postvideo);
            }
        }

            $em->remove($campaign);
            $em->flush();
            return $this->getResponse()->setStatusCode(200);

            }
        else{
            $this->getResponse()->setStatusCode(404);
            $return = array("errors" => Campaign::ERR_CAMPAIGN_NOT_FOUND);
        }


        return new JsonModel(array("result" => $return));
    }

}