<?php
namespace Campaign\Controller;

use App\Mvc\Controller\RestfulController;
use Zend\View\Model\JsonModel;




/**
 * This controller handles all campaign module requests.
 *
 */
class ListCampaignController extends RestfulController
{
    protected $_allowedMethod = "get";

    /**
     * @example
     *  [Request]
     *      GET /campaign/list
     *      Content-Type: application/json
     *
     * @return \Zend\View\Model\JsonModel
     */
    public function indexAction()
    {
        $em = $this->getEntityManager();
        $userId = $this->getLoggedUser()->getId();
        $customer = $this->getLoggedUser()->getCustomer();
        $userRoles = $this->getLoggedUser()->getRoleIdsArray();

        $isAdmin  = in_array("1",$userRoles);
        $isWriter = in_array("2",$userRoles);
        $isEditor = in_array("3",$userRoles);
        $client_id = (integer) @$_GET["client_id"];

        $complementaryFilters ="";
        if (($isWriter || $isEditor)&& (!$isAdmin))
            $complementaryFilters = " AND ( c.id in (SELECT DISTINCT ca.id FROM \Post\Entity\Post p
                                                    JOIN p.campaign ca
                                                    JOIN ca.client cli
                                                    JOIN p.topic t
                                                    LEFT JOIN \Post\Entity\TopicUserAssignment pa with pa.topic = t
                                                    LEFT JOIN pa.user u
                                                    WHERE
                                                    c.id= ca.id
                                                    AND ( (pa.id IS NOT NULL AND u.id =  $userId) OR p.assignedTo = $userId )
                                                    )
                                         )";
        if ($client_id){
            $client = $em->getRepository("Client\Entity\Client")->find($client_id);
            $complementaryFilters .= " AND c.client = ?2";
        }

        $query = $em->createQuery("SELECT  c FROM \Campaign\Entity\Campaign c
                                        JOIN c.customer cc
                                        WHERE
                                        c.customer = ?1
                                        $complementaryFilters")
            ->setParameter(1,$customer);
        if ($client_id)
            $query->setParameter(2,$client);

        $queryResult = $query->getResult();
        $resultArray = array();
        foreach ($queryResult as $rec)
            $resultArray[] = $rec->getExpectedArray();

        return new JsonModel(array("result" => $resultArray));
    }
}