<?php
namespace Blog\Controller;

use App\Mvc\Controller\RestfulController;
use Zend\View\Model\JsonModel;
use Zend\Mvc\MvcEvent;

/**
 * This controller handles
 *
 */
class GetAllBlogController extends RestfulController
{
    protected $_allowedMethod = "get";

    public function indexAction()
    {
        $blogRepository = $this->getEntityManager()->getRepository('Blog\Entity\Blog');
        $blogList = $blogRepository->findAll();

        $blogArray = array();
        foreach ($blogList as $blog) {
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