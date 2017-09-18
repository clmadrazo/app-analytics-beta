<?php
namespace BlogPost\Controller;

use App\Mvc\Controller\RestfulController;
use Zend\View\Model\JsonModel;
use Zend\Mvc\MvcEvent;

/**
 * This controller handles 
 * 
 */
class GetAllBlogPostController extends RestfulController
{  
    protected $_allowedMethod = "get";
    protected $requestData;
    protected $user;

    public function onDispatch(MvcEvent $e)
    {
        $parts = explode("/", $this->getRequest()->getUri()->getPath());
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
        $blogPostList = $blogPostRepository->findAll();

        $blogpostArray = array();
        foreach ($blogPostList as $blogPost) {
                $blogpostArray[] = $blogPost->getExpectedArray();
            }
        

        if (!empty($blogpostArray)) {
            $this->getResponse()->setStatusCode(200);
            $return = $blogpostArray;
        } else {
            $this->getResponse()->setStatusCode(404);
            $return = array("errors" => \BlogPost\Entity\BlogPost::ERR_BLOGPOST_NOT_FOUND);
        }
        
        return new JsonModel(array("result" => $return));
    }
}