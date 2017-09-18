<?php
namespace User\Entity;

use App\Mvc\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entity Class representing a Customer of our Application.
 *
 * @ORM\Entity
 * @ORM\Table(name="customers")
 */
class Customer extends BaseEntity
{
    const ERR_CUSTOMER_NOT_FOUND = "Customer doesn't exists";

    /**
     * Primary Identifier
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Name
     *
     * @ORM\Column(type="string")
     */
    protected $name;


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param String $name
     * @return Customer
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return String
     */
    public function getName()
    {
        return $this->name;
    }


    /**
     * @see App\Mvc\Entity\BaseEntity
     */
    public function getExpectedArray($params = array())
    {
        return array(
            'id'            => $this->getId(),
            'name'          => $this->getName(),
        );
    }
}
