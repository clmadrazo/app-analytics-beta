<?php
namespace Listing\Controller;

use Zend\View\Model\JsonModel;

/**
 * This controller is mainly concerned about listing languages.
 */
class LanguageRegionController extends BaseController
{
    /**
     * @example
     *  [Request]
     *      GET /list/region
     *      Content-Type: application/json
     *
     * @return \Zend\View\Model\JsonModel
     */
    public function getList()
    {
        return new JsonModel(array(
            'items' => $this->getEntitiesList('Listing\Entity\LanguageRegion'),
        ));
    }
}