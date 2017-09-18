<?php
namespace User\Controller;

use App\Mvc\Controller\RestfulController;
use Zend\View\Model\JsonModel;
use User\Entity\User;

/**
 * This controller handles all user module requests.
 *
 */
class DeleteUserController extends RestfulController
{
    protected $_allowedMethod = "post";
    protected $em;
    protected $customer;
    /**
     * @example
     *  [Request]
     *      GET /user/list
     *      Content-Type: application/json
     *
     * @return \Zend\View\Model\JsonModel
     */
    public function indexAction()
    {
        $this->em = $this->getEntityManager();
        //$loggedInUser   = $this->getLoggedUser();
        //$this->customer = $loggedInUser->getCustomer();
        $request = $this->getRequest();
        $requestData = $this->processBodyContent($request);

        $id = $requestData[0]['userId'];

        if (!empty($id)){
            $user = $this->em->getRepository('User\Entity\User')->find($id);
        }
        $this->em->remove($user);
        $this->em->flush();

    }

    public function removeAccessTokenAction()
    {
        $request = $this->getRequest();
        $requestData = $this->processBodyContent($request);
        $token = $requestData[0]['token'];
        $this->em = $this->getEntityManager();
        $q = $this->em->createQuery('delete from User\Entity\AccessToken m where m.value = ?1');
        $q->setParameter(1,$token);
        $numDeleted = $q->execute();

        if ($numDeleted != null) {
            return $this->getResponse()->setStatusCode(200);
        } else {
            $this->getResponse()->setStatusCode(404);
            $return = array("errors" => "Access Token not exists");
        }

        return new JsonModel(array("result" => $return));

    }

}