<?php
namespace Registration;

return array(
    'controllers' => array(
        'invokables' => array(
            'Registration\Controller\Registration' => 'Registration\Controller\RegistrationController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'registration' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/registration[/]',
                    'defaults' => array(
                        'controller' => 'Registration\Controller\Registration',
                        'action' => 'index',
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
