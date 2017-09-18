<?php
namespace Campaign\Entity;

use App\Mvc\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;
use Zend\XmlRpc\Value\String;
use Post\Entity\PostType;
use Post\Entity\Topic;

/**
 * Entity Class representing a Campaign Detail of our Application.
 *
 * @ORM\Entity
 * @ORM\Table(name="campaigns_details")
 */
class CampaignDetail extends BaseEntity
{  
    const ERR_DETAILS_NOT_VALID = "Campaign Details are invalid";
    
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
     * Post Type table reference
     *
     * @ORM\ManyToOne(targetEntity="Post\Entity\PostType")
     * @ORM\JoinColumn(name="post_type_id", referencedColumnName="id")
     */
    protected $postType;
    
    /**
     * Posts Amount
     *
     * @ORM\Column(type="integer")
     */
    protected $posts_amount;

    
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
     * @return CampaignDetail
     */
    public function setCampaign(Campaign $campaign)
    {
        $this->campaign = $campaign;
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
     * @return CampaignDetail
     */
    public function setPostType(PostType $postType)
    {
        $this->postType = $postType;
        return $this;
    }

    /**
     * @param Topic $topic
     * @return CampaignDetail
     */
    public function setTopic(Topic $topic)
    {
        $this->topic = $topic;
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
     * @param int $postsAmount
     * @return CampaignDetail
     */
    public function setPostsAmount($postsAmount)
    {
        $this->posts_amount = $postsAmount;
        return $this;
    }

    /**
     * @return String
     */
    public function getPostsAmount()
    {
        return $this->posts_amount;
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
            'postType'      => $this->getPostType()->getExpectedArray(),
            'postsAmount'   => $this->getPostsAmount(),
        );
    }
}