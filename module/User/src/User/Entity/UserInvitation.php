<?php
namespace User\Entity;

use App\Mvc\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation\Object;
use DateTime;

/**
 * Entity Class representing a User of our Application.
 *
 * @ORM\Entity
 * @ORM\Table(name="user_invitations")
 */
class UserInvitation extends BaseEntity
{
    //Error messages
    const INVITATION_NOT_VALID = "The invitations object is not valid";
    const ERR_EMAIL_INVALID = "Invalid email";
    const ERR_ROLE_INVALID = "Invalide role";
    const ERR_EMAIL_REGISTERED = "This email address already has an user account";


    /**
     * Primary Identifier
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    /**
     * Users table reference
     *
     * @ORM\ManyToOne(targetEntity="User\Entity\User")
     */
    protected $user;

    /**
     * Users table reference
     *
     * @ORM\ManyToOne(targetEntity="User\Entity\Role")
     */
    protected $role;

    /**
     * Guess Name
     *
     * @ORM\Column(type="string")
     */
    protected $guess_name;

    /**
     * Email
     *
     * @ORM\Column(type="string")
     */
    protected $email;

    /**
     * Sent
     *
     * @ORM\Column(type="datetime")
     */
    protected $sent;


    /**
     * Registration Code
     *
     * @ORM\Column(type="string")
     */
    protected $registration_code;

    /**
     * Last Access
     *
     * @ORM\Column(type="string")
     */
    protected $last_access;

    /**
     * Customers table reference
     *
     * @ORM\ManyToOne(targetEntity="Customer")
     * @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
     */
    protected $customer;

    /**
     * Status
     *
     * @ORM\Column(type="smallint")
     */
    protected $status;


    public function __construct()
    {
        $this->registration_code = md5(uniqid(rand(), true));
    }

    public function getStatus(){
        return $this->status;
    }
    public function setStatus($status){
        $this->status = $status;
    }
    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @param User $user
     * @return UserInvitation
     */
    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @param Role $role
     * @return UserInvitation
     */
    public function setRole(Role $role)
    {
        $this->role = $role;
        return $this;
    }

    /**
     * @param string $email
     * @return UserInvitation
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @param string $guess_name
     * @return UserInvitation
     */
    public function setGuessName($guess_name)
    {
        $this->guess_name = $guess_name;
        return $this;
    }

    /**
     * @param String $sent
     * @example 2001-12-23
     * @return UserInvitation
     */
    public function setSent($sent)
    {
        $this->sent = new DateTime($sent);
        return $this;
    }

    /**
     * @param String $last_access
     * @example 2001-12-23
     * @return UserInvitation
     */
    public function setLastAccess($last_access)
    {
        $this->last_access = new DateTime($last_access);
        return $this;
    }

    /**
     * @return Object date
     */
    public function getSent()
    {
        return $this->sent;
    }

    /**
     * @return string
     */
    public function getRegistrationCode()
    {
        return $this->registration_code;
    }

    /**
     * @return Object date
     */
    public function getLastAccess()
    {
        return $this->last_access;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return Role
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @return String
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return String
     */
    public function getGuessName()
    {
        return $this->guess_name;
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
     * @return Campaign\Entity\Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    public function send()
    {
        $href = 'http://' . $_SERVER['SERVER_NAME'] . '/#registration/' . $this->getRegistrationCode().'/'.$this->getUser()->getLanguage()->getCode();
        $message = '
                    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                    <html xmlns="http://www.w3.org/1999/xhtml">
                        <head>
                            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                            <title>Social Platform Invite</title>
                        </head>
                        <body>
                            You have been invited to register at Content Analytis App. Click <a href=' . $href . '>here</a>
                            to complete your registration now!<br />
                            If the link doesn\'t work for you, copy &amp; paste the following url in your browser:<br />'
            . $href . '
                            
                        </body>
                    </html>
        ';


        $url = 'api sendgrid';
        $user = 'username';
        $pass = 'password';
        // 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

        $template = '{
                        "filters": {
                          "templates": {
                            "settings": {
                              "enable": 1,
                              "template_id": "b3027402-0ef2-4c3c-912c-e8e10d206344"
                            }
                          }
                        }
                     }';

        $params = array(
            'api_user'  => $user,
            'api_key'   => $pass,
            'to'        => $this->getEmail(),
            'subject'   => 'Social Platform Invite',
            'html'      => $message,
            'text'      => $message,
            'from'      => 'eolivier@App.com',
            'x-smtpapi' => $template,
            'body'      => 'esto es el body',
        );


        $request =  $url.'api/mail.send.json';

        // Generate curl request
        $session = curl_init($request);
        // Tell curl to use HTTP POST
        curl_setopt ($session, CURLOPT_POST, true);
        // Tell curl that this is the body of the POST
        curl_setopt ($session, CURLOPT_POSTFIELDS, $params);
        // Tell curl not to return headers, but do return the response
        curl_setopt($session, CURLOPT_HEADER, false);
        // Tell PHP not to use SSLv3 (instead opting for TLS)
        curl_setopt($session, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

        // obtain response
        $response = curl_exec($session);
        curl_close($session);

        return ($response === '{"message":"success"}') ? true : false;
    }

    /**
     * @see App\Mvc\Entity\BaseEntity
     */
    public function getExpectedArray($params = array())
    {
        return array(
            'id' => $this->id,
            'user_id' => $this->getUser()->getId(),
            'role_id' => $this->getRole()->getId(),
            'email' => $this->email,
            'guess_name' => $this->guess_name,
            'sent' => $this->sent,
            'registration_code' => $this->getRegistrationCode(),
            'last_access' => $this->getLastAccess(),
            'status' => $this->getStatus(),
        );
    }
}
