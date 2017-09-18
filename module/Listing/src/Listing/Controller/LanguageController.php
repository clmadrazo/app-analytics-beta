<?php
namespace Listing\Controller;

use Zend\View\Model\JsonModel;

/**
 * This controller is mainly concerned about listing regions
 * with their appropriate language
 */
class LanguageController extends BaseController
{
    protected $_allowedMethod = "get";
    
    /**
     * @example
     *  [Request]
     *      GET /list/language
     *      Content-Type: application/json
     * 
     * @return \Zend\View\Model\JsonModel
     */
    public function getList()
    {
        $regions = $this->getLanguagesOrderByName('Listing\Entity\LanguageRegion');
        
        $entitiesArray = array();
        
        foreach ($regions as $region) {
            $region->setEntityManager($this->getEntityManager());
            $entitiesArray[] = $region->getExpectedArray();
        }

        return new JsonModel(array(
            'items' => $entitiesArray,
        ));
    }
    
    /**
     * Returns an array of entities
     * @return array
     */
    public function getLanguagesOrderByName($entityId)
    {
        $entityRepo = $this->getEntityManager()->getRepository($entityId);
        return $entityRepo->findBy(array(), array('native' => 'ASC'));
    }
}