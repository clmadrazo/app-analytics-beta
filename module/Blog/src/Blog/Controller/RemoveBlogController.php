<?php

namespace Blog\Controller;

use App\Mvc\Controller\RestfulController;
use Zend\View\Model\JsonModel;
use Zend\Mvc\MvcEvent;
use Blog\Entity\Blog;

/**
 * 
 */
class RemoveBlogController extends RestfulController {    
    protected $_allowedMethod = "delete";
    protected $_em = null;
    protected $blogId;

    public function onDispatch(MvcEvent $e)
    {
        $parts = explode("/", $this->getRequest()->getUri()->getPath());
        $last = array_pop($parts);
        $this->blogId = $last;

        return parent::onDispatch($e);
    }
    
    /**
     * This function will manage the request to remove a Blog Post
     * @example
     *  [Request]
     *      DELETE /blog-post/remove/[id]
     * @return 200 OK, or 404 with an array containing an 'errors' list
     */
    public function indexAction() {
        if (is_null($this->_em)) {
            $this->_em = $this->getEntityManager();
        }
        
        $blog = $this->_getBlog();

        if (!empty($blog)) {
          $blog->setStatus(0);
          $this->_em->persist($blog);
          $this->_em->flush();
          return $this->getResponse()->setStatusCode(200);
        } else {
          $this->getResponse()->setStatusCode(404);
          $return = array("errors" => \Blog\Entity\Blog::ERR_BLOG_NOT_FOUND);
        }

        return new JsonModel(array("result" => $return));
    }
    
    private function _getBlog()
    {
        $blog = $this->_em->getRepository('Blog\Entity\Blog')
                ->findOneBy(array('id' => $this->blogId));
        
        return $blog;
    }
}
