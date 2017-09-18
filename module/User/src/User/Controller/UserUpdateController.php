<?php

namespace User\Controller;

use App\Mvc\Controller\RestfulController;
use User\Entity\User;
use Doctrine\ORM\EntityNotFoundException;
use Zend\View\Model\JsonModel;

/**
 * 
 */
class UserUpdateController extends RestfulController {

    protected $_allowedMethod = 'put';
    /**
     * @link http://www.yami-ec.com.ar/wiki/index.php?title=User_Update_Article_Notifications Service API documentation
     * @return Zend\View\Model\JsonModel
     */
    public function updateArticleNotificationsAction()
    {
        try {

            $requestData = $this->processBodyContent($this->getRequest());
            $userId = $this->getEvent()->getRouteMatch()->getParam('userId');
            
            /* @var $userWorkFlow \User\Model\Workflow\UserWorkflow */
            $userWorkFlow = $this->getServiceLocator()->get('UserWorkflow');
            $user = $userWorkFlow->persistUser(
                ['newArticleNotificationsInRandom' => !!$requestData['articleNotifications']], 
                $userId
            );

            $result = [
                'userId' => $user->getId(),
                'articleNotifications' => $user->getNewArticleNotificationsInRandom(),
            ];
            
            $this->getResponse()->setStatusCode(200);
            $this->getResponse()->getHeaders()->addHeaders(
                [
                    'Location' => $this->url()->fromRoute('user/articleNotifications/get', ['userId' => $user->getId()])
                ]
            );

        } catch (\Exception $exc) {

            $this->getResponse()->setStatusCode(500);
            if ($exc instanceof EntityNotFoundException) {
                $this->getResponse()->setStatusCode(404);
            }

            $result = [
                'error' => 'There was an error while processing the request',
            ];
            if (in_array(APPLICATION_ENV, [APPLICATION_ENV_DEV, APPLICATION_ENV_TESTING])) {
                $result = array_merge(
                    $result,
                    [
                        'exception' => [
                            'code' => $exc->getCode(),
                            'message' => $exc->getMessage(),
                            'stackTrace' => $exc->getTraceAsString(),
                        ]
                    ]
                );
            }
        }

        return new JsonModel(
            $result
        );
    }

    public function updateUserAction()
    {
        $em = $this->getEntityManager();
        $request = $this->getRequest();
        $requestData = $this->processBodyContent($request);
        $userId = $requestData[0]['userId'];
        $postRepository = $em->getRepository('User\Entity\User');
        $post = $postRepository->find($userId);

        if (!empty($post)) {
            if(isset($requestData[0]['steps']))
                $post->setSteps($requestData[0]['steps']);

            $em->persist($post);
            $em->flush();

            $this->getResponse()->setStatusCode(200);
            $return = array($post->getExpectedArray());

        }
        else {
            $this->getResponse()->setStatusCode(404);
            $return = array("errors" => \User\Entity\User::ERR_USER_NOT_FOUND);
        }

        return new JsonModel(array("result" => $return));
    }

    public function activeUserAction()
    {
        $em = $this->getEntityManager();
        $request = $this->getRequest();
        $requestData = $this->processBodyContent($request);
        $email = $requestData[0]['email'];
        $userRepository = $em->getRepository('User\Entity\User');
        $user = $userRepository->findOneBy(array('email' => $email));

        if (!empty($user)) {
            $user->setStatus(\User\Entity\User::STATUS_ACTIVE);

            $em->persist($user);
            $em->flush();

            $this->getResponse()->setStatusCode(200);
            $return = array($user->getExpectedArray());

        }
        else {
            $this->getResponse()->setStatusCode(404);
            $return = array("errors" => \User\Entity\User::ERR_USER_NOT_FOUND);
        }

        return new JsonModel(array("result" => $return));
    }
}
