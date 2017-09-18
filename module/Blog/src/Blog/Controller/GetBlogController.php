<?php
namespace Blog\Controller;

use App\Mvc\Controller\RestfulController;
use Zend\View\Model\JsonModel;
use Zend\Mvc\MvcEvent;

/**
 * This controller handles 
 * 
 */
class GetBlogController extends RestfulController
{  
    protected $_allowedMethod = "get";
    protected $blogId;

    public function onDispatch(MvcEvent $e)
    {
        $parts = explode("/", $this->getRequest()->getUri()->getPath());
        $last = array_pop($parts);
        $this->blogId = $last;

        return parent::onDispatch($e);
    }
    

    public function indexAction()
    {
        $blogRepository = $this->getEntityManager()->getRepository('Blog\Entity\Blog');
        $blog = $blogRepository->find($this->blogId);

        if (!empty($blog)) {
            $this->getResponse()->setStatusCode(200);
            $return = array($blog->getExpectedArray());
        } else {
            $this->getResponse()->setStatusCode(404);
            $return = array("errors" => \Blog\Entity\Blog::ERR_BLOG_NOT_FOUND);
        }
        
        return new JsonModel(array("result" => $return));
    }
}