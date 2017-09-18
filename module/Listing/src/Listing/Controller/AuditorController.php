<?php
namespace Listing\Controller;

use Zend\View\Model\JsonModel;

/**
 * This controller is mainly concerned about listing countries.
 */
class AuditorController extends BaseController
{
    protected $_allowedMethod = "get";

    /**
     * @example
     *  [Request]
     *      GET /list/writer
     *      Content-Type: application/json
     *
     * @return \Zend\View\Model\JsonModel
     */
    public function getList()
    {
        $em = $this->getEntityManager();
        $auditor_role = $em->find('User\Entity\Role', 4);
        $loggedInUser=$this->getLoggedUser();
        $customer=$loggedInUser->getCustomer()->getId();
        $auditors_list=$em->getRepository('User\Entity\RoleUser')->findBy(array('role'=>$auditor_role));
        $return=array();

        foreach ($auditors_list as $auditor){
            if ($auditor->getUser()->getCustomer()->getId()==$customer && $auditor->getUser()->getStatus()==1) {
                $return[] = $auditor->getExpectedArray();
            }
        }

        return new JsonModel(array("result" => $return));
    }
}