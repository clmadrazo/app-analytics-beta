<?php
namespace User;

return array(
    'controllers' => array(
        'invokables' => array(
            'User\Controller\UserGet'               => 'User\Controller\UserGetController',
            'User\Controller\UserUpdate'            => 'User\Controller\UserUpdateController',
            'User\Controller\UserRoleUpdate'        => 'User\Controller\UserRoleUpdateController',
            'User\Controller\UserSkillGet'          => 'User\Controller\UserSkillGetController',
            'User\Controller\UserSendInvitations'   => 'User\Controller\UserSendInvitations',
            'User\Controller\ListUser'    => 'User\Controller\ListUserController',
            'Profile\Controller\GetUser' => 'Profile\Controller\GetUserController',
            'User\Controller\InactivateUser'    => 'User\Controller\InactivateUserController',
            'User\Controller\DeleteUser'    => 'User\Controller\DeleteUserController',
        ),
    ),
    'controller_plugins' => array(
        'invokables' => array(
            'QueryPaginator' => 'App\Mvc\Controller\Plugin\Doctrine\QueryPaginator',
        )
    ),
    'router' => array(
        'routes' => array(
            'deleteUser' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/inactivateuser[/]',
                    'defaults' => array(
                        'controller' => 'User\Controller\DeleteUser',
                        'action' => 'index',
                    ),
                ),
            ),
            'deleteAccessToken' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/access-token/remove[/]',
                    'defaults' => array(
                        'controller' => 'User\Controller\DeleteUser',
                        'action' => 'removeAccessToken',
                    ),
                ),
            ),
            'inactivateUser' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/inactivateuser[/]',
                    'defaults' => array(
                        'controller' => 'User\Controller\InactivateUser',
                        'action' => 'index',
                    ),
                ),
            ),
            'activateUser' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/activateUser[/]',
                    'defaults' => array(
                        'controller' => 'User\Controller\UserUpdate',
                        'action' => 'activeUser',
                    ),
                ),
            ),
            'updateUser' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/userupdate/:userId[/]',
                    'defaults' => array(
                        'controller' => 'User\Controller\UserRoleUpdate',
                        'action' => 'index',
                    ),
                ),
            ),
            'userUpdate' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/update/user[/]',
                    'defaults' => array(
                        'controller' => 'User\Controller\UserUpdate',
                        'action' => 'updateUser',
                    ),
                ),
            ),
            'getUser' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/user/:userId[/]',
                    'defaults' => array(
                        'controller' => 'Profile\Controller\GetUser',
                        'action' => 'index',
                    ),
                ),
            ),
            'getUserInvitation' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/userInvitation/:userId[/]',
                    'defaults' => array(
                        'controller' => 'Profile\Controller\GetUser',
                        'action' => 'userInvitation',
                    ),
                ),
            ),
            'listUser' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/user/list[/]',
                    'defaults' => array(
                        'controller' => 'User\Controller\ListUser',
                        'action' => 'index',
                    ),
                ),
            ),
            'search-editor' => array(
                'type'    => 'literal',
                'options' => array(
                    'route'    => '/user/search/',
                    'defaults' => array(
                        'controller' => 'User\Controller\UserGet',
                        'action' => 'search',
                    ),
                ),
            ),
            'userSendInvitations' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/user/send-invitations[/]',
                    'defaults' => array(
                        'controller' => 'User\Controller\UserSendInvitations',
                        'action' => 'index',
                    ),
                ),
            ),
            'search-skill' => array(
                'type'    => 'literal',
                'options' => array(
                    'route'    => '/skill/search/',
                    'defaults' => array(
                        'controller' => 'User\Controller\UserSkillGet',
                        'action' => 'search',
                    ),
                ),
            ),
            'user' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/user/:userId',
                    'constraints' => array(
                        'publisherId' => '[0-9]+',
                    ),
                ),
                'may_terminate' => false,
                'child_routes' => array(
                    'articleNotifications' => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route' => '/articleNotifications[/]',
                        ),
                        'may_terminate' => false,
                        'child_routes' => array(
                            'update' => array(
                                'type'    => 'method',
                                'options' => array(
                                    'verb'    => 'put',
                                    'defaults' => array(
                                        'controller' => 'User\Controller\UserUpdate',
                                        'action' => 'updateArticleNotifications',
                                    ),
                                ),
                            ),
                            'get' => array(
                                'type'    => 'method',
                                'options' => array(
                                    'verb'    => 'get',
                                    'defaults' => array(
                                        'controller' => 'User\Controller\UserGet',
                                        'action' => 'getArticleNotifications',
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
            ),
            'orm_default' => array(
                    'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                )
            )
        )
    ),
);