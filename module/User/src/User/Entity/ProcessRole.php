<?php
namespace User\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="processes_roles")
 */
class ProcessRole
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    protected $id;

    /**
     * Process table reference
     *
     * @ORM\ManyToOne(targetEntity="User\Entity\Process")
     */
    protected $process;

    /**
     * Role table reference
     *
     * @ORM\ManyToOne(targetEntity="User\Entity\Role")
     */
    protected $role;

    
    public function getId()
    {
        return $this->id;
    }

    public function getProcess()
    {
        return $this->process;
    }
    
    public function setProcess(Process $process)
    {
        $this->process = $process;
        return $this;
    }
    
    public function getRole()
    {
        return $this->role;
    }
    
    public function setRole(Role $role)
    {
        $this->role = $role;
        return $this;
    }

    
    /**
     * @see App\Mvc\Entity\BaseEntity
     */
    public function getExpectedArray($params = array())
    {
        return array(
            'id'         => $this->getId(),
            'process_id' => $this->getProcess()->getId(),
            'role_id'    => $this->getRole()->getId(),
        );
    }
}
