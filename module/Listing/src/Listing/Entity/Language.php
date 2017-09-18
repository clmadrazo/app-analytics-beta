<?php
namespace Listing\Entity;

use App\Mvc\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityManager;

/**
 * Entity Class representing a Language in our Application.
 *
 * @ORM\Entity
 * @ORM\Table(name="languages")
 */
class Language extends BaseEntity
{
    const ERR_NO_REGION = "There is no region assigned to this language";
    
    /**
     * Primary Identifier
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

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
     * Default Locale
     *
     * @ORM\Column(type="string")
     */
    protected $default_locale;

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
     * Returns the entity's ID
     * @return int
     */    
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Returns the entity's code
     * @return string
     */    
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Returns the entity's title
     * @return string
     */    
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Returns the entity's native
     * @return string
     */    
    public function getNative()
    {
        return $this->native;
    }

    /**
     * Returns the entity's default locale
     * @return string
     */    
    public function getDefaultLocale()
    {
        return $this->default_locale;
    }


    /**
     * @see App\Mvc\Entity\BaseEntity
     */
    public function exchangeArray($data)
    {
        $this->id = $this->_getValue($data, 'languageId');
        $this->code = $this->_getValue($data, 'code');
        $this->title = $this->_getValue($data, 'title');
        $this->default_locale = $this->_getValue($data, 'defaultLocale');
    }

    /**
     * @see App\Mvc\Entity\BaseEntity
     */
    public function getExpectedArray($params = array())
    {
        $region = $this->_entityManager->getRepository('Listing\Entity\LanguageRegion')
                    ->findOneBy(array('parent_id' => $this->getId()));

        if (count($region)) {
            return array(
                'language_region_id' => $region->getId(),
                'language_region_code' => $region->getCode(),
                'language_region_title' => $region->getTitle(),
                'language_region_native' => $region->getNative(),
                'language_id' => $this->getId(),
                'language_code' => $this->getCode(),
                'language_title' => $this->getTitle(),
                'language_native' => $this->getNative(),
                'language_default_locale' => $this->getDefaultLocale()
            );
        } else {
            throw new \Exception(self::ERR_NO_REGION);
        }
    }

}
