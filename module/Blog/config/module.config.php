<?php
namespace Blog;

return array(
    'controllers' => array(
        'invokables' => array(
            'Blog\Controller\AddBlog' => 'Blog\Controller\AddBlogController',
            'Blog\Controller\GetAllBlog' => 'Blog\Controller\GetAllBlogController',
            'Blog\Controller\GetBlog' => 'Blog\Controller\GetBlogController',
            'Blog\Controller\RemoveBlog' => 'Blog\Controller\RemoveBlogController',
            'Blog\Controller\UpdateBlog' => 'Blog\Controller\UpdateBlogController',
            'Blog\Controller\SearchBlogByUser' => 'Blog\Controller\SearchBlogByUserController',
        ),
    ),
    'service_manager' => array(
        'invokables' => array(

        ),
    ),
    'router' => array(
        'routes' => array(
            'addBlog' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/blog/add[/]',
                    'defaults' => array(
                        'controller' => 'Blog\Controller\AddBlog',
                        'action' => 'index'
                    ),
                ),
            ),
            'getBlog' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/blog/get/:id[/]',
                    'defaults' => array(
                        'controller' => 'Blog\Controller\GetBlog',
                        'action' => 'index',
                    ),
                ),
            ),
            'searchBlogByUser' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/blog/search/:id_user[/]',
                    'defaults' => array(
                        'controller' => 'Blog\Controller\SearchBlogByUser',
                        'action' => 'index',
                    ),
                ),
            ),
            'getAllBlog' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/blog/get[/]',
                    'defaults' => array(
                        'controller' => 'Blog\Controller\GetAllBlog',
                        'action' => 'index',
                    ),
                ),
            ),
            'updateBlog' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/blog/update/:id[/]',
                    'defaults' => array(
                        'controller' => 'Blog\Controller\UpdateBlog',
                        'action' => 'index',
                    ),
                ),
            ),
            'removeBlog' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/blog/remove/:id[/]',
                    'defaults' => array(
                        'controller' => 'Blog\Controller\RemoveBlog',
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