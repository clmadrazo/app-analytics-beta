<?php
namespace Blog\Controller;

use App\Mvc\Controller\RestfulController;
use Zend\View\Model\JsonModel;
use Zend\Mvc\MvcEvent;

/**
 * This controller handles 
 * 
 */
class SearchBlogByUserController extends RestfulController
{
    protected $_allowedMethod = "get";
    protected $userId;

    public function onDispatch(MvcEvent $e)
    {
        $parts = explode("/", $this->getRequest()->getUri()->getPath());
        $last = array_pop($parts);
        $this->userId = $last;

        return parent::onDispatch($e);
    }

    public function indexAction()
    {
        $blogRepository = $this->getEntityManager()->getRepository('Blog\Entity\Blog');
        $blogs = $blogRepository->findBy(array('id_user'=>$this->userId,'status'=>1));

        $blogArray = array();
        foreach ($blogs as $blog) {
            $blogArray[] = $blog->getExpectedArray();
        }

        if (!empty($blogArray)) {
            $this->getResponse()->setStatusCode(200);
            $return = $blogArray;
        } else {
            $this->getResponse()->setStatusCode(404);
            $return = array("errors" => \Blog\Entity\Blog::ERR_BLOG_NOT_FOUND);
        }
        
        return new JsonModel(array("result" => $return));
    }
}