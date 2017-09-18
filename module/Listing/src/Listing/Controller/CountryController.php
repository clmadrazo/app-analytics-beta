<?php
namespace Listing\Controller;

use Zend\View\Model\JsonModel;

/**
 * This controller is mainly concerned about listing countries.
 */
class CountryController extends BaseController
{
    protected $_allowedMethod = "get";
    
    /**
     * @example
     *  [Request]
     *      GET /list/country
     *      Content-Type: application/json
     * 
     * @return \Zend\View\Model\JsonModel
     */
    public function getList()
    {
        return new JsonModel(array(
            'items' => $this->getEntitiesList('Listing\Entity\Country', false),
        ));
    }
}