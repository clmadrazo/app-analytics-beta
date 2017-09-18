<?php
namespace BlogPost;

return array(
    'controllers' => array(
        'invokables' => array(
            'PayPal\Controller\GetVerifiedStatus' => 'PayPal\Controller\GetVerifiedStatusController',
        ),
    ),
    'service_manager' => array(
        'invokables' => array(

        ),
    ),
    'router' => array(
        'routes' => array(
            'getVerifiedStatus' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/paypal/getVerifiedStatus[/]',
                    'defaults' => array(
                        'controller' => 'PayPal\Controller\GetVerifiedStatus',
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