<?php
namespace BlogPost\Controller;

use App\Mvc\Controller\RestfulController;
use Zend\View\Model\JsonModel;
use Zend\Http\Client;
use Zend\Http\Client\Adapter\Curl;
use Zend\Http\Request;
use Zend\Http\Headers;
use Zend\Mvc\MvcEvent;


/**
 * This controller handles 
 * 
 */
class UpdateBlogPostController extends RestfulController
{  
    protected $_allowedMethod = "post";
    
    protected $blogPostId;
    protected $user;

    public function onDispatch(MvcEvent $e)
    {
        $parts = explode("/", $this->getRequest()->getUri()->getPath());
        $last = array_pop($parts);
        $this->blogPostId = $last;
        $last = array_pop($parts);
        $this->user = $last;

        return parent::onDispatch($e);
    }
    public function activeBlogPostAction()
    {
        $em = $this->getEntityManager();
        $request = $this->getRequest();
        $requestData = $this->processBodyContent($request);
        $key_api = $requestData[0]['key_api'];
        $parts = explode("-", $key_api);
        $first = array_shift($parts);
        $user = $first;
        $cm = $em->getClassMetadata('BlogPost\Entity\BlogPost');
        $cm->setTableName('ca_blog_posts_user_'.$user);
        $q = $em->createQuery('update BlogPost\Entity\BlogPost m set m.status = 2 where m.key_api = ?1');
        $q->setParameter(1,$key_api);
        $numUpgrade = $q->execute();
        return $this->getResponse()->setStatusCode(200);
    }

    public function indexAction()
    {
        $em = $this->getEntityManager();
        $cm = $em->getClassMetadata('BlogPost\Entity\BlogPost');
        $cm->setTableName('ca_blog_posts_user_'.$this->user);
        $request = $this->getRequest();
        $requestData = $this->processBodyContent($request);

        $postRepository = $em->getRepository('BlogPost\Entity\BlogPost');
        $post = $postRepository->find($this->blogPostId);

        if (!empty($post)) {
            if(isset($requestData[0]['post_id']))
                $post->setPostId($requestData[0]['post_id']);
            if(isset($requestData[0]['key_api']))
                $post->setKeyApi($requestData[0]['key_api']);
            if(isset($requestData[0]['title']))
                $post->setTitle($requestData[0]['title']);
            if(isset($requestData[0]['date_publishing']))
                $post->setDatePublishing($requestData[0]['date_publishing']);
            if(isset($requestData[0]['url']))
                $post->setUrl($requestData[0]['url']);
            if(isset($requestData[0]['author']))
                $post->setAuthor($requestData[0]['author']);
            if(isset($requestData[0]['category']))
                $post->setCategory($requestData[0]['category']);
            if(isset($requestData[0]['avg_session_duration']))
                $post->setAvgSessionDuration($requestData[0]['avg_session_duration']);
            if(isset($requestData[0]['total_social_count']))
                $post->setTotalSocialCount($requestData[0]['total_social_count']);
            if(isset($requestData[0]['view']))
                $post->setView($requestData[0]['view']);
            if(isset($requestData[0]['social_count_facebook']))
                $post->setSocialCountFacebook($requestData[0]['social_count_facebook']);
            if(isset($requestData[0]['social_count_twitter']))
                $post->setSocialCountTwitter($requestData[0]['social_count_twitter']);
            if(isset($requestData[0]['social_count_linkedin']))
                $post->setSocialCountLinkedin($requestData[0]['social_count_linkedin']);
//            if(isset($requestData[0]['social_count_reddit']))
//                $post->setSocialCountReddit($requestData[0]['social_count_reddit']);
//            if(isset($requestData[0]['social_count_stumble_upon']))
//                $post->setSocialCountStumbleUpon($requestData[0]['social_count_stumble_upon']);
            if(isset($requestData[0]['social_count_google_plus']))
                $post->setSocialCountGooglePlus($requestData[0]['social_count_google_plus']);
//            if(isset($requestData[0]['social_count_pinterest']))
//                $post->setSocialCountPinterest($requestData[0]['social_count_pinterest']);
//            if(isset($requestData[0]['social_count_flattr']))
//                $post->setSocialCountFlattr($requestData[0]['social_count_flattr']);
//            if(isset($requestData[0]['social_count_XING']))
//                $post->setSocialCountXING($requestData[0]['social_count_XING']);
//            if(isset($requestData[0]['sync_date']))
//                $post->setSyncDate($requestData[0]['sync_date']);
            if(isset($requestData[0]['words']))
                $post->setWords($requestData[0]['words']);
            if(isset($requestData[0]['status']))
                $post->setStatus($requestData[0]['status']);
            if(isset($requestData[0]['created']))
                $post->setCreated($requestData[0]['created']);
            $em->persist($post);
            $em->flush();

            $this->getResponse()->setStatusCode(200);
            $return = array($post->getExpectedArray());

            }
        else {
            $this->getResponse()->setStatusCode(404);
            $return = array("errors" => \Post\Entity\BlogPost::ERR_BLOGPOST_NOT_FOUND);
        }
        
        return new JsonModel(array("result" => $return));
    }
}
