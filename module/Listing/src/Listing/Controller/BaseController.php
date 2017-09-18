<?php
namespace Listing\Controller;

use App\Mvc\Controller\RestfulController;
use Zend\View\Model\JsonModel;

/**
 * Provides the base functionality that is common across all
 * controllers in this module.
 */
class BaseController extends RestfulController
{
    /**
     * Returns an array of entities
     * @return array
     */
    public function getEntities($entityId)
    {
        $entityRepo = $this->getEntityManager()->getRepository($entityId);
        return $entityRepo->findAll();
    }

    /**
     * Returns an array of arrays (entities as arrays)
     * @return array
     */
    public function getEntitiesList($entityId, $complete = true)
    {
        $entityRepo = $this->getEntityManager()->getRepository($entityId);
        $entitiesList = $entityRepo->findAll();

        // @todo We could avoid this by using a different JsonModel class
        //      or some other alternative
        $entitiesArray = array();
        foreach ($entitiesList as $item) {
            if ($complete) {
                $entitiesArray[] = $item->getArrayCopy();
            } else {
                $entitiesArray[] = $item->getExpectedArray();
            }
        }

        return $entitiesArray;
    }

    /**
     * @endpoint GET /list
     * @return \Zend\View\Model\JsonModel
     */
    public function getList()
    {
        $this->notFoundAction();
    }

    /**
     * @endpoint GET /list/:id
     * @param type $id
     * @return \Zend\View\Model\JsonModel
     */
    public function get($id)
    {
        $this->notFoundAction();
    }

    /**
     * @endpoint POST /list
     */
    public function create($data)
    {
        $this->notFoundAction();
    }

    /**
     * @endpoint PUT /list
     * @param type $id
     * @param type $data
     */
    public function update($id, $data)
    {
        $this->notFoundAction();
    }

    /**
     * @endpoint DELETE /list/:id
     * @param type $id
     */
    public function delete($id)
    {
        $this->notFoundAction();
    }
}
