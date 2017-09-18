<?php
namespace Listing\Controller;

use Zend\View\Model\JsonModel;

/**
 * This controller is mainly concerned about listing countries.
 */
class MediaController extends BaseController
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
        $postTypes = $em->getRepository('Post\Entity\PostType')->findAll();

        foreach ($postTypes as $postType) {
            $return[] = $postType->getExpectedArray();
        }

        return new JsonModel(array("result" => $return));
    }
}