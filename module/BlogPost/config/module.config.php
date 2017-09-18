<?php
namespace BlogPost;

return array(
    'controllers' => array(
        'invokables' => array(
            'BlogPost\Controller\AddBlogPost' => 'BlogPost\Controller\AddBlogPostController',
            'BlogPost\Controller\GetBlogPost' => 'BlogPost\Controller\GetBlogPostController',
            'BlogPost\Controller\UpdateBlogPost' => 'BlogPost\Controller\UpdateBlogPostController',
            'BlogPost\Controller\RemoveBlogPost' => 'BlogPost\Controller\RemoveBlogPostController',
            'BlogPost\Controller\SearchBlogPost' => 'BlogPost\Controller\SearchBlogPostController',
            'BlogPost\Controller\GetAllBlogPost' => 'BlogPost\Controller\GetAllBlogPostController',
            'BlogPost\Controller\TopAuthorBlogPost' => 'BlogPost\Controller\TopAuthorBlogPostController',
            'BlogPost\Controller\TopBlogPost' => 'BlogPost\Controller\TopBlogPostController',
            'BlogPost\Controller\TopTopicBlogPost' => 'BlogPost\Controller\TopTopicBlogPostController',
            'BlogPost\Controller\AnalyticsBlogPost' => 'BlogPost\Controller\AnalyticsBlogPostController',
            'BlogPost\Controller\CreateTableBlogPost' => 'BlogPost\Controller\CreateTableBlogPostController',
        ),
    ),
    'service_manager' => array(
        'invokables' => array(

        ),
    ),
    'router' => array(
        'routes' => array(
            'addBlogPost' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/blog-post/add[/]',
                    'defaults' => array(
                        'controller' => 'BlogPost\Controller\AddBlogPost',
                        'action' => 'index'
                    ),
                ),
            ),
            'getBlogPost' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/blog-post/get/:userId/:postId[/]',
                    'defaults' => array(
                        'controller' => 'BlogPost\Controller\GetBlogPost',
                        'action' => 'index',
                    ),
                ),
            ),
            'updateBlogPost' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/blog-post/update/:userId/:id[/]',
                    'defaults' => array(
                        'controller' => 'BlogPost\Controller\UpdateBlogPost',
                        'action' => 'index',
                    ),
                ),
            ),
            'activeBlogPost' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/blog-post/update/active[/]',
                    'defaults' => array(
                        'controller' => 'BlogPost\Controller\UpdateBlogPost',
                        'action' => 'activeBlogPost',
                    ),
                ),
            ),
            'removeBlogPost' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/blog-post/remove/:key_api[/]',
                    'defaults' => array(
                        'controller' => 'BlogPost\Controller\RemoveBlogPost',
                        'action' => 'index',
                    ),
                ),
            ),
            'searchBlogPost' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/blog-post/search[/]',
                    'defaults' => array(
                        'controller' => 'BlogPost\Controller\SearchBlogPost',
                        'action' => 'index',
                    ),
                ),
            ),
            'getallBlogPost' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/blog-post/get/:userId[/]',
                    'defaults' => array(
                        'controller' => 'BlogPost\Controller\GetAllBlogPost',
                        'action' => 'index',
                    ),
                ),
            ),
            'createTableBlogPost' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/blog-post/create[/]',
                    'defaults' => array(
                        'controller' => 'BlogPost\Controller\CreateTableBlogPost',
                        'action' => 'index',
                    ),
                ),
            ),
            'topAuthorBlogPost' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/blog-post/top-author[/]',
                    'defaults' => array(
                        'controller' => 'BlogPost\Controller\TopAuthorBlogPost',
                        'action' => 'index',
                    ),
                ),
            ),
            'topBlogPost' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/blog-post/top-post[/]',
                    'defaults' => array(
                        'controller' => 'BlogPost\Controller\TopBlogPost',
                        'action' => 'index',
                    ),
                ),
            ),
            'topTopicBlogPost' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/blog-post/top-topic[/]',
                    'defaults' => array(
                        'controller' => 'BlogPost\Controller\TopTopicBlogPost',
                        'action' => 'index',
                    ),
                ),
            ),
            'analyticsBlogPost' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/blog-post/analytics[/]',
                    'defaults' => array(
                        'controller' => 'BlogPost\Controller\AnalyticsBlogPost',
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