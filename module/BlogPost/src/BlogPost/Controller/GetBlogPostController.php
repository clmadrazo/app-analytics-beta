<?php
namespace BlogPost\Controller;

use App\Mvc\Controller\RestfulController;
use Zend\View\Model\JsonModel;
use Zend\Mvc\MvcEvent;

/**
 * This controller handles 
 * 
 */
class GetBlogPostController extends RestfulController
{  
    protected $_allowedMethod = "get";
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
    

    public function indexAction()
    {
        $em = $this->getEntityManager();
        $cm = $em->getClassMetadata('BlogPost\Entity\BlogPost');
        $cm->setTableName('ca_blog_posts_user_'.$this->user);
        $blogPostRepository = $em->getRepository('BlogPost\Entity\BlogPost');
        $blogPost = $blogPostRepository->find($this->blogPostId);

        if (!empty($blogPost)) {
            $this->getResponse()->setStatusCode(200);
            $return = array($blogPost->getExpectedArray());
        } else {
            $this->getResponse()->setStatusCode(404);
            $return = array("errors" => \BlogPost\Entity\BlogPost::ERR_BLOGPOST_NOT_FOUND);
        }
        
        return new JsonModel(array("result" => $return));
    }
}