<?php
namespace Listing\Entity;

use App\Mvc\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entity Class representing a Language in our Application.
 *
 * @ORM\Entity
 * @ORM\Table(name="languages_regions")
 */
class LanguageRegion extends BaseEntity
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
     * Parent
     *
     * @ORM\ManyToOne(targetEntity="Language")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    protected $parent;

    /**
     * Parent id
     *
     * @ORM\Column(type="integer")
     */
    protected $parent_id;

    /**
     * Language Code
     *
     * @ORM\Column(type="string")
     */
    protected $code;

    /**
     * Language title
     *
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * Language native title
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $native;

    /**
     * Google Domain
     *
     * @ORM\Column(type="string")
     */
    protected $google_domain;

    /**
     * Status
     *
     * @ORM\Column(type="smallint")
     */
    protected $status;

    /**
     * Weight
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $weight;

    /**
     * Updated
     *
     * @ORM\Column(type="datetime")
     */
    protected $updated;

    /**
     * Created
     *
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * Returns id
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns code
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Returns title
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Returns native
     * @return string
     */
    public function getNative()
    {
        return $this->native;
    }

    /**
     * Returns the parent (Language) entity
     * @return Language
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @see App\Mvc\Entity\BaseEntity
     */
    public function exchangeArray($data)
    {
        $this->id = $this->_getValue($data, 'languageId');
        $this->code = $this->_getValue($data, 'code');
        $this->title = $this->_getValue($data, 'title');
    }
    
    /**
     * @see App\Mvc\Entity\BaseEntity
     */
    public function getExpectedArray($params = array())
    {
        $language = $this->_entityManager->getRepository('Listing\Entity\Language')
                    ->findOneBy(array('id' => $this->getParent()->getId()));

        if (count($language)) {
            return array(
                'language_region_id' => $this->getId(),
                'language_region_code' => $this->getCode(),
                'language_region_title' => $this->getTitle(),
                'language_region_native' => $this->getNative(),
                'language_id' => $language->getId(),
                'language_code' => $language->getCode(),
                'language_title' => $language->getTitle(),
                'language_native' => $language->getNative(),
                'language_default_locale' => $language->getDefaultLocale()
            );
        } else {
            throw new \Exception(self::ERR_NO_REGION);
        }
    }
}
