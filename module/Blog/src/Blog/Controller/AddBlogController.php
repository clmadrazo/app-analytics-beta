<?php
namespace Blog\Controller;

use App\Mvc\Controller\RestfulController;
use Zend\View\Model\JsonModel;
use Zend\Mvc\MvcEvent;
use Blog\Entity\Blog;


/**
 * This controller handles all post module requests.
 * 
 */
class AddBlogController extends RestfulController
{  
    protected $_allowedMethod = "post";
    protected $requestData;
    protected $_em = null;

    public function indexAction()
    {
        $this->_em = $this->getEntityManager();
        $request = $this->getRequest();
        $this->requestData = $this->processBodyContent($request);
        $name = $this->requestData[0]['name'];
        $id_user = $this->requestData[0]['id_user'];
        $blogby = $this->_em->getRepository('Blog\Entity\Blog')->findBy(array('id_user'=>$id_user,'name'=>$name));
        $return = null;

        if (empty($blogby)) {
            $blog = new \Blog\Entity\Blog;
            $blog->setIdUser($this->requestData[0]['id_user']);
            $blog->setName($this->requestData[0]['name']);
            $blog->setNameUpdated($this->requestData[0]['name_updated']);
            $blog->setStatus(1);
            $this->_em->persist($blog);
            $this->_em->flush();
            $this->getResponse()->setStatusCode(200);
            $return = array($blog->getExpectedArray());
        } else {
            $this->getResponse()->setStatusCode(404);
            $return = array("errors" => \Blog\Entity\Blog::ERR_BLOG_ALREADY_EXISTS);
        }

        
        return new JsonModel(array("result" => $return));
    }
}

