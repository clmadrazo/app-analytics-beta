<?php
namespace User\Entity;

use App\Mvc\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation\Object;
use Doctrine\ORM\EntityManager;
use DateTime;
use DoctrineModule\Validator\ObjectExists;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Entity Class representing a User of our Application.
 *
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User extends BaseEntity
{
    protected $_validationErrors = array();

    /**
     * All available User statuses.
     */
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    //Error messages
    const ERR_EMAIL_INVALID = "Invalid email";
    const ERR_USERNAME_EXISTS = "Username already exists";
    const ERR_EMAIL_EXISTS = "Email address already exists";
    const ERR_LANGUAGE_INVALID = "Invalid language id";
    const ERR_USER_NOT_FOUND = "User doesn't exists";

    const DEFAULT_BIRTHDATE = null;
    const DEFAULT_COUNTRY_ID = 'ar';
    const DEFAULT_LANGUAGE_REGION_ID = 1;
    const DEFAULT_LANGUAGE_ID = 1;


    /**
     * Primary Identifier
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    /**
     * Username
     *
     * @ORM\Column(type="string")
     */
    protected $username;

    /**
     * Password
     *
     * @ORM\Column(type="string")
     */
    protected $password;

    /**
     * Name
     *
     * @ORM\Column(type="string")
     */
    protected $name = "";

    /**
     * LastName
     *
     * @ORM\Column(type="string")
     */
    protected $lastname = "";

    /**
     * Date of Birth
     *
     * @ORM\Column(name="date_of_birth", type="date")
     */
    protected $dateOfBirth;

    /**
     * Email
     *
     * @ORM\Column(type="string", unique=true)
     */
    protected $email;

    /**
     * Countries table reference
     *
     * @ORM\ManyToOne(targetEntity="Listing\Entity\Country")
     */
    protected $country;

    /**
     * Town
     *
     * @ORM\Column(type="string")
     */
    protected $town = "";

    /**
     * Telephone
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $telephone;

    /**
     * Mobile
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $mobile;

    /**
     * Facebook id
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $facebook;

    /**
     * Twitter id
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $twitter;

    /**
     * Linkedin id
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $linkedin;

    /**
     * Google Plus id
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $gplus;

    /**
     * Timezone
     *
     * @ORM\Column(type="decimal", precision=10, scale=1)
     */
    protected $timezone = "";

    /**
     * Status
     *
     * @ORM\Column(type="smallint")
     */
    protected $status = "";

    /**
     * Lenguage Regions table reference
     * @ORM\ManyToOne(targetEntity="Listing\Entity\LanguageRegion")
     */
    protected $language_region;

    /**
     * Language Region id
     * @ORM\Column(type="integer")
     */
    protected $language_region_id;

    /**
     * Language table reference
     * @ORM\ManyToOne(targetEntity="Listing\Entity\Language")
     */
    protected $language;

    /**
     * Language id
     * @ORM\Column(type="integer")
     */
    protected $language_id;

    /**
     * Modified date
     *
     * @ORM\Column(type="datetime")
     */
    protected $updated;

    /**
     * Created date
     *
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     *
     * @ORM\Column(type="string")
     */
    protected $profile_url;

    /**
     *
     * @ORM\Column(type="string")
     */
    protected $social_token;

    /**
     *
     * @ORM\Column(type="string")
     */
    protected $facebook_long_lived_token;

    /**
     *
     * @ORM\Column(type="string")
     */
    protected $twitter_token;

    /**
     *
     * @ORM\Column(type="string")
     */
    protected $twitter_token_secret;

    /**
     *
     * @ORM\Column(type="string")
     */
    protected $reset_password_code;

    /**
     *
     * @ORM\Column(type="datetime")
     */
    protected $reset_password_code_expiration;

    /**
     * User Roles array
     * @ORM\OneToMany(targetEntity="RoleUser", mappedBy="user")
     */
    protected $user_roles;

    /**
     * Customers table reference
     *
     * @ORM\ManyToOne(targetEntity="Customer")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     */
    protected $customer;
    /**
     * Relative Path
     *
     * @ORM\Column(type="string")
     */
    protected $relative_path;

    /**
     * Steps
     *
     * @ORM\Column(type="integer")
     */
    protected $steps;


    /**
     *
     * @param \Doctrine\ORM\EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager = null)
    {
        parent::__construct($entityManager);

        $this->dateOfBirth = new DateTime(self::DEFAULT_BIRTHDATE);
        $this->updated = new DateTime();
        $this->created = new DateTime();

        $country = $this->_entityManager->find('Listing\Entity\Country', self::DEFAULT_COUNTRY_ID);
        $this->setCountry($country);
        $languageRegion = $this->_entityManager->find('Listing\Entity\LanguageRegion', self::DEFAULT_LANGUAGE_REGION_ID);
        $this->setLanguageRegion($languageRegion);
        $language = $this->_entityManager->find('Listing\Entity\Language', self::DEFAULT_LANGUAGE_ID);
        $this->setLanguage($language);

        $this->user_roles = new ArrayCollection();
    }


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return String
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param String $name
     * @return User
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
     * @param String $lastname
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
        return $this;
    }

    /**
     * @return String
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param String $dateOfBirth
     * @example 2001-12-23
     * @return User
     */
    public function setDateOfBirth($dateOfBirth)
    {
        $this->dateOfBirth = new DateTime($dateOfBirth);
        return $this;
    }

    /**
     * @return Object date
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    /**
     * @param String $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return String
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param Country $country
     * @return User
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return Listing\Entity\Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param String $town
     * @return User
     */
    public function setTown($town)
    {
        $this->town = $town;
        return $this;
    }

    /**
     * @return String
     */
    public function getTown()
    {
        return $this->town;
    }

    /**
     * @param String $telephone
     * @return User
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
        return $this;
    }

    /**
     * @return String
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * @param String $mobile
     * @return User
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;
        return $this;
    }

    /**
     * @return String
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }


    /**
     * @param string $facebook
     * @return User
     */
    public function setFacebook($facebook)
    {
        $this->facebook = $facebook;

        return $this;
    }

    /**
     * @return string
     */
    public function getFacebook()
    {
        return isset($this->facebook) ? $this->facebook : '';
    }

    /**
     * @param string $twitter
     * @return User
     */
    public function setTwitter($twitter)
    {
        $this->twitter = $twitter;
        return $this;
    }

    /**
     * @return string
     */
    public function getTwitter()
    {
        return isset($this->twitter) ? $this->twitter : '';
    }

    /**
     * @param string $linkedin
     * @return User
     */
    public function setLinkedin($linkedin)
    {
        $this->linkedin = $linkedin;
        return $this;
    }

    /**
     * @return string
     */
    public function getLinkedin()
    {
        return isset($this->linkedin) ? $this->linkedin : '';
    }

    /**
     * @param string $googlePlus
     * @return User
     */
    public function setGooglePlus($googlePlus)
    {
        $this->gplus = $googlePlus;
        return $this;
    }

    /**
     * @return string
     */
    public function getGooglePlus()
    {
        return isset($this->gplus) ? $this->gplus : '';
    }

    /**
     * @param int $timezone
     * @return User
     */
    public function setTimezone($timezone)
    {
        $this->timezone = $timezone;
        return $this;
    }

    /**
     * @return int
     */
    public function getTimezone()
    {
        return $this->timezone;
    }

    /**
     * @param int $status
     * @return User
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
     * @return string
     */
    public function getProfileUrl()
    {
        return $this->profile_url;
    }


    /**
     * @param string $profileUrl
     * @return User
     */
    public function setProfileUrl($profileUrl)
    {
        $this->profile_url = $profileUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getSocialToken()
    {
        return $this->social_token;
    }

    /**
     * @param string $socialToken
     * @return User
     */
    public function setSocialToken($socialToken)
    {
        $this->social_token = $socialToken;
        return $this;
    }

    /**
     * @return string
     */
    public function getFacebookLongLivedToken()
    {
        return $this->facebook_long_lived_token;
    }

    /**
     * @param string $facebookLongLivedToken
     * @return User
     */
    public function setFacebookLongLivedToken($facebookLongLivedToken)
    {
        $this->facebook_long_lived_token = $facebookLongLivedToken;
        return $this;
    }

    /**
     * @param LanguageRegion $languageRegion
     * @return User
     */
    public function setLanguageRegion($languageRegion)
    {
        $this->language_region = $languageRegion;
        return $this;
    }

    /**
     * @param integer $languageRegionId
     * @return User
     */
    public function setLanguageRegionId($languageRegionId)
    {
        $this->language_region_id = $languageRegionId;
        return $this;
    }

    /**
     * @return int
     */
    public function getLanguageRegion()
    {
        return $this->language_region;
    }

    /**
     * @param Language $language
     * @return User
     */
    public function setLanguage($language)
    {
        $this->language = $language;
        return $this;
    }

    /**
     * @param integer $languageId
     * @return User
     */
    public function setLanguageId($languageId)
    {
        $this->language_id = $languageId;
        return $this;
    }

    /**
     * @return int
     */
    public function getLanguage()
    {
        return $this->language;
    }

    public function getDefaultStatus()
    {
        return self::STATUS_PENDING;
    }

    /**
     * @param string $resetPasswordCode
     * @return User
     */
    public function setResetPasswordCode($resetPasswordCode)
    {
        $this->reset_password_code = $resetPasswordCode;
        return $this;
    }

    /**
     * @param Customer $customer
     * @return User
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;
        return $this;
    }

    /**
     * @return string
     */
    public function getResetPasswordCode()
    {
        return $this->reset_password_code;
    }

    /**
     * @param datetime $resetPasswordCodeExpiration
     * @return User
     */
    public function setResetPasswordCodeExpiration($resetPasswordCodeExpiration)
    {
        $this->reset_password_code_expiration = $resetPasswordCodeExpiration;
        return $this;
    }

    /**
     * @return datetime
     */
    public function getResetPasswordCodeExpiration()
    {
        return $this->reset_password_code_expiration;
    }

    /**
     * @return Campaign\Entity\Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @return string
     */
    public function getRelativePath()
    {
        return $this->relative_path;
    }

    /**
     * @param string
     */
    public function setRelativePath($relativePath)
    {
        $this->relative_path = $relativePath;
    }

    /**
     * @return int
     */
    public function getSteps()
    {
        return $this->steps;
    }

    public function setSteps($steps)
    {
        $this->steps = $steps;
    }


    /**
     * @see App\Mvc\Entity\BaseEntity
     */
    public function exchangeArray($data)
    {
        $this->id = (!empty($data['userId'])) ? $data['userId'] : null;
        $this->email = (!empty($data['email'])) ? $data['email'] : null;
        $this->password = (!empty($data['password'])) ? $data['password'] : null;
        $this->status = (!empty($data['status'])) ? $data['status'] : null;
        $this->created = (!empty($data['created'])) ? $data['created'] : null;
        $this->modified = (!empty($data['modified'])) ? $data['modified'] : null;
        $this->reset_password_code = (!empty($data['resetPasswordCode'])) ? $data['resetPasswordCode'] : null;
        $this->relative_path = (!empty($data['relativePath'])) ? $data['relativePath'] : null;
        $this->steps = (!empty($data['steps'])) ? $data['steps'] : null;
    }

    /**
     * @see App\Mvc\Entity\BaseEntity
     */
    public function getCleanArrayCopy()
    {
        $arrayCopy = $this->getArrayCopy();
        unset($arrayCopy['password']);
        unset($arrayCopy['resetPasswordCode']);
        return $arrayCopy;
    }
    public function getAbsolutePath(){
        $new_path = explode('\\', $this->relative_path);
        $path_amount = count($new_path);
        if ((count($new_path)<=1)&&(!empty($this->relative_path))){
            $new_path = explode('/', $this->relative_path);
            $path_amount = count($new_path);
        }
        if ($path_amount > 1) {
            $end_str = $new_path[$path_amount - 2] . '/' . $new_path[$path_amount - 1];
            $new_path_str = (empty($this->relative_path)) ? "" : 'ws/uploads/User/' . $end_str;
        }
        else
            return '';
        //in linux case
        //$root =  (empty($_SERVER["APPL_PHYSICAL_PATH"]))?$_SERVER['CONTEXT_DOCUMENT_ROOT']:$_SERVER["APPL_PHYSICAL_PATH"];
        //$rootReplace = $root.'/uploads/User/'.$this->getId().'/'.$new_path[$path_amount -1];
        //$new_path_str = str_replace('/var/www/html/social-platform/public/uploads/User/','ws/uploads/',$new_path);
        return $new_path_str;
    }

    /**
     * @see App\Mvc\Entity\BaseEntity
     */
    public function getExpectedArray($params = array())
    {
        return array(
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'firstName' => $this->name,
            'lastName' => $this->lastname,
            'created' => $this->created,
            'modified' => $this->updated,
            'relativePath' => $this->relative_path,
            'absolutePath' => $this->getAbsolutePath(),
            'role' => $this->getRoleIdsArray(),
            'language_id' => $this->language_id,
            'steps' => $this->steps
        );
    }

    /**
     * Return a complex entity
     */
    public function getExpectedFullArray()
    {
        $this->getLanguage()->setEntityManager($this->_entityManager);

        return array(
            'id'                => $this->getId(),
            'username'          => $this->getUsername(),
            'email'             => $this->getEmail(),
            'firstName'         => $this->getName(),
            'lastName'          => $this->getLastname(),
            'language'          => $this->getLanguageRegion()->getExpectedArray(),
            'fblink'            => $this->getFacebook(),
            'twlink'            => $this->getTwitter(),
            'lnlink'            => $this->getLinkedin(),
            'gplink'            => $this->getGooglePlus(),
            'profileUrl'        => $this->getProfileUrl(),
            'steps'             => $this->getSteps(),
            'created'           => $this->created,
            'modified'          => $this->updated,
            'customer'          => $this->getCustomer()->getExpectedArray()
        );
    }


    public function getValidationErrors()
    {
        return $this->_validationErrors;
    }

    protected function addValidationError($errorMessage)
    {
        $this->_validationErrors[] = $errorMessage;
    }

    public function getUserRoles()
    {
        return $this->user_roles;
    }

    public function getRoles()
    {
        $roles = array();

        foreach ($this->user_roles as $userRole) {
            $roles[] = $userRole->getRole();
        }

        return $roles;
    }

    public function getRoleIdsArray()
    {
        $roles = array();

        foreach ($this->user_roles as $userRole) {
            $roles[] = $userRole->getRole()->getId();
        }

        return $roles;
    }

    /**
     * Check if the user is valid
     * It checks: email address correct - email address and username
     * doesn't exists
     * @params boolean $isNew Indicate if this is a create action
     * @return boolean
     */
    public function isValid($isNew = false)
    {
        $return = true;
        $validatorEmailValid = new \Zend\Validator\EmailAddress();
        if (!$validatorEmailValid->isValid($this->email)) {
            $this->addValidationError(self::ERR_EMAIL_INVALID);
            $return = false;
        };

        //If it's an insert...
        if ($isNew) {
            //Check if the user exists
            if (!$this->_checkUserExists()) {
                $return = false;
            }
        }

        return $return;
    }

    /**
     * Check if the user exists in the database
     * It checks: email address and username doesn't exists
     * @return boolean
     */
    protected function _checkUserExists()
    {
        $return = true;
        $repo = $this->_entityManager->getRepository('User\Entity\User');

        $validatorUsername = new ObjectExists(
            array('object_repository' => $repo, 'fields' => array('username'))
        );
        $validatorEmailExists = new ObjectExists(
            array('object_repository' => $repo, 'fields' => array('email'))
        );

        if ($validatorUsername->isValid($this->getUsername())) {
            $this->addValidationError(self::ERR_USERNAME_EXISTS);
            $return = false;
        }
        if ($validatorEmailExists->isValid($this->getEmail())) {
            $this->addValidationError(self::ERR_EMAIL_EXISTS);
            $return = false;
        }

        return $return;
    }

    /*
     * This method populates a User entity with the request data
     * @params array $resquestData The request data
     *         boolean $isNew Indicate if this is a create action
     * @return User The user entity populated
     */
    public function populate(array $requestData, $isNew = false)
    {
        $util = new \App\Util\AppUtils();

        if ($isNew) {
            $this->setUsername($requestData[0]['email']);
            $this->setEmail($requestData[0]['email']);
        }

        if (isset($requestData[0]['name'])) {
            $this->setName($requestData[0]['name']);
        }
        if (isset($requestData[0]['lastName'])) {
            $this->setLastname($requestData[0]['lastName']);
        }
        if (isset($requestData[0]['password'])) {
            $this->setPassword($requestData[0]['password']);
        }
        if (isset($requestData[0]['writingExperience'])) {
            $this->setWritingExperience($requestData[0]['writingExperience']);
        }
        if (isset($requestData[0]['fblink'])) {
            $this->setFacebook($util->completeUrl($requestData[0]['fblink']));
        }
        if (isset($requestData[0]['twlink'])) {
            $this->setTwitter($util->completeUrl($requestData[0]['twlink']));
        }
        if (isset($requestData[0]['lnlink'])) {
            $this->setLinkedin($util->completeUrl($requestData[0]['lnlink']));
        }
        if (isset($requestData[0]['gplink'])) {
            $this->setGooglePlus($util->completeUrl($requestData[0]['gplink']));
        }
        if (isset($requestData[0]['user_educations'])) {
            $this->_setUserEducations($requestData[0]['user_educations']);
        }
        if (isset($requestData[0]['user_works'])) {
            $this->_setUserWorks($requestData[0]['user_works']);
        }
        if (isset($requestData[0]['languageRegionId'])) {
            $languageRegion = $this->_entityManager->find(
                    'Listing\Entity\LanguageRegion', $requestData[0]['languageRegionId']);

            if (!empty($languageRegion)) {
                $this->setLanguageRegionId($requestData[0]['languageRegionId']);
                $this->setLanguageId($languageRegion->getParent()->getId());
            } else {
                $this->addValidationError(self::ERR_LANGUAGE_INVALID);
            }
        }
        if (isset($requestData[0]['urlBlogger'])) {
            $this->_setUrlBlogger($util->completeUrl($requestData[0]['urlBlogger']));
        }
        if (isset($requestData[0]['urlWordpress'])) {
            $this->_setUrlWordpress($util->completeUrl($requestData[0]['urlWordpress']));
        }
        if (isset($requestData[0]['urlPersonalSite'])) {
            $this->_setUrlPersonalSite($util->completeUrl($requestData[0]['urlPersonalSite']));
        }
        if (isset($requestData[0]['wantToWork'])) {
            $this->setWantToWork($requestData[0]['wantToWork']);
        }
        if (isset($requestData[0]['price'])) {
            $profileHelper = new \Profile\Model\Helper\ProfileHelper();
            $profileHelper->setEntityManager($this->_entityManager);
            $profileHelper->setUserPrice($this, $requestData);
        }

        return $this;
    }


    public function getLoginData()
    {
        $data = array();
        $data['user'] = $this->getEmail();
        $data['userId'] = $this->getId();
        $data['password'] = $this->getPassword();

        return array($data);
    }

    public function getErrorMessages()
    {
        return !is_null($this->_inputFilter) ?
            $this->_inputFilter->getMessages() :
            array('errors' => $this->getValidationErrors());
    }
}
