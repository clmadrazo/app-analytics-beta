<?php
namespace Profile\Controller;

//rename
use App\Mvc\Controller\RestfulController;
use Zend\View\Model\JsonModel;
use Zend\Mvc\MvcEvent;


/**
 * This controller handles all client module requests.
 *
 */
class GetUserController extends RestfulController
{
    protected $_allowedMethod = "get";
    private $userId;
    private $em;

    public function onDispatch(MvcEvent $e)
    {
        $parts = explode("/", $this->getRequest()->getUri()->getPath());
        $last = array_pop($parts);
        $this->userId = $last;

        return parent::onDispatch($e);
    }


    /**
     * @example
     *  [Request]
     *      GET /client/list
     *      Content-Type: application/json
     *
     * @return \Zend\View\Model\JsonModel
     */
    public function indexAction()
    {

        $this->em = $this->getEntityManager();

        $return=array();
        $return = $this->_getUser()->getExpectedArray();


        return new JsonModel(array("result" => $return));
    }

    public function userInvitationAction()
    {

        $this->em = $this->getEntityManager();

        $return=array();
        $get = $this->_getUserInvitation();
        $return = $get->getExpectedArray();

        return new JsonModel(array("result" => $return));
    }

    private function _getUser()
    {
        return $this->em->getRepository('User\Entity\User')->find($this->userId);
    }

    private function _getUserInvitation()
    {
        return $this->em->getRepository('User\Entity\UserInvitation')->findOneBy(array('user'=>$this->userId));
    }

}