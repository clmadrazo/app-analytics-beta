<?php
namespace Listing\Entity;

use App\Mvc\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Entity
 * @ORM\Table(name="suscription_type")
 */
class SuscriptionType extends BaseEntity
{
    /**
     * Primary Identifier
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Description
     *
     * @ORM\Column(type="string")
     */
    protected $description;

    /**
     * Cost
     *
     * @ORM\Column(type="float")
     */
    protected $cost;

    public function getId()
    {
        return $this->id;
    }

    public function getExpectedArray($params = array())
    {
        return array(
            'id' => $this->id,
            'description' => $this->description,
            'cost' => $this->cost,
        );
    }
}
