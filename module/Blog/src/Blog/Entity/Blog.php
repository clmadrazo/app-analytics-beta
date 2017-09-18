<?php
namespace Blog\Entity;

use App\Mvc\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityManager;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Entity Class representing a Blog of our Application.
 *
 * @ORM\Entity
 * @ORM\Table(name="ca_blog")
 */
class Blog extends BaseEntity
{
    const ERR_BLOG_NOT_FOUND = "Blog doesn't exists";
    const ERR_BLOG_ALREADY_EXISTS = "Blog already exists";

    /**
     * Primary Identifier
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Id_user
     *
     * @ORM\Column(type="integer")
     */
    protected $id_user;

    /**
     * Name
     *
     * @ORM\Column(type="string")
     */
    protected $name = "";

    /**
     * Name_updated
     *
     * @ORM\Column(type="string")
     */
    protected $name_updated = "";

    /**
     * Status
     *
     * @ORM\Column(type="integer")
     */
    protected $status;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id_user
     * @return Blog
     */
    public function setIdUser($id_user)
    {
        $this->id_user = $id_user;
        return $this;
    }

    /**
     * @return int
     */
    public function getIdUser()
    {
        return $this->id_user;
    }

    /**
     * @param string $name
     * @return Blog
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
     * @param string $name_updated
     * @return Blog
     */
    public function setNameUpdated($name_updated)
    {
        $this->name_updated = $name_updated;
        return $this;
    }

    /**
     * @return String
     */
    public function getNameUpdated()
    {
        return $this->name_updated;
    }

    /**
     * @param int $status
     * @return Blog
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @see App\Mvc\Entity\BaseEntity
     */
    public function getExpectedArray($params = array())
    {
        return array(
            'id'                        => $this->getId(),
            'id_user'                   => $this->getIdUser(),
            'name'                   => $this->getName(),
            'name_updated'                   => $this->getNameUpdated(),
            'status'                   => $this->getStatus(),
        );
    }
}
