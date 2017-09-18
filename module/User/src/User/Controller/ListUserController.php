<?php
namespace User\Controller;

use App\Mvc\Controller\RestfulController;
use Zend\View\Model\JsonModel;
use User\Entity\User;

/**
 * This controller handles all user module requests.
 *
 */
class ListUserController extends RestfulController
{
    protected $_allowedMethod = "get";
    protected $em;
    protected $customer;
    const ROLE_ADMIN  = 1;
    const ROLE_WRITER = 2;
    const ROLE_EDITOR = 3;
    const ROLE_AUDITOR= 4;

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
        $em = $this->em;
        $loggedInUser   = $this->getLoggedUser();
        $this->customer = $loggedInUser->getCustomer();

        $adminArray = $this->getUserRoles($this::ROLE_ADMIN);
        $writeArray   = $this->getUserRoles($this::ROLE_WRITER);
        $editorArray  = $this->getUserRoles($this::ROLE_EDITOR);
        $auditorArray = $this->getUserRoles($this::ROLE_AUDITOR);

        $this->populateInvitations($this::ROLE_ADMIN,$adminArray);
        $this->populateInvitations($this::ROLE_WRITER,$writeArray);
        $this->populateInvitations($this::ROLE_EDITOR,$editorArray);
        $this->populateInvitations($this::ROLE_AUDITOR,$auditorArray);

        return new JsonModel(array("result" =>
                array("admin"=>$adminArray,"write"=>$writeArray,"editor"=>$editorArray,'auditor'=>$auditorArray)
        ));
    }
    private function getUserRoles($roleId){
        $query = $this->em->createQuery("SELECT DISTINCT ru FROM User\Entity\RoleUser ru
                                    JOIN ru.role r
                                    JOIN ru.user u
                                    WHERE
                                    u.customer = ?1
                                    and u.status = 1
                                    and r.id=$roleId");
        $query->setParameter(1,$this->customer);
        $queryResult =  $query->getResult();
        foreach ($queryResult as $rec)
            $resultArray[] = $rec->getExpectedArray();
        return $resultArray;
    }
    private function populateInvitations($role,&$arrayResult){
        $query = $this->em->createQuery("SELECT  ue FROM User\Entity\UserInvitation ue
                                    JOIN ue.role r
                                    where
                                    ue.customer = ?1
                                    and r.id= $role
                                    and ue.status is NULL");
        $query->setParameter(1,$this->customer);
        $arrayInvite = $query->getResult();
        foreach ($arrayInvite as $rec)
            $arrayResult[] = array("user"=>$rec->getExpectedArray(),"invitation"=>1);
    }
}