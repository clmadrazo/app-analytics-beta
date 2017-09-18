<?php
namespace Post;

return array(
    'controllers' => array(
        'invokables' => array(
            'Docs\Controller\Docs' => 'Docs\Controller\DocsController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'showDoc' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/docs[/]',
                    'defaults' => array(
                        'controller' => 'Docs\Controller\Docs',
                        'action' => 'index',
                    ),
                ),
            ),
            'showDocBlogPost' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/docs/blog-post[/]',
                    'defaults' => array(
                        'controller' => 'Docs\Controller\Docs',
                        'action' => 'blogpost',
                    ),
                ),
            ),
            'showDocBlog' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/docs/blog[/]',
                    'defaults' => array(
                        'controller' => 'Docs\Controller\Docs',
                        'action' => 'blog',
                    ),
                ),
            ),
            'showDocBlogPostAdd' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/docs/blog-post/add[/]',
                    'defaults' => array(
                        'controller' => 'Docs\Controller\Docs',
                        'action' => 'blogpostAdd',
                    ),
                ),
            ),
            'showDocBlogPostCreateTable' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/docs/blog-post/create[/]',
                    'defaults' => array(
                        'controller' => 'Docs\Controller\Docs',
                        'action' => 'blogpostCreateTable',
                    ),
                ),
            ),
            'showDocBlogAdd' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/docs/blog/add[/]',
                    'defaults' => array(
                        'controller' => 'Docs\Controller\Docs',
                        'action' => 'blogAdd',
                    ),
                ),
            ),
            'showDocBlogPostTopAuthor' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/docs/blog-post/top-author[/]',
                    'defaults' => array(
                        'controller' => 'Docs\Controller\Docs',
                        'action' => 'blogpostTopAuthor',
                    ),
                ),
            ),
            'showDocBlogPostTopTopic' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/docs/blog-post/top-topic[/]',
                    'defaults' => array(
                        'controller' => 'Docs\Controller\Docs',
                        'action' => 'blogpostTopTopic',
                    ),
                ),
            ),
            'showDocBlogPostTop' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/docs/blog-post/top-post[/]',
                    'defaults' => array(
                        'controller' => 'Docs\Controller\Docs',
                        'action' => 'blogpostTop',
                    ),
                ),
            ),
            'showDocBlogPostUpdate' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/docs/blog-post/update/[:id][/]',
                    'defaults' => array(
                        'controller' => 'Docs\Controller\Docs',
                        'action' => 'blogpostUpdate',
                    ),
                ),
            ),
            'showDocBlogPostActive' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/docs/blog-post/update/active[/]',
                    'defaults' => array(
                        'controller' => 'Docs\Controller\Docs',
                        'action' => 'blogpostActive',
                    ),
                ),
            ),
            'showDocBlogUpdate' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/docs/blog/update/[:id][/]',
                    'defaults' => array(
                        'controller' => 'Docs\Controller\Docs',
                        'action' => 'blogUpdate',
                    ),
                ),
            ),
            'showDocBlogPostRemove' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/docs/blog-post/remove/[:id][/]',
                    'defaults' => array(
                        'controller' => 'Docs\Controller\Docs',
                        'action' => 'blogpostRemove',
                    ),
                ),
            ),
            'showDocCheckAuth' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/docs/authentication/checkAuth[/]',
                    'defaults' => array(
                        'controller' => 'Docs\Controller\Docs',
                        'action' => 'authenticationCheckAuth',
                    ),
                ),
            ),
            'showDocBlogRemove' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/docs/blog/remove/[:id][/]',
                    'defaults' => array(
                        'controller' => 'Docs\Controller\Docs',
                        'action' => 'blogRemove',
                    ),
                ),
            ),
            'showDocBlogPostSearch' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/docs/blog-post/search[/]',
                    'defaults' => array(
                        'controller' => 'Docs\Controller\Docs',
                        'action' => 'blogpostSearch',
                    ),
                ),
            ),
            'showDocBlogSearchByUser' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/docs/blog/search/[:is_user][/]',
                    'defaults' => array(
                        'controller' => 'Docs\Controller\Docs',
                        'action' => 'blogSearchByUser',
                    ),
                ),
            ),
            'showDocBlogPostAnalytics' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/docs/blog-post/analytics[/]',
                    'defaults' => array(
                        'controller' => 'Docs\Controller\Docs',
                        'action' => 'blogpostAnalytics',
                    ),
                ),
            ),
            'showDocBlogPostGet' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/docs/blog-post/get/[:id][/]',
                    'defaults' => array(
                        'controller' => 'Docs\Controller\Docs',
                        'action' => 'blogpostGet',
                    ),
                ),
            ),
            'showDocBlogGet' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/docs/blog/get/[:id][/]',
                    'defaults' => array(
                        'controller' => 'Docs\Controller\Docs',
                        'action' => 'blogGet',
                    ),
                ),
            ),
            'showDocAuthentication' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/docs/authentication[/]',
                    'defaults' => array(
                        'controller' => 'Docs\Controller\Docs',
                        'action' => 'authentication',
                    ),
                ),
            ),
            'showDocRegistration' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/docs/registration[/]',
                    'defaults' => array(
                        'controller' => 'Docs\Controller\Docs',
                        'action' => 'registration',
                    ),
                ),
            ),
            'showDocRegistrationDo' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/docs/registration/do[/]',
                    'defaults' => array(
                        'controller' => 'Docs\Controller\Docs',
                        'action' => 'registrationDo',
                    ),
                ),
            ),
            'showDocUser' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/docs/user[/]',
                    'defaults' => array(
                        'controller' => 'Docs\Controller\Docs',
                        'action' => 'user',
                    ),
                ),
            ),
            'showDocUserSendInvitation' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/docs/user/send-invitation[/]',
                    'defaults' => array(
                        'controller' => 'Docs\Controller\Docs',
                        'action' => 'userSendInvitation',
                    ),
                ),
            ),
            'showDocAuthenticationLogin' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/docs/authentication/login[/]',
                    'defaults' => array(
                        'controller' => 'Docs\Controller\Docs',
                        'action' => 'authenticationLogin',
                    ),
                ),
            ),
            'showDocAuthenticationRefreshToken' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/docs/authentication/refresh-token[/]',
                    'defaults' => array(
                        'controller' => 'Docs\Controller\Docs',
                        'action' => 'authenticationRefreshToken',
                    ),
                ),
            ),
            'showDocListing' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/docs/listing[/]',
                    'defaults' => array(
                        'controller' => 'Docs\Controller\Docs',
                        'action' => 'listing',
                    ),
                ),
            ),
            'showDocListingCountry' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/docs/listing/country[/]',
                    'defaults' => array(
                        'controller' => 'Docs\Controller\Docs',
                        'action' => 'listingCountry',
                    ),
                ),
            ),
            'showDocListingLanguage' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/docs/listing/language[/]',
                    'defaults' => array(
                        'controller' => 'Docs\Controller\Docs',
                        'action' => 'listingLanguage',
                    ),
                ),
            ),
            'showDocListingAuditor' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/docs/listing/auditor[/]',
                    'defaults' => array(
                        'controller' => 'Docs\Controller\Docs',
                        'action' => 'listingAuditor',
                    ),
                ),
            ),
            'showDocListingEditor' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/docs/listing/editor[/]',
                    'defaults' => array(
                        'controller' => 'Docs\Controller\Docs',
                        'action' => 'listingEditor',
                    ),
                ),
            ),

            'showDocListingWriter' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/docs/listing/writer[/]',
                    'defaults' => array(
                        'controller' => 'Docs\Controller\Docs',
                        'action' => 'listingWriter',
                    ),
                ),
            ),

            'showDocPost' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/docs/post[/]',
                    'defaults' => array(
                        'controller' => 'Docs\Controller\Docs',
                        'action' => 'post',
                    ),
                ),
            ),
            'showDocPostAddPost' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/docs/post/add[/]',
                    'defaults' => array(
                        'controller' => 'Docs\Controller\Docs',
                        'action' => 'postAddPost',
                    ),
                ),
            ),
            'showDocPostAddPostToBuffer' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/docs/post/addPostToBuffer[/]',
                    'defaults' => array(
                        'controller' => 'Docs\Controller\Docs',
                        'action' => 'postAddPostToBuffer',
                    ),
                ),
            ),
            'showDocPostGetPostWorkflow' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/docs/post/workflow[/]',
                    'defaults' => array(
                        'controller' => 'Docs\Controller\Docs',
                        'action' => 'postGetPostWorkflow',
                    ),
                ),
            ),
            'showDocPostAddPostComment' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/docs/post/comment[/]',
                    'defaults' => array(
                        'controller' => 'Docs\Controller\Docs',
                        'action' => 'postAddPostComment',
                    ),
                ),
            ),
            'showDocPostGetPostComment' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/docs/post/getcomment[/]',
                    'defaults' => array(
                        'controller' => 'Docs\Controller\Docs',
                        'action' => 'postGetPostComment',
                    ),
                ),
            ),
            'showDocPostAddPostImage' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/docs/post/image[/]',
                    'defaults' => array(
                        'controller' => 'Docs\Controller\Docs',
                        'action' => 'postAddPostImage',
                    ),
                ),
            ),
            'showDocPostRemovePostImage' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/docs/post/image/remove[/]',
                    'defaults' => array(
                        'controller' => 'Docs\Controller\Docs',
                        'action' => 'postRemovePostImage',
                    ),
                ),
            ),
            'showDocPostSchedulePost' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/docs/post/schedule[/]',
                    'defaults' => array(
                        'controller' => 'Docs\Controller\Docs',
                        'action' => 'schedulePost',
                    ),
                ),
            ),
            'showDocPostScheduleGetFacebookAccounts' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/docs/post/schedule/facebook/getAccounts[/]',
                    'defaults' => array(
                        'controller' => 'Docs\Controller\Docs',
                        'action' => 'getFacebookAccounts',
                    ),
                ),
            ),
            'showDocPostScheduleFacebookPost' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/docs/post/schedule/facebook[/]',
                    'defaults' => array(
                        'controller' => 'Docs\Controller\Docs',
                        'action' => 'scheduleFacebookPost',
                    ),
                ),
            ),
            'showDocPostAssignTo' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/docs/post/assignTo[/]',
                    'defaults' => array(
                        'controller' => 'Docs\Controller\Docs',
                        'action' => 'postAssignTo',
                    ),
                ),
            ),
            'showDocPostAssignTopicTo' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/docs/post/assignTopicTo[/]',
                    'defaults' => array(
                        'controller' => 'Docs\Controller\Docs',
                        'action' => 'postAssignTopicTo',
                    ),
                ),
            ),
            'showDocCampaign' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/docs/campaign[/]',
                    'defaults' => array(
                        'controller' => 'Docs\Controller\Docs',
                        'action' => 'campaign',
                    ),
                ),
            ),
            'showDocCampaignAddCampaign' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/docs/campaign/add[/]',
                    'defaults' => array(
                        'controller' => 'Docs\Controller\Docs',
                        'action' => 'campaignAddCampaign',
                    ),
                ),
            ),
            'showDocCampaignSetCampaignDeadlines' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/docs/campaign/deadlines[/]',
                    'defaults' => array(
                        'controller' => 'Docs\Controller\Docs',
                        'action' => 'campaignSetCampaignDeadlines',
                    ),
                ),
            ),
            'showDocPostUpdatePostStatus' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/docs/post/status[/]',
                    'defaults' => array(
                        'controller' => 'Docs\Controller\Docs',
                        'action' => 'postUpdatePostStatus',
                    ),
                ),
            ),
            'showDocPostUpdatePost' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/docs/post/update[/]',
                    'defaults' => array(
                        'controller' => 'Docs\Controller\Docs',
                        'action' => 'postUpdatePost',
                    ),
                ),
            ),
            'showDocPostPublishDate' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/docs/post/publishdate[/]',
                    'defaults' => array(
                        'controller' => 'Docs\Controller\Docs',
                        'action' => 'postPublishDate',
                    ),
                ),
            ),
            'showDocUpdateCampaign' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/docs/campaign/update[/]',
                    'defaults' => array(
                        'controller' => 'Docs\Controller\Docs',
                        'action' => 'campaignUpdateCampaign',
                    ),
                ),
            ),
            'showDocDeleteCampaign' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/docs/campaign/delete[/]',
                    'defaults' => array(
                        'controller' => 'Docs\Controller\Docs',
                        'action' => 'campaignDeleteCampaign',
                    ),
                ),
            ),

            'showDocListCampaign' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/docs/campaign/list[/]',
                    'defaults' => array(
                        'controller' => 'Docs\Controller\Docs',
                        'action' => 'campaignListCampaign',
                    ),
                ),
            ),
            'showDocPostGetPostList' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/docs/post/list[/]',
                    'defaults' => array(
                        'controller' => 'Docs\Controller\Docs',
                        'action' => 'postGetPostList',
                    ),
                ),
            ),
            'showDocPostGetPostInformation' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/docs/post/information[/]',
                    'defaults' => array(
                        'controller' => 'Docs\Controller\Docs',
                        'action' => 'postGetPostInformation',
                    ),
                ),
            ),
            'showDocPostGetPostSearchResult' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/docs/post/search[/]',
                    'defaults' => array(
                        'controller' => 'Docs\Controller\Docs',
                        'action' => 'postGetPostSearchResult',
                    ),
                ),
            ),
            'showDocPostGetPostFilterResult' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/docs/post/filter[/]',
                    'defaults' => array(
                        'controller' => 'Docs\Controller\Docs',
                        'action' => 'postGetPostFilterResult',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'strategies' => array(
            'ViewJsonStrategy',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../src/Docs/View',
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