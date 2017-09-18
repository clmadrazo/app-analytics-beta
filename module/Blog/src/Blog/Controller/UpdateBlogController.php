<?php
namespace Blog\Controller;

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
class UpdateBlogController extends RestfulController
{  
    protected $_allowedMethod = "post";
    
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
        $em = $this->getEntityManager();
        $request = $this->getRequest();
        $requestData = $this->processBodyContent($request);

        $postRepository = $em->getRepository('Blog\Entity\Blog');
        $post = $postRepository->find($this->blogId);

        if (!empty($post)) {
            if(isset($requestData[0]['id_user']))
                $post->setIdUser($requestData[0]['id_user']);
            if(isset($requestData[0]['name']))
                $post->setName($requestData[0]['name']);
            if(isset($requestData[0]['name_updated']))
                $post->setNameUpdated($requestData[0]['name_updated']);
            if(isset($requestData[0]['status']))
                $post->setStatus($requestData[0]['status']);
            $em->persist($post);
            $em->flush();

            $this->getResponse()->setStatusCode(200);
            $return = array($post->getExpectedArray());

            }
        else {
            $this->getResponse()->setStatusCode(404);
            $return = array("errors" => \Post\Entity\Blog::ERR_BLOG_NOT_FOUND);
        }
        
        return new JsonModel(array("result" => $return));
    }
}
