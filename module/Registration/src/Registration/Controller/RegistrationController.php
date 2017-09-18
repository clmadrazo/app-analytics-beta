<?php
namespace Registration\Controller;

use Authentication\Model\Helper\AuthenticationHelper;
use App\Mvc\Controller\RestfulController;
use User\Entity;
use Zend\View\Model\JsonModel;

/**
 *
 */
class RegistrationController extends RestfulController
{
    protected $_allowedMethod = "post";
    private  $em;
    private $alert;

    /**
     * This function will manage the request to create a new User
     * @example
     *  [Request]
     *      POST /registration
     * see http://www.yami-ec.com.ar/wiki/index.php?title=Registration
     * @return an array model that contains the user created if it was
     * succesful, or containing an 'errors' list
     */
    public function indexAction()
    {
        $requestData = $this->processBodyContent($this->getRequest());

        $return = $this->doRegistration($requestData);

        return new JsonModel($return);
    }

    /*
     * This method populates a User entity with the request data
     * @params array $resquestData The request data
     * @return User The user entity populated
     */
    private function _fillUser(array $requestData)
    {
        $authHelper = new AuthenticationHelper();
        @$id = $requestData[0]['userId'];
        if (!empty($id)){
            $user = $this->em->getRepository('User\Entity\User')->find($id);
            $user->setStatus(\User\Entity\User::STATUS_ACTIVE);
        }
        else{
            $user = new \User\Entity\User($this->em);
            $user->setStatus(\User\Entity\User::STATUS_INACTIVE);
            $this->alert = 1;
        }

        $user->setName($requestData[0]['name']);
        $user->setLastname($requestData[0]['lastName']);
        if(isset($requestData[0]['username']))
            $user->setUsername($requestData[0]['username']);
        if(isset($requestData[0]['email']))
            $user->setEmail($requestData[0]['email']);
        if(isset($requestData[0]['steps']))
            $user->setSteps(0);
        if(isset($requestData[0]['dateOfBirth']))
            $user->setDateOfBirth($requestData[0]['dateOfBirth']);
        $customer = new \User\Entity\Customer;
        $customer->setName($requestData[0]['name'].' '.($requestData[0]['lastName']));
        $this->em->persist($customer);
        $this->em->flush();
        $s = $this->em->getRepository('User\Entity\Customer')->findOneBy(array('name' => $requestData[0]['name'].' '.($requestData[0]['lastName'])));
        $user->setCustomer($s);
        $user->setPassword($authHelper->hash($requestData[0]['password'], null, true));

        $user->setLanguageId($requestData[0]['language_id']);
        
        return $user;
    }

    function ImageResize($width, $path)//$imgString,
    {
        /* Get original file size */

        list($w, $h) =  getimagesize( $path);// getimagesizefromstring($imgString);

        /* Calculate new image size */
        $ratio = $w/ $h;

        $height = ceil($width/ $ratio);
        $x = ($w - $width / $ratio) / 2;
        //$w = ceil($width / $ratio);

        /* Save image */
        /* Get binary data from image */
        /* create image from string */
        try {
            $image = imagecreatefromjpeg($path); //imagecreatefromstring($imgString);

            $tmp = imagecreatetruecolor($width, $height);//  imagecreatetruecolor($width, $height);
            imagecopyresampled($tmp, $image, 0, 0, 0, 0, $width, $height, $w, $h);
            imagejpeg($tmp, $path, 100);
        }catch(Exception $e ){
            die('erro aki'.$e->getMessage());
        }
        //file_put_contents($path,$tmp);

        return true;
        imagedestroy($image);
        imagedestroy($tmp);
    }

    private function _uploadImage($encodedImage,$userId)
    {
        $img = str_replace('data:image/png;base64,', '', $encodedImage);
        $img = str_replace('data:image/jpeg;base64,', '', $encodedImage);
        $img = str_replace(' ', '+', $img);
        $data = base64_decode($img);


        $absolutePath = PUBLIC_PATH .
            DIRECTORY_SEPARATOR .
            "uploads"
            . DIRECTORY_SEPARATOR
            . "User" . DIRECTORY_SEPARATOR . "$userId" . DIRECTORY_SEPARATOR;
        if (!file_exists($absolutePath))
            mkdir($absolutePath, 0777, true);
        $fileName = str_pad(rand(0, 9999999999), 10, '0', STR_PAD_LEFT) . '.jpg';
        $absolutePath .= $fileName;

        file_put_contents($absolutePath, $data);
        $this->ImageResize(250,$absolutePath);

        return "ws/uploads/User/$userId/$fileName";
    }

    public function doRegistration($data, $em=null)
    {
            if (is_null($em)) {
                $em = $this->getEntityManager();
                $this->em = $em;
            } else {
                $this->setEntityManager($em);
            }
            if(isset($data[0]['email'])){
                $aux = $this->em->getRepository('User\Entity\User')->findOneBy(array('email' => $data[0]['email']));
                if($aux !=null){
                    $this->getResponse()->setStatusCode(404);
                    return array("error" => "Email already exists");
                }
            }
            $user = $this->_fillUser($data);
            $userInvitationRepository = $em->getRepository('User\Entity\UserInvitation');
            if (isset($data[0]['registrationCode']))
                $userInvitation = $userInvitationRepository->findOneBy(array('registration_code' => $data[0]['registrationCode']));
            $new = false;
            if (!empty($userInvitation)) {
                $new = true;
                $user->setCustomer($userInvitation->getCustomer());
                //return array("error" => "Invalid registration_code");
            }
            $user->setProfileUrl('');
            $user->setTimezone(null);
            $country = $em->find('Listing\Entity\Country', '1');
            $user->setCountry($country);

            if ($user->isValid($new)) {
                $aux = $em->getRepository('User\Entity\RoleUser')->findBy(array('user' => $user->getId()));
                if (!$aux) {
                    $roleUser = new \User\Entity\RoleUser();
                    $roleRepository = $em->getRepository('User\Entity\Role');
                    if (!empty($userInvitation)) {
                        $em->persist($user);
                        $em->getConnection()->exec("update user_invitations set status='1' where email = '" . $userInvitation->getEmail() . "'");
                        $em->flush();
                        $role = $roleRepository->find($userInvitation->getRole()->getId());
                    } else {
                        $role = $roleRepository->find(1);
                    }
                    $roleUser->setRole($role);
                    $roleUser->setUser($user);
                    $em->persist($roleUser);
                }
                if (@$data[0]['image']) {
                    @$path = $this->_uploadImage($data[0][image], $user->getId());
                    $user->setRelativePath($path);
                }
                $em->persist($user);
                $em->flush();
                if ($this->alert == 1) {
                    $this->send($user->getEmail(), $user->getLanguage()->getId());
                }
                $return = $user->getExpectedArray();
            } else {
                $this->getResponse()->setStatusCode(404);
                $return = $user->getErrorMessages();
            }

            return $return;
    }

    public function send($email,$language)
    {
        if($language == 1)
            $language = "pt-br";
        else if($language == 2)
            $language = "es";
        else if($language == 3)
            $language = "en";
        $href = 'http://' . $_SERVER['SERVER_NAME'] . '/#activation/' . $email.'/'.$language;
        if($language = "en"){
            $message = '
                        <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                        <html xmlns="http://www.w3.org/1999/xhtml">
                            <head>
                                  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                                <title>Social Platform Invite</title>
                            </head>
                            <body>
                                Welcome to Content Analytis App. Click <a href=' . $href . '>here</a>
                                to activate your account and complete the registration process.

                            </body>
                        </html>
            ';
        }
        else if($language = "es"){
            $message = '
                        <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                        <html xmlns="http://www.w3.org/1999/xhtml">
                            <head>
                                  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                                <title>Social Platform Invite</title>
                            </head>
                            <body>
                                Bienvenido a Content Analytis App. Hacer clic <a href=' . $href . '>aquí</a>
                                para activar su cuenta y completar el proceso de registro.

                            </body>
                        </html>
            ';
        }
        else {
            $message = '
                        <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                        <html xmlns="http://www.w3.org/1999/xhtml">
                            <head>
                                  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                                <title>Social Platform Invite</title>
                            </head>
                            <body>
                                Bem-vindo a Content Analytis App. Clique <a href=' . $href . '>aqui</a>
                                para ativar sua conta e completar o processo de registro.

                            </body>
                        </html>
            ';
        }

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
            'to'        => $email,
            'subject'   => 'Social Platform Activation',
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
}