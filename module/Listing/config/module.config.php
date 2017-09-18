<?php
namespace Listing;

return array(
    'controllers' => array(
        'invokables' => array(
            'Listing\Controller\Country' => 'Listing\Controller\CountryController',
            'Listing\Controller\Language' => 'Listing\Controller\LanguageController',
            'Listing\Controller\LanguageRegion' => 'Listing\Controller\LanguageRegionController',
            'Listing\Controller\Editor' => 'Listing\Controller\EditorController',
            'Listing\Controller\Writer' => 'Listing\Controller\WriterController',
            'Listing\Controller\Auditor' => 'Listing\Controller\AuditorController',
            'Listing\Controller\Role' => 'Listing\Controller\RoleController',
            'Listing\Controller\Media' => 'Listing\Controller\MediaController',
            'Listing\Controller\SuscriptionType' => 'Listing\Controller\SuscriptionTypeController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'listCountry' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/list/country[/]',
                    'defaults' => array(
                        'controller' => 'Listing\Controller\Country'
                    ),
                ),
            ),
            'listLanguage' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/list/language[/]',
                    'defaults' => array(
                        'controller' => 'Listing\Controller\Language'
                    ),
                ),
            ),
            'listEditor' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/list/editor[/]',
                    'defaults' => array(
                        'controller' => 'Listing\Controller\Editor'
                    ),
                ),
            ),
            'listWriter' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/list/writer[/]',
                    'defaults' => array(
                        'controller' => 'Listing\Controller\Writer'
                    ),
                ),
            ),
            'listAuditor' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/list/auditor[/]',
                    'defaults' => array(
                        'controller' => 'Listing\Controller\Auditor'
                    ),
                ),
            ),
            'listRole' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/list/role[/]',
                    'defaults' => array(
                        'controller' => 'Listing\Controller\Role'
                    ),
                ),
            ),
            'listSuscriptionType' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/list/suscription_type[/]',
                    'defaults' => array(
                        'controller' => 'Listing\Controller\SuscriptionType'
                    ),
                ),
            ),
            'listMedia' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/list/media[/]',
                    'defaults' => array(
                        'controller' => 'Listing\Controller\Media'
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
