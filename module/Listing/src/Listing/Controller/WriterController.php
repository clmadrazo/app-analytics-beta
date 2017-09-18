<?php
namespace Listing\Controller;

use Zend\View\Model\JsonModel;

/**
 * This controller is mainly concerned about listing countries.
 */
class WriterController extends BaseController
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
        $writer_role = $em->find('User\Entity\Role', 2);
        $loggedInUser=$this->getLoggedUser();
        $customer=$loggedInUser->getCustomer()->getId();
        $writers_list=$em->getRepository('User\Entity\RoleUser')->findBy(array('role'=>$writer_role));
        $return=array();

        foreach ($writers_list as $writer){
            if ($writer->getUser()->getCustomer()->getId()==$customer && $writer->getUser()->getStatus()==1) {
                $return[] = $writer->getExpectedArray();
            }
        }

        return new JsonModel(array("result" => $return));
    }
}