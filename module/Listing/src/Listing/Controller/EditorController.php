<?php
namespace Listing\Controller;

use Zend\View\Model\JsonModel;

/**
 * This controller is mainly concerned about listing countries.
 */
class EditorController extends BaseController
{
    protected $_allowedMethod = "get";

    /**
     * @example
     *  [Request]
     *      GET /list/editor
     *      Content-Type: application/json
     *
     * @return \Zend\View\Model\JsonModel
     */
    public function getList()
    {
        $em = $this->getEntityManager();
        $editor_role = $em->find('User\Entity\Role', 3);
        $loggedInUser=$this->getLoggedUser();
        $customer=$loggedInUser->getCustomer()->getId();
        $editors_list=$em->getRepository('User\Entity\RoleUser')->findBy(array('role'=>$editor_role));
        $return=array();

        foreach ($editors_list as $editor){
            if ($editor->getUser()->getCustomer()->getId()==$customer && $editor->getUser()->getStatus()==1) {
                $return[] = $editor->getExpectedArray();
            }
        }

        return new JsonModel(array("result" => $return));
    }
}