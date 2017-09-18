<?php

namespace BlogPost\Controller;

use App\Mvc\Controller\RestfulController;
use Zend\View\Model\JsonModel;
use Zend\Mvc\MvcEvent;
use BlogPost\Entity\BlogPost;

/**
 * 
 */
class RemoveBlogPostController extends RestfulController {    
    protected $_allowedMethod = "delete";
    protected $_em = null;
    protected $keyApi;

    public function onDispatch(MvcEvent $e)
    {
        $parts = explode("/", $this->getRequest()->getUri()->getPath());
        $last = array_pop($parts);
        $this->keyApi = $last;

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
        $this->_em = $this->getEntityManager();
        $parts = explode("-", $this->keyApi);
        $first = array_shift($parts);
        $user = $first;
        $cm = $this->_em->getClassMetadata('BlogPost\Entity\BlogPost');
        $cm->setTableName('ca_blog_posts_user_'.$user);
        $request = $this->getRequest();
        $requestData = $this->processBodyContent($request);
        if(isset($requestData[0]['post_id']))
        {
            $q = $this->_em->createQuery('update BlogPost\Entity\BlogPost m set m.status = 0 where m.key_api = ?1 and m.post_id = ?2');
            $q->setParameter(1,$this->keyApi);
            $q->setParameter(2,$requestData[0]['post_id']);
        }
        else
        {
            $q = $this->_em->createQuery('update BlogPost\Entity\BlogPost m set m.status = 0 where m.key_api = ?1');
            $q->setParameter(1,$this->keyApi);
        }
        $numDeleted = $q->execute();

        if ($numDeleted != null) {
          return $this->getResponse()->setStatusCode(200);
        } else {
          $this->getResponse()->setStatusCode(404);
          $return = array("errors" => \BlogPost\Entity\BlogPost::ERR_BLOGPOST_NOT_FOUND);
        }

        return new JsonModel(array("result" => $return));
    }
}
