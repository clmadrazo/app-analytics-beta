<?php
namespace Listing\Entity;

use App\Mvc\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entity Class representing a Country in our Application.
 *
 * @ORM\Entity
 * @ORM\Table(name="countries")
 */
class Country extends BaseEntity
{
    /**
     * Primary Identifier
     *
     * @ORM\Id
     * @ORM\Column(type="string")
     * @ORM\GeneratedValue(strategy="NONE")
     */
    protected $id;

    /**
     * Country Name
     *
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * Country Name
     *
     * @ORM\Column(type="string")
     */
    protected $printable_name;

    /**
     * Country ISO3
     *
     * @ORM\Column(type="string", unique=true)
     */
    protected $iso3;

    /**
     * Country code
     *
     * @ORM\Column(type="smallint")
     */
    protected $numcode;

    /**
     * Telephone Code
     *
     * @ORM\Column(type="string")
     */
    protected $telephone_code;

    /**
     * Weight
     *
     * @ORM\Column(type="integer")
     */
    protected $weight;

    /**
     * @see App\Mvc\Entity\BaseEntity
     */
    public function exchangeArray($data)
    {
        $this->id = $this->_getValue($data, 'countryId');
        $this->iso3 = $this->_getValue($data, 'iso3');
        $this->name = $this->_getValue($data, 'name');
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @see App\Mvc\Entity\BaseEntity
     */
    public function getExpectedArray($params = array())
    {
        return array(
            'countryCode' => $this->id,
            'countryName' => $this->printable_name,
        );
    }
}
