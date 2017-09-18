<?php
namespace Authentication\Controller;

use App\Mvc\Controller\RestfulController;
use Authentication\Model\UserCredentials;
use Zend\View\Model\JsonModel;
use Authentication\Model\Email;
use User\Entity\RoleUser;
use User\Entity\Role;


/**
 * This controller handles all authentication requests. In this first iteration
 * it only supports a very simple implementation but the idea is to, later on,
 * move forward into using a more secure and reliable mechanism like OAuth 2.0.
 *
 * @todo This endpoints must implementing Transaction IDs (see Listing services)
 */
class AuthenticationController extends RestfulController
{
    protected $_allowedMethod = "post";

    const ERR_COULDNT_RETRIEVE_USER_INFO = "Couldn't retrieve user info, please check the token";

    /**
     * @example
     *  [Request]
     *      POST /authentication/login
     *      Content-Type: application/json
     *      Accept: application/json
     *      {
     *          "username": "some-username",
     *          "password": "some-password"
     *      }
     *
     * @return \Zend\Http\Message\Response
     */
    public function loginAction()
    {
        $request = $this->getRequest();
        $requestData = $this->processBodyContent($request);

        $return  = $this->doLogin($requestData);

        return $return;
    }


    public function doLogin($data, $isSocial=false)
    {
        $return = null;
        $response = $this->getResponse();
        $userCredentials = new UserCredentials();
        $userCredentials->exchangeArray($data);

        // Filter & validate credentials (fail early).
        if ($userCredentials->isValid()) {
            $authHelper = $this->getServiceLocator()->get('AuthenticationHelper');
            if (!$isSocial) {
                // Authenticate user credentials only if it's a login service (not social)
                $userId = $authHelper->authenticate($userCredentials);
            } else {
                $userId = $data[0]['userId'];
            }

            if ($userId) {
                $headers = $response->getHeaders();
                $profileHelper = $this->getServiceLocator()->get('ProfileHelper');
                $user = $profileHelper->getUser($userId);
                $headers->addHeaderLine('X-User-Id', $userId);

                $headers->addHeaderLine('User-Name', htmlentities( $user->getName().' '.$user->getLastname()));
                $headers->addHeaderLine('User-Email', $user->getEmail());
                $headers->addHeaderLine('image',$user->getAbsolutePath());
                $langCode = $user->getLanguage()->getCode();
                $code = ($langCode=="")?"pt-br":$langCode;
                $headers->addHeaderLine('languageCode',$code);
                $userRoles=$user->getRoles();
                $userRolesString='';
                $i=0;
                $tok = $profileHelper->generateToken($user);
                foreach ($userRoles as $userRole){
                    if ($i==0) $userRolesString=$userRole->getId();
                    else $userRolesString.=(','.$userRole->getId());
                    $i++;
                }
                $headers->addHeaderLine('User-Roles', $userRolesString);

                $headers->addHeaderLine('Bearer-Token', $tok['token']);
                $headers->addHeaderLine('Refresh-Token', $tok['refresh']);
                $return = $response->setStatusCode(200);
            } else {
                $return = $response->setStatusCode(404);
            }
        } else {
            $response->setStatusCode(400);
            $errorMessages = array(
                'errors' => $userCredentials->getErrorMessages(),
            );
            $return = new JsonModel($errorMessages);
        }

        return $return;
    }

    public function refreshTokenAction()
    {
        $response = $this->getResponse();
        $requestData = $this->processBodyContent($this->getRequest());
        $em = $this->getEntityManager();

        $token = $em->getRepository('User\Entity\AccessToken')
            ->findOneBy(array('refresh' => $requestData[0]['refreshToken']));

        if (empty($token)) {
            $response->setStatusCode(400);
            $return = new JsonModel(array('errors' => parent::PROCESS_REQUEST_ERROR));
        } else {
            $value = bin2hex(openssl_random_pseudo_bytes(16));
            $refresh = bin2hex(openssl_random_pseudo_bytes(16));
            $token->setValue($value);
            $token->setRefresh($refresh);
            $token->setCreated();
            $this->getEntityManager()->persist($token);
            $this->getEntityManager()->flush();
            $headers = $response->getHeaders();
            $headers->addHeaderLine('X-User-Id', $token->getUser()->getId());
            $headers->addHeaderLine('User-Email', $token->getUser()->getEmail());
            $headers->addHeaderLine('Bearer-Token', $value);
            $headers->addHeaderLine('Refresh-Token', $refresh);
            $headers->addHeaderLine('languageCode',$token->getUser()->getLanguage()->getCode());

            $return = $response->setStatusCode(200);
        }

        return $return;
    }

    /**
     * @example
     *  [Request]
     *      POST /authentication/forgot-password
     *      Content-Type: application/json
     *      {
     *          "email": "some-email"
     *      }
     */
    public function forgotPasswordAction()
    {
        $em = $this->getEntityManager();
        $requestData = $this->processBodyContent($this->getRequest());

        $profileHelper = $this->getServiceLocator()->get('ProfileHelper');
        $user = $profileHelper->getUserByEmail($requestData[0]['email']);

        if (!empty($user)) {
            $authHelper = $this->getServiceLocator()->get('AuthenticationHelper');
            $resetCode = $authHelper->generateResetPasswordCode($requestData[0]['email']);
            $user->setResetPasswordCode($resetCode);
            $expiration = new \DateTime('now + 15 minutes');
            $user->setResetPasswordCodeExpiration($expiration);
            $em->persist($user);
            $em->flush();
            $return = array("resetCode" => $resetCode);
        } else {
            $this->getResponse()->setStatusCode(404);
            $return = array("errors" => "Email not registered");
        }

        return new JsonModel(array("result" => $return));
    }

    /**
     * @example
     *  [Request]
     *      POST /authentication/validate-reset-password
     *      Content-Type: application/json
     *      {
     *          "email": "some-email",
     *          "resetCode": "some-code"
     *      }
     */
    public function validateResetCodeAction()
    {
        $response = $this->getResponse();
        $requestData = $this->processBodyContent($this->getRequest());

        $authHelper = $this->getServiceLocator()->get('AuthenticationHelper');
        if ($authHelper->isValidResetCode($requestData[0]['email'], $requestData[0]['resetCode'])) {
            $response->setStatusCode(200);
        } else {
            $response->setStatusCode(404);
        }

        return $response;
    }

    /**
     * @example
     *  [Request]
     *      POST /authentication/set-password
     *      Content-Type: application/json
     *      Accept: application/json
     *      {
     *          "email": "some-email",
     *          "password": "password"
     *      }
     *
     * @return \Zend\Http\Message\Response
     */
    public function changePasswordAction()
    {
        $response = $this->getResponse();
        $requestData = $this->processBodyContent($this->getRequest());
        $authHelper = $this->getServiceLocator()->get('AuthenticationHelper');

        $userEntity = new UserCredentials();
        $userEntity->exchangeArray($requestData);
        if ($userEntity->isValid()) {
                $email = $userEntity->getEmail();
                $password = $userEntity->getPassword();
                if ($authHelper->setPassword($email, $password)) {
                    $response->setStatusCode(200);
                }
        } else {
            $response->setStatusCode(400);
        }

        return $response;
    }
}