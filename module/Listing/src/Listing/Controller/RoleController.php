<?php
namespace Listing\Controller;

use Zend\View\Model\JsonModel;
use User\Entity\Role;

/**
 * This controller is mainly concerned about listing countries.
 */
class RoleController extends BaseController
{
    protected $_allowedMethod = "get";

    /**
     * @example
     *  [Request]
     *      GET /list/role
     *      Content-Type: application/json
     *
     * @return \Zend\View\Model\JsonModel
     */
    public function getList()
    {
        $em = $this->getEntityManager();
        $roles = $em->getRepository('User\Entity\Role')->findAll();

        foreach ($roles as $role) {
            $return[] = $role->getExpectedArray();
        }

        return new JsonModel(array("result" => $return));
    }
}