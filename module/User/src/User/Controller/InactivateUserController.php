<?php
namespace User\Controller;

use App\Mvc\Controller\RestfulController;
use Zend\View\Model\JsonModel;
use User\Entity\User;

/**
 * This controller handles all user module requests.
 *
 */
class InactivateUserController extends RestfulController
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
        $user->setStatus(0);
        $this->em->persist($user);
        $this->em->flush();

        return new JsonModel(
            array("result" => $user->getExpectedArray())
        );
    }

}