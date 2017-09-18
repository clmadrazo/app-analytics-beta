<?php
namespace Listing\Controller;

use Zend\View\Model\JsonModel;

/**
 * This controller is mainly concerned about listing countries.
 */
class SuscriptionTypeController extends BaseController
{
    protected $_allowedMethod = "get";

    /**
     * @example
     *  [Request]
     *      GET /list/suscription_type
     *      Content-Type: application/json
     *
     * @return \Zend\View\Model\JsonModel
     */
    public function getList()
    {
        $em = $this->getEntityManager();
        $suscriptions = $em->getRepository('Listing\Entity\SuscriptionType')->findAll();

        foreach ($suscriptions as $s) {
            $return[] = $s->getExpectedArray();
        }

        return new JsonModel(array("result" => $return));
    }
}