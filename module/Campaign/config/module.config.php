<?php
namespace Campaign;

return array(
    'controllers' => array(
        'invokables' => array(
            'Campaign\Controller\AddCampaign' => 'Campaign\Controller\AddCampaignController',
            'Campaign\Controller\SetCampaignDeadlines' => 'Campaign\Controller\SetCampaignDeadlinesController',
            'Campaign\Controller\ListCampaign' => 'Campaign\Controller\ListCampaignController',
            'Campaign\Controller\UpdateCampaign' => 'Campaign\Controller\UpdateCampaignController',
            'Campaign\Controller\DeleteCampaign' => 'Campaign\Controller\DeleteCampaignController',
            'Campaign\Controller\GetCampaignInformation' => 'Campaign\Controller\GetCampaignInformationController',

        ),
    ),
    'router' => array(
        'routes' => array(
            'addCampaign' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/campaign/add[/]',
                    'defaults' => array(
                        'controller' => 'Campaign\Controller\AddCampaign',
                        'action' => 'index'
                    ),
                ),
            ),
            'setCampaignDeadlines' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/campaign/deadlines[/]',
                    'defaults' => array(
                        'controller' => 'Campaign\Controller\SetCampaignDeadlines',
                        'action' => 'index'
                    ),
                ),
            ),
            'listCampaign' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/campaign/list[/]',
                    'defaults' => array(
                        'controller' => 'Campaign\Controller\ListCampaign',
                        'action' => 'index'
                    ),
                ),
            ),
            'updateCampaign' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/campaign/update[/]',
                    'defaults' => array(
                        'controller' => 'Campaign\Controller\UpdateCampaign',
                        'action' => 'index'
                    ),
                ),
            ),
            'deleteCampaign' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/campaign/delete[/]',
                    'defaults' => array(
                        'controller' => 'Campaign\Controller\DeleteCampaign',
                        'action' => 'index'
                    ),
                ),
            ),


            'getCampaignInformation' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/campaign/getinformation[/]',
                    'defaults' => array(
                        'controller' => 'Campaign\Controller\GetCampaignInformation',
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