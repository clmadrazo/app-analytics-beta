<?php
namespace Campaign\Entity;

use App\Mvc\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;
use Zend\XmlRpc\Value\String;
use User\Entity\Role;
use Post\Entity\Topic;
use Post\Entity\PostType;
use DateTime;

/**
 * Entity Class representing a Campaign Deadline of our Application.
 *
 * @ORM\Entity
 * @ORM\Table(name="campaigns_deadlines")
 */
class CampaignDeadline extends BaseEntity
{  
    const ERR_DEADLINES_NOT_VALID = "Campaign Deadlines are invalid";
    
    /**
     * Primary Identifier
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * Campaign table reference
     *
     * @ORM\ManyToOne(targetEntity="Campaign\Entity\Campaign")
     * @ORM\JoinColumn(name="campaign_id", referencedColumnName="id")
     */
    protected $campaign;

    /**
     * Topic table reference
     *
     * @ORM\ManyToOne(targetEntity="Post\Entity\Topic")
     * @ORM\JoinColumn(name="topic_id", referencedColumnName="id")
     */
    protected $topic;

    /**
     * post_types table reference
     *
     * @ORM\ManyToOne(targetEntity="Post\Entity\PostType")
     * @ORM\JoinColumn(name="post_type", referencedColumnName="id")
     */
    protected $postType;



    /**
     * Role table reference
     *
     * @ORM\ManyToOne(targetEntity="User\Entity\Role")
     * @ORM\JoinColumn(name="role_id", referencedColumnName="id")
     */
    protected $role;
    
    /**
     * Campaign Deadline
     *
     * @ORM\Column(type="datetime")
     */
    protected $deadline;

    
    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Campaign
     */
    public function getCampaign()
    {
        return $this->campaign;
    }


    /**
     * @param Campaign $campaign
     * @return CampaignDeadline
     */
    public function setCampaign(Campaign $campaign)
    {
        $this->campaign = $campaign;
        return $this;
    }



    /**
     * @return Topic
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * @param Topic $topic
     * @return CampaignDeadline
     */
    public function setTopic(Topic $topic)
    {
        $this->topic = $topic;
        return $this;
    }

    /**
     * @return PostType
     */
    public function getPostType()
    {
        return $this->postType;
    }

    /**
     * @param PostType $postType
     * @return CampaignDeadline
     */
    public function setPostType(PostType $postType)
    {
        $this->postType = $postType;
        return $this;
    }




    /**
     * @return Role
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param Role $role
     * @return CampaignDeadline
     */
    public function setRole(Role $role)
    {
        $this->role = $role;
        return $this;
    }






    /**
     * @param String $deadline
     * @example 2001-12-23
     * @return User
     */
    public function setDeadline($deadline)
    {
        $deadline = implode('-', array_reverse(explode('/', $deadline)));
        $this->deadline = new DateTime($deadline);
        return $this;
    }

    /**
     * @return Object date
     */
    public function getDeadline()
    {
        $this->deadline->setTimeZone(new \DateTimeZone('UTC'));
        return $this->deadline;
    }
        
    
    /**
     * @see App\Mvc\Entity\BaseEntity
     */
    public function getExpectedArray($params = array())
    {
        return array(
            'id'            => $this->getId(),
            'campaign'      => $this->getCampaign()->getExpectedArray(),
            'topic'         => $this->getTopic()->getExpectedArray(),
            'role'          => $this->getRole()->getExpectedArray(),
            'deadline'      => $this->getDeadline()
        );
    }
}