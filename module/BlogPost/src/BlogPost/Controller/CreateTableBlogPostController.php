<?php
namespace BlogPost\Controller;

use App\Mvc\Controller\RestfulController;
use Zend\View\Model\JsonModel;
use Zend\Mvc\MvcEvent;

/**
 * This controller handles 
 * 
 */
class CreateTableBlogPostController extends RestfulController
{  
    protected $_allowedMethod = "post";

    public function indexAction()
    {
        $em = $this->getEntityManager();
        $conn = $em->getConnection();
        $sm = $conn->getSchemaManager();
        $request = $this->getRequest();
        $this->requestData = $this->processBodyContent($request);
        $user = $this->requestData[0]['userId'];
        $table = $sm->listTableDetails('ca_blog_posts');
        $tableName = 'ca_blog_posts_user_'.$user;
        $columns = array();
        $indexes = array();
        $fkConstraints = array();
        $options = array();
        $table->__construct($tableName,$columns,$indexes,$fkConstraints,0,$options);
        $check = $sm->tablesExist(array($tableName));
        if(!$check)
        {
            $sm->createTable($table);
            $em->
            $this->getResponse()->setStatusCode(200);
            $return = array(array('table' => $tableName));
        }
        else {
            $this->getResponse()->setStatusCode(404);
            $return = array("errors" => "Blog Post User Table already exists");
        }

        return new JsonModel(array("result" => $return));
    }
}