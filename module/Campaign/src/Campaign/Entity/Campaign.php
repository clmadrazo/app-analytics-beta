<?php
namespace Campaign\Entity;

use App\Mvc\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;
use Zend\XmlRpc\Value\String;
use Doctrine\ORM\EntityManager;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Post\Entity\Topic;
use Client\Entity\Client;
use Campaign\Entity\Customer;


/**
 * Entity Class representing a Campaign of our Application.
 *
 * @ORM\Entity
 * @ORM\Table(name="campaigns")
 */
class Campaign extends BaseEntity
{
    const ERR_CAMPAIGN_NOT_FOUND = "Campaign doesn't exists";
    const ERR_DETAILS_INVALID_JSON = "Campaign Details should be a valid JSON";
    const ERR_DEADLINES_INVALID_JSON = "Campaign Deadlines should be a valid JSON";

    /**
     * All available Campaign statuses.
     */
    const STATUS_ACTIVE = 1;
    
    /**
     * Primary Identifier
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * Customers table reference
     *
     * @ORM\ManyToOne(targetEntity="Campaign\Entity\Customer")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     */
    protected $customer;

    /**
     * Clients table reference
     *
     * @ORM\ManyToOne(targetEntity="Client\Entity\Client")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     */
    protected $client;





    /**
     * Name
     *
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * Guidelines
     *
     * @ORM\Column(type="string")
     */
    protected $guidelines;

    /**
     * Status
     *
     * @ORM\Column(type="integer")
     */
    protected $status;

    /**
     * Created date
     *
     * @ORM\Column(type="datetime")
     */
    protected $created;
    
    /**
     * Created By (User Table Reference)
     *
     * @ORM\ManyToOne(targetEntity="User\Entity\User")
     * @ORM\JoinColumn(name="created_by", referencedColumnName="id")
     */
    protected $createdBy;

    /**
     * Campaign Details array
     * @ORM\OneToMany(targetEntity="CampaignDetail", mappedBy="campaign", cascade={"persist"})
     */
    protected $campaign_details;
    
    
    /**
     * 
     * @param \Doctrine\ORM\EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager = null)
    {
        parent::__construct($entityManager);
    
        $this->campaign_details = new ArrayCollection();
        $this->created = new DateTime();        
    }
    
    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param Customer $customer
     * @return Campaign
     */
    public function setCustomer(Customer $customer)
    {
        $this->customer = $customer;
        return $this;
    }


    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param Client $client
     * @return Campaign
     */
    public function setClient(Client $client)
    {
        $this->client = $client;
        return $this;
    }




    /**
     * @param String $name
     * @return Campaign
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
     * @param String $guidelines
     * @return Campaign
     */
    public function setGuidelines($guidelines)
    {
        $this->guidelines = $guidelines;
        return $this;
    }

    /**
     * @return String
     */
    public function getGuideLines()
    {
        return $this->guidelines;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $status
     * @return Campaign
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }
    
    /**
     * @param String $created
     * @example 2001-12-23 19:15:00
     * @return Campaign
     */
    public function setCreated($created)
    {
        $this->created = new DateTime($created);
        return $this;
    }

    /**
     * @return datetime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param User $createdBy
     * @return Campaign
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;
        return $this;
    }

    /**
     * @return User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    public function setCampaignDetails(array $details, Campaign $campaign, $customer)
    {
       // $customer = $this->getLoggedUser()->getCustomer();
        foreach ($details as $detail) {





          //  $oDetail = new CampaignDetail();
            //$oDetail->setCampaign($this);
            $topicEntity = $this->_entityManager->getRepository('Post\Entity\Topic')
                ->findOneBy(array('title' => $detail->topic, 'customer'=>$customer));
            if (!$topicEntity){
            $topicEntity=new Topic();
                $topicEntity->setTitle($detail->topic);
                $topicEntity->setSlug($detail->topic);
                $topicEntity->setCustomer($customer);


                $this->_entityManager->persist($topicEntity);
                $this->_entityManager->flush();
            }
            $postType = $this->_entityManager->getRepository('Post\Entity\PostType')
                ->find($detail->postType);

            $campaignDetailEntity = $this->_entityManager->getRepository('Campaign\Entity\CampaignDetail')
                ->findOneBy(array('campaign' => $campaign, 'topic' => $topicEntity, 'postType'=>$postType));
            if (empty($campaignDetailEntity)) {
                $oDetail = new CampaignDetail();
            }
            else $oDetail=$campaignDetailEntity;

            $oDetail->setTopic($topicEntity);

            $oDetail->setPostType($postType);
            $oDetail->setPostsAmount($detail->postsAmount);
            $oDetail->setCampaign($campaign);
            $this->campaign_details[] = $oDetail;
        }
    }
    
    public function getCampaignDetails()
    {
        $details = array();
        
        foreach ($this->campaign_details as $detail) {
            $expectedArray = $this->_entityManager->getRepository('Campaign\Entity\CampaignDetail')
                ->find($detail->getId())->getExpectedArray();

            $details[] = $expectedArray;
        }

        return $details;
    }

    
    /**
     * @see App\Mvc\Entity\BaseEntity
     */
    public function getExpectedArray($params = array())
    {
        return array(
            'id'            => $this->getId(),
            'customer'      => $this->getCustomer()->getExpectedArray(),
            'client'        =>$this->getClient()->getExpectedArray(),
            'name'          => $this->getName(),
            'guidelines'    => $this->getGuideLines(),
            'status'        => $this->getStatus(),
            'created'       => $this->getCreated(),
            'createdBy'     => $this->getCreatedBy()->getExpectedArray(),
        );
    }
}