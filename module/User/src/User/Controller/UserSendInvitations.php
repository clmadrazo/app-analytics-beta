<?php

namespace User\Controller;

use App\Mvc\Controller\RestfulController;
use User\Entity\User;
use Zend\View\Model\JsonModel;

/**
 *
 */
class UserSendInvitations extends RestfulController {

    protected $_allowedMethod = 'post';


    /**
     * @link /docs/user/send-invitations
     * @return Zend\View\Model\JsonModel
     */
    public function indexAction()
    {
        $return = array();

        $em = $this->getEntityManager();
        $request = $this->getRequest();
        $requestData = $this->processBodyContent($request);

        $invitations = json_decode($requestData[0]['invitations']);

//        $a = array(array("email" => "email1", "role" => "1"), array("email" => "email2", "role" => "2"));
        //die("A");
        //echo json_encode($a);
        //      var_dump($invitations);die;;
        //     var_dump($a);
        //    die("HOLA");
        if (!is_null($invitations)) {
            $sents = array();
            $valid = true;
            foreach ($invitations as $invitation) {
                if (!isset($invitation->email) || !isset($invitation->role)) {
                    $valid = false;
                }
                if ($valid) {
                    $roleRepository = $em->getRepository('User\Entity\Role');
                    $role = $roleRepository->find($invitation->role);
                    if (empty($role)) {
                        $return = array("errors" => \User\Entity\Role::ERR_ROLE_NOT_FOUND);
                    } else {
                        $userInvite = new \User\Entity\UserInvitation();
                        $userInvite->setUser($this->getLoggedUser());
                        $userInvite->setEmail($invitation->email);
                        $userInvite->setRole($role);
                        $loggedInUser=$this->getLoggedUser();
                        $customer=$loggedInUser->getCustomer();
                        $userInvite->setCustomer($customer);
                        if (isset($invitation->guess_name)) {
                            $userInvite->setGuessName($invitation->guess_name);
                        }
                        $userInvite->setSent(date('Y-m-d h:i:s'));
                        if ($userInvite->send()) {
                            $em->persist($userInvite);
                            $sents[] = $userInvite->getExpectedArray();
                        } else {
                            $this->getResponse()->setStatusCode(404);
                            return new JsonModel(array("errors" => "problemas ao enviar e-mail"));
                        }
                    }
                } else {
                    $return = array("errors" => \User\Entity\UserInvitation::INVITATION_NOT_VALID);
                }
                $return = $sents;
                $em->flush();
            }
        } else {
            $this->getResponse()->setStatusCode(404);
            $return = array("errors" => 'The invitations object is not valid');
        }

        return new JsonModel(array("result" => $return));
    }
}