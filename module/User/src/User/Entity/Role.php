<?php
namespace User\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="roles")
 */
class Role
{
    const ERR_ROLE_NOT_FOUND = "Role doesn't exists";
            
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $title;

    /**
     * Processes array
     * @ORM\OneToMany(targetEntity="ProcessRole", mappedBy="role")
     */
    protected $role_processes;

    
    /**
     * 
     * @param \Doctrine\ORM\EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager = null)
    {
        parent::__construct($entityManager);
    
        $this->role_processes = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }
    
    public function getRoleProcesses()
    {
        return $this->role_processes;
    }

    public function getProcesses()
    {
        $processes = array();

        foreach ($this->role_processes as $roleProcess) {
            $processes[] = $roleProcess->getProcess();
        }
        
        return $processes;
    }
    
    /**
     * @see App\Mvc\Entity\BaseEntity
     */
    public function getExpectedArray($params = array())
    {
        return array(
            'id'        => $this->getId(),
            'title'     => $this->getTitle(),
        );
    }
}
