<?php
namespace Docs\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * This controller handles all docs module requests.
 *
 */
    class DocsController extends AbstractActionController
    {
        public function __construct()
        {
        }

        public function indexAction()
        {
            $view = new ViewModel(array(
                'text' => '
                ',
            ));
            $view->setTemplate('docs/docs/index');

            return $view;
        }

        public function authenticationAction()
        {
            $view = new ViewModel(array(
                'text' => '
                        <h2>Authentication Services</h2>
                        <p style="font-size: 16px;">
                            The basic idea of the role\'s based Authentication module implementation, is that ALL
                            the requested services <strong>should</strong> send info about the user who is making
                            the request. The user identification is achieved through an authentication token that
                            should be sent in the request Headers. In accordance to the user\'s role, if the user
                            is able to execute the service, it will work as usual, otherwise, it will return a
                            400 Bad Request.
                        </p>
                        <p>
                            How it works: the Bearer-Token is generated when the user gets logged in. The Login
                            Service, returns in its response headers the generated Bearer-Token. Also, the Login
                            Service will return a Refresh-Token, that will be useful just in case the token would
                            have expired.
                        </p>
                        <p>
                            The TTL (time to live) for the token is 5 minutes, and its get renewed with EACH
                            sucessfull request to any service (it means, user\'s activity).<br />
                            The token could expires before the user gets logged out, therefore, the frontend
                            application should proceed as follows:
                        </p>
                        <ul>
                            <li>to every request, first, it should validate the user credentials as usual it does</li>
                            <li>make the request sending the Bearer-Token in the headers</li>
                            <li>if the response was 401 Unauthorized, it means the Bearer-Token sometimes was valid,
                            but it isn\'t anymore, so the token may be renewed, what can be achieved using the
                            Refresh Token</li>
                            <li>make the request again, sending the newest Bearer-Token obtained from the Refresh
                            Token Service</li>
                        </ul>
                        <p>
                            The services included in this module are:
                        </p>
                        <ul>
                            <li><a href="authentication/login">Login Service</a></li>
                            <li><a href="authentication/refresh-token">Refresh Token Service</a></li>
                            <li><a href="authentication/checkAuth">Check Auth Service</a></li>
                        </ul>
                ',
            ));
            $view->setTemplate('docs/docs/index');

            return $view;
        }

        public function registrationAction()
        {
            $view = new ViewModel(array(
                'text' => '
                        <h2>Registration Services</h2>
                        <p style="font-size: 16px;">
                            This module takes care of all Registration related operations.
                        </p>
                        <ul>
                            <li><a href="registration/do">Registration Service</a></li>
                        </ul>
                ',
            ));
            $view->setTemplate('docs/docs/index');

            return $view;
        }

        public function userAction()
        {
            $view = new ViewModel(array(
                'text' => '
                        <h2>User Services</h2>
                        <p style="font-size: 16px;">
                            This module takes care of all Users related operations.
                        </p>
                        <ul>
                            <li><a href="user/send-invitation">User Send Invitation Service</a></li>
                        </ul>
                ',
            ));
            $view->setTemplate('docs/docs/index');

            return $view;
        }

        public function userSendInvitationAction()
        {
            $view = new ViewModel(array(
                'module' => 'User Services',
                'service' => 'User Send Invitation Service',
                'description' => 'This service will send an email invitation through SendGrid to a non-user email
                    address, associated to a specific role. This will send an email to the invited user, so they
                    can go through a received Registration Link containing a Registration Code in order to sign up
                    to the site.<br />
                    It will persists some info in order to log the invites, and will return the created invites or
                    an array in case of errors',
                'method' => 'POST',
                'endpoint' => '/user/send-invitation/',
                'requestHeaders' => 'Content-Type: application/json; Bearer-Token: 0e1d4b16538f4c2986b2d2c4f5dfdae7',
                'params' => array(
                    array(
                        'name' => 'invitations',
                        'required' => true,
                        'type' => 'json',
                        'format' => 'Valid JSON array of objects containing at least two attributes,'
                        . '"email", and "role" each one of them. Also you can send the "guess_name" attribute. '
                        . 'TAKE CARE to escape properly the doble quotes (look at the example body).',
                    ),
                ),
                'exampleBody' => '[{ "invitations": "[{\"email\": \"martin.matias.h@gmail.com\", \"role\": \"1\"}, {\"email\": \"elsegundoeamil@mail.com\", \"guess_name\": \"guessssna\", \"role\": \"2\"}]" }]',
                'responses' => array(
                    '200 OK',
                    '400 Bad Request',
                    '404 ',
                ),
                'exampleResponses' => array(
                    array(
                        'code' => '200',
                        'body' => '{"result":[{"id":null,"user_id":1,"role_id":1,"email":"martin.matias.h@gmail.com","guess_name":null,"sent":{"date":"2015-09-14 10:27:44","timezone_type":3,"timezone":"America\/Buenos_Aires"},"registration_code":"264b0294e3b7ece079b57d13cf1bac6c","last_access":null},{"id":null,"user_id":1,"role_id":2,"email":"elsegundoeamil@mail.com","guess_name":"guessssna","sent":{"date":"2015-09-14 10:27:46","timezone_type":3,"timezone":"America\/Buenos_Aires"},"registration_code":"b9a847fbb0df29c7964c27eb324a1f7f","last_access":null}]}',
                        'headers' => ''
                    ),
                ),
            ));
            $view->setTemplate('docs/docs/index');

            return $view;
        }

        public function authenticationLoginAction()
        {
            $view = new ViewModel(array(
                'module' => 'Authentication Services',
                'service' => 'Login Service',
                'description' => 'This service will manage the authentication of users into the application.
                    Also it will return to us an authorization Bearer-Token that will be required to do any subsequent
                    request, as well as a Refresh-Token.<br />In each subsequent request (to any service), the
                    Bearer-Token should be sent into the Request Headers. The Refresh-Token will be useful to refresh the
                    Token just in case it would have expired.<br />The Token will be unique for each user, so it will be
                    tied to the user\'s role in order to manage the permissions.',
                'method' => 'POST',
                'endpoint' => '/authentication/login/',
                'requestHeaders' => 'Content-Type: application/json;',
                'params' => array(
                    array(
                        'name' => 'email',
                        'required' => true,
                        'type' => 'string',
                        'format' => 'Valid email address',
                    ),
                    array(
                        'name' => 'pass',
                        'required' => true,
                        'type' => 'string',
                        'format' => '',
                    ),
                ),
                'exampleBody' => '[{ "user": "mm@mm.com", "password": "lapass" }]',
                'responses' => array(
                    '200 OK',
                    '400 Bad Request',
                    '404 If the user/pass combination doesn\'t match our records',
                ),
                'exampleResponses' => array(
                    array(
                        'code' => '200',
                        'body' => '',
                        'headers' => 'X-User-Id: 1<br />
                                        User-Email: mm@mm.com<br />
                                        Bearer-Token: f85216048605955c173740c5f3547d56<br />
                                        Refresh-Token: 6cc08c5d5b0729b339294ec5b6c07c7d'
                    ),
                ),
            ));
            $view->setTemplate('docs/docs/index');

            return $view;
        }

        public function authenticationCheckAuthAction()
        {
            $view = new ViewModel(array(
                'module' => 'Authentication Services',
                'service' => 'Check Auth Service',
                'description' => 'This service checks if it has generated a token for the user specified).',
                'method' => 'POST',
                'endpoint' => '/authentication/checkAuth/',
                'requestHeaders' => 'Content-Type: application/json;',
                'params' => array(
                    array(
                        'name' => 'email',
                        'required' => true,
                        'type' => 'string',
                        'format' => '',
                    ),
                ),
                'exampleBody' => '[{  "email": "mm@mm.com" }]',
                'responses' => array(
                    '200 OK',
                    '400 Bad Request',
                ),
                'exampleResponses' => array(
                    array(
                        'code' => '200',
                        'body' => '',
                        'headers' => 'X-User-Id: 1<br />
                                        Bearer-Token: d1e43c95fd28e2c37b3af7ce602aaa81<br />
                                        Refresh-Token: 05620009b4aaeba18f9e176eff51a3bb'
                    ),
                ),
            ));
            $view->setTemplate('docs/docs/index');

            return $view;
        }

        public function authenticationRefreshTokenAction()
        {
            $view = new ViewModel(array(
                'module' => 'Authentication Services',
                'service' => 'Refresh Token Service',
                'description' => 'This service will generate a new fresh token, based on the Refresh-Token
                    received (which you\'ve got from the Login Service).',
                'method' => 'POST',
                'endpoint' => '/authentication/refresh-token/',
                'requestHeaders' => 'Content-Type: application/json;',
                'params' => array(
                    array(
                        'name' => 'refreshToken',
                        'required' => true,
                        'type' => 'string',
                        'format' => 'Valid Refresh-Token',
                    ),
                ),
                'exampleBody' => '[{  "refreshToken": "6cc08c5d5b0729b339294ec5b6c07c7d" }]',
                'responses' => array(
                    '200 OK',
                    '400 Bad Request',
                ),
                'exampleResponses' => array(
                    array(
                        'code' => '200',
                        'body' => '',
                        'headers' => 'X-User-Id: 1<br />
                                        User-Email: mm@mm.com<br />
                                        Bearer-Token: d1e43c95fd28e2c37b3af7ce602aaa81<br />
                                        Refresh-Token: 05620009b4aaeba18f9e176eff51a3bb'
                    ),
                ),
            ));
            $view->setTemplate('docs/docs/index');

            return $view;
        }

        public function listingAction()
        {
            $view = new ViewModel(array(
                'text' => '
                        <h2>Listing Services</h2>
                        <p style="font-size: 16px;">
                        </p>
                        <ul>
                            <li><a href="listing/country">List Countries</a></li>
                            <li><a href="listing/language">List Languages</a></li>
                            <li><a href="listing/auditor">List Auditors</a></li>
                            <li><a href="listing/editor">List Editors</a></li>
                            <li><a href="listing/writer">List Writers</a></li>
                        </ul>
                ',
            ));
            $view->setTemplate('docs/docs/index');

            return $view;
        }

        public function listingCountryAction()
        {
            $view = new ViewModel(array(
                'module' => 'Listing Services',
                'service' => 'Listing Countries Service',
                'description' => 'This service will provide a full list of available countries.',
                'method' => 'GET',
                'endpoint' => '/list/country/',
                'requestHeaders' => 'Content-Type: application/json; Bearer-Token: 0e1d4b16538f4c2986b2d2c4f5dfdae7',
                'params' => array(),
                'exampleBody' => '',
                'responses' => array(
                    '200 OK',
                    '400 Bad Request',
                ),
                'exampleResponses' => array(
                    array(
                        'code' => '200',
                        'body' => '{"items":[{"countryCode":"","countryName":"Argentina"}]}',
                        'headers' => ''
                    ),
                ),
            ));
            $view->setTemplate('docs/docs/index');

            return $view;
        }

        public function listingAuditorAction()
        {
            $view = new ViewModel(array(
                'module' => 'Listing Services',
                'service' => 'Listing Auditors Service',
                'description' => 'This service will provide a full list of users with role auditor.',
                'method' => 'GET',
                'endpoint' => '/list/auditor/',
                'requestHeaders' => 'Content-Type: application/json; Bearer-Token: 0e1d4b16538f4c2986b2d2c4f5dfdae7',
                'params' => array(),
                'exampleBody' => '',
                'responses' => array(
                    '200 OK',
                    '400 Bad Request',
                ),
                'exampleResponses' => array(
                    array(
                        'code' => '200',
                        'body' => '{
      "result": [
        {
          "id": 5,
          "role_id": 4,
          "user": {
            "id": 5,
            "username": "zarkoauditor@gmail.com",
            "email": "zarkoauditor@gmail.com",
            "firstName": "zarkoauditor",
            "lastName": "krnetaauditor",
            "created": {
              "date": "-0001-11-30 00:00:00",
              "timezone_type": 3,
              "timezone": "Europe/Paris"
            },
            "modified": {
              "date": "-0001-11-30 00:00:00",
              "timezone_type": 3,
              "timezone": "Europe/Paris"
            }
          }
        }
      ]
    }',
                        'headers' => ''
                    ),
                ),
            ));
            $view->setTemplate('docs/docs/index');

            return $view;
        }


        public function listingEditorAction()
        {
            $view = new ViewModel(array(
                'module' => 'Listing Services',
                'service' => 'Listing Editors Service',
                'description' => 'This service will provide a full list of users with role editor.',
                'method' => 'GET',
                'endpoint' => '/list/editor/',
                'requestHeaders' => 'Content-Type: application/json; Bearer-Token: 0e1d4b16538f4c2986b2d2c4f5dfdae7',
                'params' => array(),
                'exampleBody' => '',
                'responses' => array(
                    '200 OK',
                    '400 Bad Request',
                ),
                'exampleResponses' => array(
                    array(
                        'code' => '200',
                        'body' => '{
      "result": [
        {
          "id": 3,
          "role_id": 2,
          "user": {
            "id": 3,
            "username": "zarkoeditor@gmail.com",
            "email": "zarkoeditor@gmail.com",
            "firstName": "zarkoeditor",
            "lastName": "krnetaeditor",
            "created": {
              "date": "-0001-11-30 00:00:00",
              "timezone_type": 3,
              "timezone": "Europe/Paris"
            },
            "modified": {
              "date": "-0001-11-30 00:00:00",
              "timezone_type": 3,
              "timezone": "Europe/Paris"
            }
          }
        }
      ]
    }',
                        'headers' => ''
                    ),
                ),
            ));
            $view->setTemplate('docs/docs/index');

            return $view;
        }

        public function listingWriterAction()
        {
            $view = new ViewModel(array(
                'module' => 'Listing Services',
                'service' => 'Listing Writer Service',
                'description' => 'This service will provide a full list of users with role writer.',
                'method' => 'GET',
                'endpoint' => '/list/writer/',
                'requestHeaders' => 'Content-Type: application/json; Bearer-Token: 0e1d4b16538f4c2986b2d2c4f5dfdae7',
                'params' => array(),
                'exampleBody' => '',
                'responses' => array(
                    '200 OK',
                    '400 Bad Request',
                ),
                'exampleResponses' => array(
                    array(
                        'code' => '200',
                        'body' => '{
      "result": [
        {
          "id": 4,
          "role_id": 3,
          "user": {
            "id": 4,
            "username": "zarkowriter@gmail.com",
            "email": "zarkowriter@gmail.com",
            "firstName": "zarkowriter",
            "lastName": "krnetawriter",
            "created": {
              "date": "-0001-11-30 00:00:00",
              "timezone_type": 3,
              "timezone": "Europe/Paris"
            },
            "modified": {
              "date": "-0001-11-30 00:00:00",
              "timezone_type": 3,
              "timezone": "Europe/Paris"
            }
          }
        }
      ]
    }',
                        'headers' => ''
                    ),
                ),
            ));
            $view->setTemplate('docs/docs/index');

            return $view;
        }





        public function listingLanguageAction()
        {
            $view = new ViewModel(array(
                'module' => 'Listing Services',
                'service' => 'Listing Languages Service',
                'description' => 'This service will provide a full list of available languages.',
                'method' => 'GET',
                'endpoint' => '/list/language/',
                'requestHeaders' => 'Content-Type: application/json; Bearer-Token: 0e1d4b16538f4c2986b2d2c4f5dfdae7',
                'params' => array(),
                'exampleBody' => '',
                'responses' => array(
                    '200 OK',
                    '400 Bad Request',
                ),
                'exampleResponses' => array(
                    array(
                        'code' => '200',
                        'body' => '{"items":[{"language_region_id":1,"language_region_code":"1","language_region_title":"Espa\u00f1ol","language_region_native":"es_ar","language_id":1,"language_code":"1","language_title":"Espa\u00f1ol","language_native":"Espa\u00f1ol","language_default_locale":"es_ar"}]}',
                        'headers' => ''
                    ),
                ),
            ));
            $view->setTemplate('docs/docs/index');

            return $view;
        }

        public function postAction()
        {
            $view = new ViewModel(array(
                'text' => '
                        <h2>Post Services</h2>
                        <p style="font-size: 16px;">
                            This module takes care of all Posts related operations.
                        </p>
                        <ul>
                            <li><a href="post/add">Add Post Service</a></li>
                            <li><a href="post/addPostToBuffer">Add Post To Buffer Service</a></li>
                            <li><a href="post/comment">Add Post Comment Service</a></li>
                            <li><a href="post/image">Add Post Image Service</a></li>
                            <li><a href="post/video">Add Post Video Service</a></li>
                            <li><a href="post/assignTo">Assign Post To Service</a></li>
                            <li><a href="post/assignTopicTo">Assign Topic To Service</a></li>
                            <li><a href="post/calendar">Get Calendar Service</a></li>
                            <li><a href="post/get">Get Post Service</a></li>
                            <li><a href="post/getcomment">Get Post Comment Service</a></li>
                            <li><a href="post/workflow">Get Post Workflow Service</a></li>
                            <li><a href="post/update">Update Post Service</a></li>
                            <li><a href="post/image/remove">Remove Post Image Service</a></li>
                            <li><a href="post/video/remove">Remove Post Video Service</a></li>
                            <li><a href="post/schedule">Schedule Post Service</a></li>
                            <li><a href="post/status">Update post status Service</a></li>
                            <li><a href="post/publishdate">Set post publishing date</a></li>
                            <li><a href="post/list">Get Post List</a></li>
                            <li><a href="post/information">Get Post Information</a></li>
                            <li><a href="post/search">Get Post Search Result</a></li>
                            <li><a href="post/filter">Get Post Filter Result</a></li>
                            <li><a href="post/schedule/facebook/getAccounts">Get Facebook\'s Accounts Service</a></li>
                            <li><a href="post/schedule/facebook">Schedule Facebook Page Post Service</a></li>
                        </ul>
                ',
            ));
            $view->setTemplate('docs/docs/index');

            return $view;
        }

        public function postAddPostAction()
        {
            $view = new ViewModel(array(
                'module' => 'Post Services',
                'service' => 'Add Post Service',
                'description' => 'This service will manage the request to add a new
                    Post. Mostly, it will be used for the admin role in order to add Posts to a Campaign.',
                'method' => 'POST',
                'endpoint' => '/post/add/',
                'requestHeaders' => 'Content-Type: application/json; Bearer-Token: 0e1d4b16538f4c2986b2d2c4f5dfdae7',
                'params' => array(
                    array(
                        'name' => 'topicId',
                        'required' => true,
                        'type' => 'integer',
                        'format' => '',
                    ),
                ),
                'exampleBody' => '[{  "refreshToken": "6cc08c5d5b0729b339294ec5b6c07c7d" }]',
                'responses' => array(
                    '200 OK',
                    '400 Bad Request',
                ),
                'exampleResponses' => array(
                    array(
                        'code' => '200',
                        'body' => '',
                        'headers' => 'X-User-Id: 1<br />
                                        User-Email: mm@mm.com<br />
                                        Bearer-Token: d1e43c95fd28e2c37b3af7ce602aaa81<br />
                                        Refresh-Token: 05620009b4aaeba18f9e176eff51a3bb'
                    ),
                ),
            ));
            $view->setTemplate('docs/docs/index');

            return $view;
        }
        public function postAddPostToBufferAction()
        {
            $view = new ViewModel(array(
                'module' => 'Post Services',
                'service' => 'Add Post Service',
                'description' => 'This service will manage the request to add a new
                    Post To a Buffer Account. You need to send to this service the posts ids, and the Access Code obtained from Buffer. Check the "Redirect Your Users for Authorization" section here <a href="https://buffer.com/developers/api/oauth">https://buffer.com/developers/api/oauth</a> in order to obtain the needed Access Code. <br />This service will return an array with the results for each post (postId, posted or not, message). If the post was successfully posted, it gets updated into the DB so you can check it later.',
                'method' => 'POST',
                'endpoint' => '/post/buffer/add',
                'requestHeaders' => 'Content-Type: application/json; Bearer-Token: 0e1d4b16538f4c2986b2d2c4f5dfdae7',
                'params' => array(
                    array(
                        'name' => 'accessCode',
                        'required' => true,
                        'type' => 'string',
                        'format' => 'Should be url encoded (as you get it from Buffer)',
                    ),
                    array(
                        'name' => 'postIds',
                        'required' => true,
                        'type' => 'string',
                        'format' => 'Comma separated values of postIds (e.g.: 1,9,18)',
                    ),
                ),
                'exampleBody' => '[{ "accessCode": "1%2Fea2e1348ee6b89bb98fd34cbc2355bfc", "postIds": "1" }]',
                'responses' => array(
                    '200 OK',
                    '400 Bad Request',
                ),
                'exampleResponses' => array(
                    array(
                        'code' => '200',
                        'body' => '{"result":[[{"postId":1,"postedToBuffer":true,"message":"One more post in your Buffer. Keep it topped up!"},{"postId":1,"postedToBuffer":false,"message":"You\u0027ve completely filled the buffer for your \u003Ca href=\u0022https:\/\/bufferapp.com\/app\/profile\/559c49461e0b224260c81c87\/buffer\/queue\u0022\u003E@PedroBuffer\u003C\/a\u003E Twitter profile, nice work! Upgrading to Awesome gets you more space..."}]]}',
                        'headers' => 'X-User-Id: 1<br />
                                        User-Email: mm@mm.com<br />
                                        Bearer-Token: d1e43c95fd28e2c37b3af7ce602aaa81<br />
                                        Refresh-Token: 05620009b4aaeba18f9e176eff51a3bb'
                    ),
                ),
            ));
            $view->setTemplate('docs/docs/index');

            return $view;
        }
        public function postGetPostFilterResultAction()
        {
            $view = new ViewModel(array(
                'module' => 'Post Services',
                'service' => 'Get Post Filter Result Service',
                'description' => 'This service will manage the request to return the list of post for the given filter. The filter should be the id of the customer. Service will return only the posts that are assigned to the logged-in user.',
                'method' => 'POST',
                'endpoint' => '/post/filter',
                'requestHeaders' => 'Content-Type: application/json; Bearer-Token: 0e1d4b16538f4c2986b2d2c4f5dfdae7',
                'params' => array(
                    array(
                        'name' => 'customerId',
                        'required' => true,
                        'type' => 'int',
                        'format' => '',
                    ),
                ),
                'exampleBody' => '[{  "customerId": "1" }]',
                'responses' => array(
                    '200 OK',
                    '400 Bad Request',
                ),
                'exampleResponses' => array(
                    array(
                        'code' => '200',
                        'body' => '

        {
            "result":
            [
                {
                    "id": 10,
                    "topic":
                    {
                        "id": 6,
                        "title": "topic1-1",
                        "slug": "topic1-1",
                        "description": "description 11"
                    },
                    "campaign":
                    {
                        "id": 5,
                        "customer":
                        {
                            "id": 1,
                            "name": "milan"
                        },
                        "name": "new name for campaign 5",
                        "guidelines": "new guidelines for campaign 5",
                        "status": 1,
                        "created":
                        {
                            "date": "2015-06-22 17:55:48",
                            "timezone_type": 3,
                            "timezone": "Europe/Paris"
                        },
                        "createdBy":
                        {
                            "id": 2,
                            "username": "zarkok@gmail.com",
                            "email": "zarkok@gmail.com",
                            "firstName": "zarko",
                            "lastName": "krneta",
                            "created":
                            {
                                "date": "-0001-11-30 00:00:00",
                                "timezone_type": 3,
                                "timezone": "Europe/Paris"
                            },
                            "modified":
                            {
                                "date": "2015-06-10 15:51:30",
                                "timezone_type": 3,
                                "timezone": "Europe/Paris"
                            }
                        }
                    },
                    "status":
                    {
                        "id": 1,
                        "description": "Creado"
                    },
                    "postType":
                    {
                        "id": 1,
                        "description": "facebook"
                    },
                    "assignedToId": "",
                    "requirements": "zarko",
                    "title": "title 1",
                    "body": "body 1",
                    "link": "",
                    "tags": "tag1, tag2, tag3",
                    "programmed": null,
                    "edited": null,
                    "editedById": "",
                    "created":
                    {
                        "date": "2015-06-22 17:55:48",
                        "timezone_type": 3,
                        "timezone": "Europe/Paris"
                    },
                    "createdById": 2,
                    "publishingDate":
                    {
                        "date": "2015-07-27 18:01:42",
                        "timezone_type": 3,
                        "timezone": "Europe/Paris"
                    },
                    "deadlines":
                    [
                        {
                            "id": 1,
                            "campaign":
                            {
                                "id": 5,
                                "customer":
                                {
                                    "id": 1,
                                    "name": "milan"
                                },
                                "name": "new name for campaign 5",
                                "guidelines": "new guidelines for campaign 5",
                                "status": 1,
                                "created":
                                {
                                    "date": "2015-06-22 17:55:48",
                                    "timezone_type": 3,
                                    "timezone": "Europe/Paris"
                                },
                                "createdBy":
                                {
                                    "id": 2,
                                    "username": "zarkok@gmail.com",
                                    "email": "zarkok@gmail.com",
                                    "firstName": "zarko",
                                    "lastName": "krneta",
                                    "created":
                                    {
                                        "date": "-0001-11-30 00:00:00",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    },
                                    "modified":
                                    {
                                        "date": "2015-06-10 15:51:30",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    }
                                }
                            },
                            "topic":
                            {
                                "id": 6,
                                "title": "topic1-1",
                                "slug": "topic1-1",
                                "description": "description 11"
                            },
                            "role":
                            {
                                "id": 2,
                                "title": "editor"
                            },
                            "deadline":
                            {
                                "date": "2015-07-29 16:01:41",
                                "timezone_type": 3,
                                "timezone": "Europe/Paris"
                            }
                        },
                        {
                            "id": 2,
                            "campaign":
                            {
                                "id": 5,
                                "customer":
                                {
                                    "id": 1,
                                    "name": "milan"
                                },
                                "name": "new name for campaign 5",
                                "guidelines": "new guidelines for campaign 5",
                                "status": 1,
                                "created":
                                {
                                    "date": "2015-06-22 17:55:48",
                                    "timezone_type": 3,
                                    "timezone": "Europe/Paris"
                                },
                                "createdBy":
                                {
                                    "id": 2,
                                    "username": "zarkok@gmail.com",
                                    "email": "zarkok@gmail.com",
                                    "firstName": "zarko",
                                    "lastName": "krneta",
                                    "created":
                                    {
                                        "date": "-0001-11-30 00:00:00",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    },
                                    "modified":
                                    {
                                        "date": "2015-06-10 15:51:30",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    }
                                }
                            },
                            "topic":
                            {
                                "id": 6,
                                "title": "topic1-1",
                                "slug": "topic1-1",
                                "description": "description 11"
                            },
                            "role":
                            {
                                "id": 3,
                                "title": "writer"
                            },
                            "deadline":
                            {
                                "date": "2015-08-04 16:02:21",
                                "timezone_type": 3,
                                "timezone": "Europe/Paris"
                            }
                        },
                        {
                            "id": 3,
                            "campaign":
                            {
                                "id": 5,
                                "customer":
                                {
                                    "id": 1,
                                    "name": "milan"
                                },
                                "name": "new name for campaign 5",
                                "guidelines": "new guidelines for campaign 5",
                                "status": 1,
                                "created":
                                {
                                    "date": "2015-06-22 17:55:48",
                                    "timezone_type": 3,
                                    "timezone": "Europe/Paris"
                                },
                                "createdBy":
                                {
                                    "id": 2,
                                    "username": "zarkok@gmail.com",
                                    "email": "zarkok@gmail.com",
                                    "firstName": "zarko",
                                    "lastName": "krneta",
                                    "created":
                                    {
                                        "date": "-0001-11-30 00:00:00",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    },
                                    "modified":
                                    {
                                        "date": "2015-06-10 15:51:30",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    }
                                }
                            },
                            "topic":
                            {
                                "id": 6,
                                "title": "topic1-1",
                                "slug": "topic1-1",
                                "description": "description 11"
                            },
                            "role":
                            {
                                "id": 4,
                                "title": "auditor"
                            },
                            "deadline":
                            {
                                "date": "2015-08-27 16:02:30",
                                "timezone_type": 3,
                                "timezone": "Europe/Paris"
                            }
                        }
                    ]
                },
                {
                    "id": 11,
                    "topic":
                    {
                        "id": 6,
                        "title": "topic1-1",
                        "slug": "topic1-1",
                        "description": "description 11"
                    },
                    "campaign":
                    {
                        "id": 5,
                        "customer":
                        {
                            "id": 1,
                            "name": "milan"
                        },
                        "name": "new name for campaign 5",
                        "guidelines": "new guidelines for campaign 5",
                        "status": 1,
                        "created":
                        {
                            "date": "2015-06-22 17:55:48",
                            "timezone_type": 3,
                            "timezone": "Europe/Paris"
                        },
                        "createdBy":
                        {
                            "id": 2,
                            "username": "zarkok@gmail.com",
                            "email": "zarkok@gmail.com",
                            "firstName": "zarko",
                            "lastName": "krneta",
                            "created":
                            {
                                "date": "-0001-11-30 00:00:00",
                                "timezone_type": 3,
                                "timezone": "Europe/Paris"
                            },
                            "modified":
                            {
                                "date": "2015-06-10 15:51:30",
                                "timezone_type": 3,
                                "timezone": "Europe/Paris"
                            }
                        }
                    },
                    "status":
                    {
                        "id": 1,
                        "description": "Creado"
                    },
                    "postType":
                    {
                        "id": 1,
                        "description": "facebook"
                    },
                    "assignedToId": "",
                    "requirements": "",
                    "title": "",
                    "body": "",
                    "link": "",
                    "tags": "",
                    "programmed": null,
                    "edited": null,
                    "editedById": "",
                    "created":
                    {
                        "date": "2015-06-22 17:55:48",
                        "timezone_type": 3,
                        "timezone": "Europe/Paris"
                    },
                    "createdById": 2,
                    "publishingDate": null,
                    "deadlines":
                    [
                        {
                            "id": 1,
                            "campaign":
                            {
                                "id": 5,
                                "customer":
                                {
                                    "id": 1,
                                    "name": "milan"
                                },
                                "name": "new name for campaign 5",
                                "guidelines": "new guidelines for campaign 5",
                                "status": 1,
                                "created":
                                {
                                    "date": "2015-06-22 17:55:48",
                                    "timezone_type": 3,
                                    "timezone": "Europe/Paris"
                                },
                                "createdBy":
                                {
                                    "id": 2,
                                    "username": "zarkok@gmail.com",
                                    "email": "zarkok@gmail.com",
                                    "firstName": "zarko",
                                    "lastName": "krneta",
                                    "created":
                                    {
                                        "date": "-0001-11-30 00:00:00",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    },
                                    "modified":
                                    {
                                        "date": "2015-06-10 15:51:30",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    }
                                }
                            },
                            "topic":
                            {
                                "id": 6,
                                "title": "topic1-1",
                                "slug": "topic1-1",
                                "description": "description 11"
                            },
                            "role":
                            {
                                "id": 2,
                                "title": "editor"
                            },
                            "deadline":
                            {
                                "date": "2015-07-29 16:01:41",
                                "timezone_type": 3,
                                "timezone": "Europe/Paris"
                            }
                        },
                        {
                            "id": 2,
                            "campaign":
                            {
                                "id": 5,
                                "customer":
                                {
                                    "id": 1,
                                    "name": "milan"
                                },
                                "name": "new name for campaign 5",
                                "guidelines": "new guidelines for campaign 5",
                                "status": 1,
                                "created":
                                {
                                    "date": "2015-06-22 17:55:48",
                                    "timezone_type": 3,
                                    "timezone": "Europe/Paris"
                                },
                                "createdBy":
                                {
                                    "id": 2,
                                    "username": "zarkok@gmail.com",
                                    "email": "zarkok@gmail.com",
                                    "firstName": "zarko",
                                    "lastName": "krneta",
                                    "created":
                                    {
                                        "date": "-0001-11-30 00:00:00",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    },
                                    "modified":
                                    {
                                        "date": "2015-06-10 15:51:30",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    }
                                }
                            },
                            "topic":
                            {
                                "id": 6,
                                "title": "topic1-1",
                                "slug": "topic1-1",
                                "description": "description 11"
                            },
                            "role":
                            {
                                "id": 3,
                                "title": "writer"
                            },
                            "deadline":
                            {
                                "date": "2015-08-04 16:02:21",
                                "timezone_type": 3,
                                "timezone": "Europe/Paris"
                            }
                        },
                        {
                            "id": 3,
                            "campaign":
                            {
                                "id": 5,
                                "customer":
                                {
                                    "id": 1,
                                    "name": "milan"
                                },
                                "name": "new name for campaign 5",
                                "guidelines": "new guidelines for campaign 5",
                                "status": 1,
                                "created":
                                {
                                    "date": "2015-06-22 17:55:48",
                                    "timezone_type": 3,
                                    "timezone": "Europe/Paris"
                                },
                                "createdBy":
                                {
                                    "id": 2,
                                    "username": "zarkok@gmail.com",
                                    "email": "zarkok@gmail.com",
                                    "firstName": "zarko",
                                    "lastName": "krneta",
                                    "created":
                                    {
                                        "date": "-0001-11-30 00:00:00",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    },
                                    "modified":
                                    {
                                        "date": "2015-06-10 15:51:30",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    }
                                }
                            },
                            "topic":
                            {
                                "id": 6,
                                "title": "topic1-1",
                                "slug": "topic1-1",
                                "description": "description 11"
                            },
                            "role":
                            {
                                "id": 4,
                                "title": "auditor"
                            },
                            "deadline":
                            {
                                "date": "2015-08-27 16:02:30",
                                "timezone_type": 3,
                                "timezone": "Europe/Paris"
                            }
                        }
                    ]
                },
                {
                    "id": 12,
                    "topic":
                    {
                        "id": 6,
                        "title": "topic1-1",
                        "slug": "topic1-1",
                        "description": "description 11"
                    },
                    "campaign":
                    {
                        "id": 5,
                        "customer":
                        {
                            "id": 1,
                            "name": "milan"
                        },
                        "name": "new name for campaign 5",
                        "guidelines": "new guidelines for campaign 5",
                        "status": 1,
                        "created":
                        {
                            "date": "2015-06-22 17:55:48",
                            "timezone_type": 3,
                            "timezone": "Europe/Paris"
                        },
                        "createdBy":
                        {
                            "id": 2,
                            "username": "zarkok@gmail.com",
                            "email": "zarkok@gmail.com",
                            "firstName": "zarko",
                            "lastName": "krneta",
                            "created":
                            {
                                "date": "-0001-11-30 00:00:00",
                                "timezone_type": 3,
                                "timezone": "Europe/Paris"
                            },
                            "modified":
                            {
                                "date": "2015-06-10 15:51:30",
                                "timezone_type": 3,
                                "timezone": "Europe/Paris"
                            }
                        }
                    },
                    "status":
                    {
                        "id": 1,
                        "description": "Creado"
                    },
                    "postType":
                    {
                        "id": 1,
                        "description": "facebook"
                    },
                    "assignedToId": "",
                    "requirements": "",
                    "title": "",
                    "body": "",
                    "link": "",
                    "tags": "",
                    "programmed": null,
                    "edited": null,
                    "editedById": "",
                    "created":
                    {
                        "date": "2015-06-22 17:55:48",
                        "timezone_type": 3,
                        "timezone": "Europe/Paris"
                    },
                    "createdById": 2,
                    "publishingDate": null,
                    "deadlines":
                    [
                        {
                            "id": 1,
                            "campaign":
                            {
                                "id": 5,
                                "customer":
                                {
                                    "id": 1,
                                    "name": "milan"
                                },
                                "name": "new name for campaign 5",
                                "guidelines": "new guidelines for campaign 5",
                                "status": 1,
                                "created":
                                {
                                    "date": "2015-06-22 17:55:48",
                                    "timezone_type": 3,
                                    "timezone": "Europe/Paris"
                                },
                                "createdBy":
                                {
                                    "id": 2,
                                    "username": "zarkok@gmail.com",
                                    "email": "zarkok@gmail.com",
                                    "firstName": "zarko",
                                    "lastName": "krneta",
                                    "created":
                                    {
                                        "date": "-0001-11-30 00:00:00",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    },
                                    "modified":
                                    {
                                        "date": "2015-06-10 15:51:30",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    }
                                }
                            },
                            "topic":
                            {
                                "id": 6,
                                "title": "topic1-1",
                                "slug": "topic1-1",
                                "description": "description 11"
                            },
                            "role":
                            {
                                "id": 2,
                                "title": "editor"
                            },
                            "deadline":
                            {
                                "date": "2015-07-29 16:01:41",
                                "timezone_type": 3,
                                "timezone": "Europe/Paris"
                            }
                        },
                        {
                            "id": 2,
                            "campaign":
                            {
                                "id": 5,
                                "customer":
                                {
                                    "id": 1,
                                    "name": "milan"
                                },
                                "name": "new name for campaign 5",
                                "guidelines": "new guidelines for campaign 5",
                                "status": 1,
                                "created":
                                {
                                    "date": "2015-06-22 17:55:48",
                                    "timezone_type": 3,
                                    "timezone": "Europe/Paris"
                                },
                                "createdBy":
                                {
                                    "id": 2,
                                    "username": "zarkok@gmail.com",
                                    "email": "zarkok@gmail.com",
                                    "firstName": "zarko",
                                    "lastName": "krneta",
                                    "created":
                                    {
                                        "date": "-0001-11-30 00:00:00",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    },
                                    "modified":
                                    {
                                        "date": "2015-06-10 15:51:30",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    }
                                }
                            },
                            "topic":
                            {
                                "id": 6,
                                "title": "topic1-1",
                                "slug": "topic1-1",
                                "description": "description 11"
                            },
                            "role":
                            {
                                "id": 3,
                                "title": "writer"
                            },
                            "deadline":
                            {
                                "date": "2015-08-04 16:02:21",
                                "timezone_type": 3,
                                "timezone": "Europe/Paris"
                            }
                        },
                        {
                            "id": 3,
                            "campaign":
                            {
                                "id": 5,
                                "customer":
                                {
                                    "id": 1,
                                    "name": "milan"
                                },
                                "name": "new name for campaign 5",
                                "guidelines": "new guidelines for campaign 5",
                                "status": 1,
                                "created":
                                {
                                    "date": "2015-06-22 17:55:48",
                                    "timezone_type": 3,
                                    "timezone": "Europe/Paris"
                                },
                                "createdBy":
                                {
                                    "id": 2,
                                    "username": "zarkok@gmail.com",
                                    "email": "zarkok@gmail.com",
                                    "firstName": "zarko",
                                    "lastName": "krneta",
                                    "created":
                                    {
                                        "date": "-0001-11-30 00:00:00",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    },
                                    "modified":
                                    {
                                        "date": "2015-06-10 15:51:30",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    }
                                }
                            },
                            "topic":
                            {
                                "id": 6,
                                "title": "topic1-1",
                                "slug": "topic1-1",
                                "description": "description 11"
                            },
                            "role":
                            {
                                "id": 4,
                                "title": "auditor"
                            },
                            "deadline":
                            {
                                "date": "2015-08-27 16:02:30",
                                "timezone_type": 3,
                                "timezone": "Europe/Paris"
                            }
                        }
                    ]
                },
                {
                    "id": 13,
                    "topic":
                    {
                        "id": 6,
                        "title": "topic1-1",
                        "slug": "topic1-1",
                        "description": "description 11"
                    },
                    "campaign":
                    {
                        "id": 5,
                        "customer":
                        {
                            "id": 1,
                            "name": "milan"
                        },
                        "name": "new name for campaign 5",
                        "guidelines": "new guidelines for campaign 5",
                        "status": 1,
                        "created":
                        {
                            "date": "2015-06-22 17:55:48",
                            "timezone_type": 3,
                            "timezone": "Europe/Paris"
                        },
                        "createdBy":
                        {
                            "id": 2,
                            "username": "zarkok@gmail.com",
                            "email": "zarkok@gmail.com",
                            "firstName": "zarko",
                            "lastName": "krneta",
                            "created":
                            {
                                "date": "-0001-11-30 00:00:00",
                                "timezone_type": 3,
                                "timezone": "Europe/Paris"
                            },
                            "modified":
                            {
                                "date": "2015-06-10 15:51:30",
                                "timezone_type": 3,
                                "timezone": "Europe/Paris"
                            }
                        }
                    },
                    "status":
                    {
                        "id": 1,
                        "description": "Creado"
                    },
                    "postType":
                    {
                        "id": 1,
                        "description": "facebook"
                    },
                    "assignedToId": "",
                    "requirements": "",
                    "title": "",
                    "body": "",
                    "link": "",
                    "tags": "",
                    "programmed": null,
                    "edited": null,
                    "editedById": "",
                    "created":
                    {
                        "date": "2015-06-22 17:55:48",
                        "timezone_type": 3,
                        "timezone": "Europe/Paris"
                    },
                    "createdById": 2,
                    "publishingDate": null,
                    "deadlines":
                    [
                        {
                            "id": 1,
                            "campaign":
                            {
                                "id": 5,
                                "customer":
                                {
                                    "id": 1,
                                    "name": "milan"
                                },
                                "name": "new name for campaign 5",
                                "guidelines": "new guidelines for campaign 5",
                                "status": 1,
                                "created":
                                {
                                    "date": "2015-06-22 17:55:48",
                                    "timezone_type": 3,
                                    "timezone": "Europe/Paris"
                                },
                                "createdBy":
                                {
                                    "id": 2,
                                    "username": "zarkok@gmail.com",
                                    "email": "zarkok@gmail.com",
                                    "firstName": "zarko",
                                    "lastName": "krneta",
                                    "created":
                                    {
                                        "date": "-0001-11-30 00:00:00",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    },
                                    "modified":
                                    {
                                        "date": "2015-06-10 15:51:30",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    }
                                }
                            },
                            "topic":
                            {
                                "id": 6,
                                "title": "topic1-1",
                                "slug": "topic1-1",
                                "description": "description 11"
                            },
                            "role":
                            {
                                "id": 2,
                                "title": "editor"
                            },
                            "deadline":
                            {
                                "date": "2015-07-29 16:01:41",
                                "timezone_type": 3,
                                "timezone": "Europe/Paris"
                            }
                        },
                        {
                            "id": 2,
                            "campaign":
                            {
                                "id": 5,
                                "customer":
                                {
                                    "id": 1,
                                    "name": "milan"
                                },
                                "name": "new name for campaign 5",
                                "guidelines": "new guidelines for campaign 5",
                                "status": 1,
                                "created":
                                {
                                    "date": "2015-06-22 17:55:48",
                                    "timezone_type": 3,
                                    "timezone": "Europe/Paris"
                                },
                                "createdBy":
                                {
                                    "id": 2,
                                    "username": "zarkok@gmail.com",
                                    "email": "zarkok@gmail.com",
                                    "firstName": "zarko",
                                    "lastName": "krneta",
                                    "created":
                                    {
                                        "date": "-0001-11-30 00:00:00",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    },
                                    "modified":
                                    {
                                        "date": "2015-06-10 15:51:30",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    }
                                }
                            },
                            "topic":
                            {
                                "id": 6,
                                "title": "topic1-1",
                                "slug": "topic1-1",
                                "description": "description 11"
                            },
                            "role":
                            {
                                "id": 3,
                                "title": "writer"
                            },
                            "deadline":
                            {
                                "date": "2015-08-04 16:02:21",
                                "timezone_type": 3,
                                "timezone": "Europe/Paris"
                            }
                        },
                        {
                            "id": 3,
                            "campaign":
                            {
                                "id": 5,
                                "customer":
                                {
                                    "id": 1,
                                    "name": "milan"
                                },
                                "name": "new name for campaign 5",
                                "guidelines": "new guidelines for campaign 5",
                                "status": 1,
                                "created":
                                {
                                    "date": "2015-06-22 17:55:48",
                                    "timezone_type": 3,
                                    "timezone": "Europe/Paris"
                                },
                                "createdBy":
                                {
                                    "id": 2,
                                    "username": "zarkok@gmail.com",
                                    "email": "zarkok@gmail.com",
                                    "firstName": "zarko",
                                    "lastName": "krneta",
                                    "created":
                                    {
                                        "date": "-0001-11-30 00:00:00",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    },
                                    "modified":
                                    {
                                        "date": "2015-06-10 15:51:30",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    }
                                }
                            },
                            "topic":
                            {
                                "id": 6,
                                "title": "topic1-1",
                                "slug": "topic1-1",
                                "description": "description 11"
                            },
                            "role":
                            {
                                "id": 4,
                                "title": "auditor"
                            },
                            "deadline":
                            {
                                "date": "2015-08-27 16:02:30",
                                "timezone_type": 3,
                                "timezone": "Europe/Paris"
                            }
                        }
                    ]
                }
            ]
        }

    ',
                        'headers' => 'X-User-Id: 1<br />
                                        User-Email: mm@mm.com<br />
                                        Bearer-Token: d1e43c95fd28e2c37b3af7ce602aaa81<br />
                                        Refresh-Token: 05620009b4aaeba18f9e176eff51a3bb'
                    ),
                ),
            ));
            $view->setTemplate('docs/docs/index');

            return $view;
        }
        public function postGetPostSearchResultAction()
        {
            $view = new ViewModel(array(
                'module' => 'Post Services',
                'service' => 'Get Post Search Result Service',
                'description' => 'This service will manage the request to return the list of post for the given search query. The service searches for the given query in the campaign name, topic name, customer name and requirements. Service will return only the posts that contain search query in the listed fields and that are assigned to the currently logged-in user.',
                'method' => 'POST',
                'endpoint' => '/post/search',
                'requestHeaders' => 'Content-Type: application/json; Bearer-Token: 0e1d4b16538f4c2986b2d2c4f5dfdae7',
                'params' => array(
                    array(
                        'name' => 'searchQuery',
                        'required' => true,
                        'type' => 'string',
                        'format' => '',
                    ),
                ),
                'exampleBody' => '[{  "searchQuery": "topic1-1" }]',
                'responses' => array(
                    '200 OK',
                    '400 Bad Request',
                ),
                'exampleResponses' => array(
                    array(
                        'code' => '200',
                        'body' => '

        {
            "result":
            [
                {
                    "id": 10,
                    "topic":
                    {
                        "id": 6,
                        "title": "topic1-1",
                        "slug": "topic1-1",
                        "description": "description 11"
                    },
                    "campaign":
                    {
                        "id": 5,
                        "customer":
                        {
                            "id": 1,
                            "name": "milan"
                        },
                        "name": "new name for campaign 5",
                        "guidelines": "new guidelines for campaign 5",
                        "status": 1,
                        "created":
                        {
                            "date": "2015-06-22 17:55:48",
                            "timezone_type": 3,
                            "timezone": "Europe/Paris"
                        },
                        "createdBy":
                        {
                            "id": 2,
                            "username": "zarkok@gmail.com",
                            "email": "zarkok@gmail.com",
                            "firstName": "zarko",
                            "lastName": "krneta",
                            "created":
                            {
                                "date": "-0001-11-30 00:00:00",
                                "timezone_type": 3,
                                "timezone": "Europe/Paris"
                            },
                            "modified":
                            {
                                "date": "2015-06-10 15:51:30",
                                "timezone_type": 3,
                                "timezone": "Europe/Paris"
                            }
                        }
                    },
                    "status":
                    {
                        "id": 1,
                        "description": "Creado"
                    },
                    "postType":
                    {
                        "id": 1,
                        "description": "facebook"
                    },
                    "assignedToId": "",
                    "requirements": "zarko",
                    "title": "title 1",
                    "body": "body 1",
                    "link": "",
                    "tags": "tag1, tag2, tag3",
                    "programmed": null,
                    "edited": null,
                    "editedById": "",
                    "created":
                    {
                        "date": "2015-06-22 17:55:48",
                        "timezone_type": 3,
                        "timezone": "Europe/Paris"
                    },
                    "createdById": 2,
                    "publishingDate":
                    {
                        "date": "2015-07-27 18:01:42",
                        "timezone_type": 3,
                        "timezone": "Europe/Paris"
                    },
                    "deadlines":
                    [
                        {
                            "id": 1,
                            "campaign":
                            {
                                "id": 5,
                                "customer":
                                {
                                    "id": 1,
                                    "name": "milan"
                                },
                                "name": "new name for campaign 5",
                                "guidelines": "new guidelines for campaign 5",
                                "status": 1,
                                "created":
                                {
                                    "date": "2015-06-22 17:55:48",
                                    "timezone_type": 3,
                                    "timezone": "Europe/Paris"
                                },
                                "createdBy":
                                {
                                    "id": 2,
                                    "username": "zarkok@gmail.com",
                                    "email": "zarkok@gmail.com",
                                    "firstName": "zarko",
                                    "lastName": "krneta",
                                    "created":
                                    {
                                        "date": "-0001-11-30 00:00:00",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    },
                                    "modified":
                                    {
                                        "date": "2015-06-10 15:51:30",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    }
                                }
                            },
                            "topic":
                            {
                                "id": 6,
                                "title": "topic1-1",
                                "slug": "topic1-1",
                                "description": "description 11"
                            },
                            "role":
                            {
                                "id": 2,
                                "title": "editor"
                            },
                            "deadline":
                            {
                                "date": "2015-07-29 16:01:41",
                                "timezone_type": 3,
                                "timezone": "Europe/Paris"
                            }
                        },
                        {
                            "id": 2,
                            "campaign":
                            {
                                "id": 5,
                                "customer":
                                {
                                    "id": 1,
                                    "name": "milan"
                                },
                                "name": "new name for campaign 5",
                                "guidelines": "new guidelines for campaign 5",
                                "status": 1,
                                "created":
                                {
                                    "date": "2015-06-22 17:55:48",
                                    "timezone_type": 3,
                                    "timezone": "Europe/Paris"
                                },
                                "createdBy":
                                {
                                    "id": 2,
                                    "username": "zarkok@gmail.com",
                                    "email": "zarkok@gmail.com",
                                    "firstName": "zarko",
                                    "lastName": "krneta",
                                    "created":
                                    {
                                        "date": "-0001-11-30 00:00:00",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    },
                                    "modified":
                                    {
                                        "date": "2015-06-10 15:51:30",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    }
                                }
                            },
                            "topic":
                            {
                                "id": 6,
                                "title": "topic1-1",
                                "slug": "topic1-1",
                                "description": "description 11"
                            },
                            "role":
                            {
                                "id": 3,
                                "title": "writer"
                            },
                            "deadline":
                            {
                                "date": "2015-08-04 16:02:21",
                                "timezone_type": 3,
                                "timezone": "Europe/Paris"
                            }
                        },
                        {
                            "id": 3,
                            "campaign":
                            {
                                "id": 5,
                                "customer":
                                {
                                    "id": 1,
                                    "name": "milan"
                                },
                                "name": "new name for campaign 5",
                                "guidelines": "new guidelines for campaign 5",
                                "status": 1,
                                "created":
                                {
                                    "date": "2015-06-22 17:55:48",
                                    "timezone_type": 3,
                                    "timezone": "Europe/Paris"
                                },
                                "createdBy":
                                {
                                    "id": 2,
                                    "username": "zarkok@gmail.com",
                                    "email": "zarkok@gmail.com",
                                    "firstName": "zarko",
                                    "lastName": "krneta",
                                    "created":
                                    {
                                        "date": "-0001-11-30 00:00:00",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    },
                                    "modified":
                                    {
                                        "date": "2015-06-10 15:51:30",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    }
                                }
                            },
                            "topic":
                            {
                                "id": 6,
                                "title": "topic1-1",
                                "slug": "topic1-1",
                                "description": "description 11"
                            },
                            "role":
                            {
                                "id": 4,
                                "title": "auditor"
                            },
                            "deadline":
                            {
                                "date": "2015-08-27 16:02:30",
                                "timezone_type": 3,
                                "timezone": "Europe/Paris"
                            }
                        }
                    ]
                },
                {
                    "id": 11,
                    "topic":
                    {
                        "id": 6,
                        "title": "topic1-1",
                        "slug": "topic1-1",
                        "description": "description 11"
                    },
                    "campaign":
                    {
                        "id": 5,
                        "customer":
                        {
                            "id": 1,
                            "name": "milan"
                        },
                        "name": "new name for campaign 5",
                        "guidelines": "new guidelines for campaign 5",
                        "status": 1,
                        "created":
                        {
                            "date": "2015-06-22 17:55:48",
                            "timezone_type": 3,
                            "timezone": "Europe/Paris"
                        },
                        "createdBy":
                        {
                            "id": 2,
                            "username": "zarkok@gmail.com",
                            "email": "zarkok@gmail.com",
                            "firstName": "zarko",
                            "lastName": "krneta",
                            "created":
                            {
                                "date": "-0001-11-30 00:00:00",
                                "timezone_type": 3,
                                "timezone": "Europe/Paris"
                            },
                            "modified":
                            {
                                "date": "2015-06-10 15:51:30",
                                "timezone_type": 3,
                                "timezone": "Europe/Paris"
                            }
                        }
                    },
                    "status":
                    {
                        "id": 1,
                        "description": "Creado"
                    },
                    "postType":
                    {
                        "id": 1,
                        "description": "facebook"
                    },
                    "assignedToId": "",
                    "requirements": "",
                    "title": "",
                    "body": "",
                    "link": "",
                    "tags": "",
                    "programmed": null,
                    "edited": null,
                    "editedById": "",
                    "created":
                    {
                        "date": "2015-06-22 17:55:48",
                        "timezone_type": 3,
                        "timezone": "Europe/Paris"
                    },
                    "createdById": 2,
                    "publishingDate": null,
                    "deadlines":
                    [
                        {
                            "id": 1,
                            "campaign":
                            {
                                "id": 5,
                                "customer":
                                {
                                    "id": 1,
                                    "name": "milan"
                                },
                                "name": "new name for campaign 5",
                                "guidelines": "new guidelines for campaign 5",
                                "status": 1,
                                "created":
                                {
                                    "date": "2015-06-22 17:55:48",
                                    "timezone_type": 3,
                                    "timezone": "Europe/Paris"
                                },
                                "createdBy":
                                {
                                    "id": 2,
                                    "username": "zarkok@gmail.com",
                                    "email": "zarkok@gmail.com",
                                    "firstName": "zarko",
                                    "lastName": "krneta",
                                    "created":
                                    {
                                        "date": "-0001-11-30 00:00:00",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    },
                                    "modified":
                                    {
                                        "date": "2015-06-10 15:51:30",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    }
                                }
                            },
                            "topic":
                            {
                                "id": 6,
                                "title": "topic1-1",
                                "slug": "topic1-1",
                                "description": "description 11"
                            },
                            "role":
                            {
                                "id": 2,
                                "title": "editor"
                            },
                            "deadline":
                            {
                                "date": "2015-07-29 16:01:41",
                                "timezone_type": 3,
                                "timezone": "Europe/Paris"
                            }
                        },
                        {
                            "id": 2,
                            "campaign":
                            {
                                "id": 5,
                                "customer":
                                {
                                    "id": 1,
                                    "name": "milan"
                                },
                                "name": "new name for campaign 5",
                                "guidelines": "new guidelines for campaign 5",
                                "status": 1,
                                "created":
                                {
                                    "date": "2015-06-22 17:55:48",
                                    "timezone_type": 3,
                                    "timezone": "Europe/Paris"
                                },
                                "createdBy":
                                {
                                    "id": 2,
                                    "username": "zarkok@gmail.com",
                                    "email": "zarkok@gmail.com",
                                    "firstName": "zarko",
                                    "lastName": "krneta",
                                    "created":
                                    {
                                        "date": "-0001-11-30 00:00:00",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    },
                                    "modified":
                                    {
                                        "date": "2015-06-10 15:51:30",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    }
                                }
                            },
                            "topic":
                            {
                                "id": 6,
                                "title": "topic1-1",
                                "slug": "topic1-1",
                                "description": "description 11"
                            },
                            "role":
                            {
                                "id": 3,
                                "title": "writer"
                            },
                            "deadline":
                            {
                                "date": "2015-08-04 16:02:21",
                                "timezone_type": 3,
                                "timezone": "Europe/Paris"
                            }
                        },
                        {
                            "id": 3,
                            "campaign":
                            {
                                "id": 5,
                                "customer":
                                {
                                    "id": 1,
                                    "name": "milan"
                                },
                                "name": "new name for campaign 5",
                                "guidelines": "new guidelines for campaign 5",
                                "status": 1,
                                "created":
                                {
                                    "date": "2015-06-22 17:55:48",
                                    "timezone_type": 3,
                                    "timezone": "Europe/Paris"
                                },
                                "createdBy":
                                {
                                    "id": 2,
                                    "username": "zarkok@gmail.com",
                                    "email": "zarkok@gmail.com",
                                    "firstName": "zarko",
                                    "lastName": "krneta",
                                    "created":
                                    {
                                        "date": "-0001-11-30 00:00:00",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    },
                                    "modified":
                                    {
                                        "date": "2015-06-10 15:51:30",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    }
                                }
                            },
                            "topic":
                            {
                                "id": 6,
                                "title": "topic1-1",
                                "slug": "topic1-1",
                                "description": "description 11"
                            },
                            "role":
                            {
                                "id": 4,
                                "title": "auditor"
                            },
                            "deadline":
                            {
                                "date": "2015-08-27 16:02:30",
                                "timezone_type": 3,
                                "timezone": "Europe/Paris"
                            }
                        }
                    ]
                },
                {
                    "id": 12,
                    "topic":
                    {
                        "id": 6,
                        "title": "topic1-1",
                        "slug": "topic1-1",
                        "description": "description 11"
                    },
                    "campaign":
                    {
                        "id": 5,
                        "customer":
                        {
                            "id": 1,
                            "name": "milan"
                        },
                        "name": "new name for campaign 5",
                        "guidelines": "new guidelines for campaign 5",
                        "status": 1,
                        "created":
                        {
                            "date": "2015-06-22 17:55:48",
                            "timezone_type": 3,
                            "timezone": "Europe/Paris"
                        },
                        "createdBy":
                        {
                            "id": 2,
                            "username": "zarkok@gmail.com",
                            "email": "zarkok@gmail.com",
                            "firstName": "zarko",
                            "lastName": "krneta",
                            "created":
                            {
                                "date": "-0001-11-30 00:00:00",
                                "timezone_type": 3,
                                "timezone": "Europe/Paris"
                            },
                            "modified":
                            {
                                "date": "2015-06-10 15:51:30",
                                "timezone_type": 3,
                                "timezone": "Europe/Paris"
                            }
                        }
                    },
                    "status":
                    {
                        "id": 1,
                        "description": "Creado"
                    },
                    "postType":
                    {
                        "id": 1,
                        "description": "facebook"
                    },
                    "assignedToId": "",
                    "requirements": "",
                    "title": "",
                    "body": "",
                    "link": "",
                    "tags": "",
                    "programmed": null,
                    "edited": null,
                    "editedById": "",
                    "created":
                    {
                        "date": "2015-06-22 17:55:48",
                        "timezone_type": 3,
                        "timezone": "Europe/Paris"
                    },
                    "createdById": 2,
                    "publishingDate": null,
                    "deadlines":
                    [
                        {
                            "id": 1,
                            "campaign":
                            {
                                "id": 5,
                                "customer":
                                {
                                    "id": 1,
                                    "name": "milan"
                                },
                                "name": "new name for campaign 5",
                                "guidelines": "new guidelines for campaign 5",
                                "status": 1,
                                "created":
                                {
                                    "date": "2015-06-22 17:55:48",
                                    "timezone_type": 3,
                                    "timezone": "Europe/Paris"
                                },
                                "createdBy":
                                {
                                    "id": 2,
                                    "username": "zarkok@gmail.com",
                                    "email": "zarkok@gmail.com",
                                    "firstName": "zarko",
                                    "lastName": "krneta",
                                    "created":
                                    {
                                        "date": "-0001-11-30 00:00:00",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    },
                                    "modified":
                                    {
                                        "date": "2015-06-10 15:51:30",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    }
                                }
                            },
                            "topic":
                            {
                                "id": 6,
                                "title": "topic1-1",
                                "slug": "topic1-1",
                                "description": "description 11"
                            },
                            "role":
                            {
                                "id": 2,
                                "title": "editor"
                            },
                            "deadline":
                            {
                                "date": "2015-07-29 16:01:41",
                                "timezone_type": 3,
                                "timezone": "Europe/Paris"
                            }
                        },
                        {
                            "id": 2,
                            "campaign":
                            {
                                "id": 5,
                                "customer":
                                {
                                    "id": 1,
                                    "name": "milan"
                                },
                                "name": "new name for campaign 5",
                                "guidelines": "new guidelines for campaign 5",
                                "status": 1,
                                "created":
                                {
                                    "date": "2015-06-22 17:55:48",
                                    "timezone_type": 3,
                                    "timezone": "Europe/Paris"
                                },
                                "createdBy":
                                {
                                    "id": 2,
                                    "username": "zarkok@gmail.com",
                                    "email": "zarkok@gmail.com",
                                    "firstName": "zarko",
                                    "lastName": "krneta",
                                    "created":
                                    {
                                        "date": "-0001-11-30 00:00:00",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    },
                                    "modified":
                                    {
                                        "date": "2015-06-10 15:51:30",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    }
                                }
                            },
                            "topic":
                            {
                                "id": 6,
                                "title": "topic1-1",
                                "slug": "topic1-1",
                                "description": "description 11"
                            },
                            "role":
                            {
                                "id": 3,
                                "title": "writer"
                            },
                            "deadline":
                            {
                                "date": "2015-08-04 16:02:21",
                                "timezone_type": 3,
                                "timezone": "Europe/Paris"
                            }
                        },
                        {
                            "id": 3,
                            "campaign":
                            {
                                "id": 5,
                                "customer":
                                {
                                    "id": 1,
                                    "name": "milan"
                                },
                                "name": "new name for campaign 5",
                                "guidelines": "new guidelines for campaign 5",
                                "status": 1,
                                "created":
                                {
                                    "date": "2015-06-22 17:55:48",
                                    "timezone_type": 3,
                                    "timezone": "Europe/Paris"
                                },
                                "createdBy":
                                {
                                    "id": 2,
                                    "username": "zarkok@gmail.com",
                                    "email": "zarkok@gmail.com",
                                    "firstName": "zarko",
                                    "lastName": "krneta",
                                    "created":
                                    {
                                        "date": "-0001-11-30 00:00:00",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    },
                                    "modified":
                                    {
                                        "date": "2015-06-10 15:51:30",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    }
                                }
                            },
                            "topic":
                            {
                                "id": 6,
                                "title": "topic1-1",
                                "slug": "topic1-1",
                                "description": "description 11"
                            },
                            "role":
                            {
                                "id": 4,
                                "title": "auditor"
                            },
                            "deadline":
                            {
                                "date": "2015-08-27 16:02:30",
                                "timezone_type": 3,
                                "timezone": "Europe/Paris"
                            }
                        }
                    ]
                },
                {
                    "id": 13,
                    "topic":
                    {
                        "id": 6,
                        "title": "topic1-1",
                        "slug": "topic1-1",
                        "description": "description 11"
                    },
                    "campaign":
                    {
                        "id": 5,
                        "customer":
                        {
                            "id": 1,
                            "name": "milan"
                        },
                        "name": "new name for campaign 5",
                        "guidelines": "new guidelines for campaign 5",
                        "status": 1,
                        "created":
                        {
                            "date": "2015-06-22 17:55:48",
                            "timezone_type": 3,
                            "timezone": "Europe/Paris"
                        },
                        "createdBy":
                        {
                            "id": 2,
                            "username": "zarkok@gmail.com",
                            "email": "zarkok@gmail.com",
                            "firstName": "zarko",
                            "lastName": "krneta",
                            "created":
                            {
                                "date": "-0001-11-30 00:00:00",
                                "timezone_type": 3,
                                "timezone": "Europe/Paris"
                            },
                            "modified":
                            {
                                "date": "2015-06-10 15:51:30",
                                "timezone_type": 3,
                                "timezone": "Europe/Paris"
                            }
                        }
                    },
                    "status":
                    {
                        "id": 1,
                        "description": "Creado"
                    },
                    "postType":
                    {
                        "id": 1,
                        "description": "facebook"
                    },
                    "assignedToId": "",
                    "requirements": "",
                    "title": "",
                    "body": "",
                    "link": "",
                    "tags": "",
                    "programmed": null,
                    "edited": null,
                    "editedById": "",
                    "created":
                    {
                        "date": "2015-06-22 17:55:48",
                        "timezone_type": 3,
                        "timezone": "Europe/Paris"
                    },
                    "createdById": 2,
                    "publishingDate": null,
                    "deadlines":
                    [
                        {
                            "id": 1,
                            "campaign":
                            {
                                "id": 5,
                                "customer":
                                {
                                    "id": 1,
                                    "name": "milan"
                                },
                                "name": "new name for campaign 5",
                                "guidelines": "new guidelines for campaign 5",
                                "status": 1,
                                "created":
                                {
                                    "date": "2015-06-22 17:55:48",
                                    "timezone_type": 3,
                                    "timezone": "Europe/Paris"
                                },
                                "createdBy":
                                {
                                    "id": 2,
                                    "username": "zarkok@gmail.com",
                                    "email": "zarkok@gmail.com",
                                    "firstName": "zarko",
                                    "lastName": "krneta",
                                    "created":
                                    {
                                        "date": "-0001-11-30 00:00:00",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    },
                                    "modified":
                                    {
                                        "date": "2015-06-10 15:51:30",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    }
                                }
                            },
                            "topic":
                            {
                                "id": 6,
                                "title": "topic1-1",
                                "slug": "topic1-1",
                                "description": "description 11"
                            },
                            "role":
                            {
                                "id": 2,
                                "title": "editor"
                            },
                            "deadline":
                            {
                                "date": "2015-07-29 16:01:41",
                                "timezone_type": 3,
                                "timezone": "Europe/Paris"
                            }
                        },
                        {
                            "id": 2,
                            "campaign":
                            {
                                "id": 5,
                                "customer":
                                {
                                    "id": 1,
                                    "name": "milan"
                                },
                                "name": "new name for campaign 5",
                                "guidelines": "new guidelines for campaign 5",
                                "status": 1,
                                "created":
                                {
                                    "date": "2015-06-22 17:55:48",
                                    "timezone_type": 3,
                                    "timezone": "Europe/Paris"
                                },
                                "createdBy":
                                {
                                    "id": 2,
                                    "username": "zarkok@gmail.com",
                                    "email": "zarkok@gmail.com",
                                    "firstName": "zarko",
                                    "lastName": "krneta",
                                    "created":
                                    {
                                        "date": "-0001-11-30 00:00:00",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    },
                                    "modified":
                                    {
                                        "date": "2015-06-10 15:51:30",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    }
                                }
                            },
                            "topic":
                            {
                                "id": 6,
                                "title": "topic1-1",
                                "slug": "topic1-1",
                                "description": "description 11"
                            },
                            "role":
                            {
                                "id": 3,
                                "title": "writer"
                            },
                            "deadline":
                            {
                                "date": "2015-08-04 16:02:21",
                                "timezone_type": 3,
                                "timezone": "Europe/Paris"
                            }
                        },
                        {
                            "id": 3,
                            "campaign":
                            {
                                "id": 5,
                                "customer":
                                {
                                    "id": 1,
                                    "name": "milan"
                                },
                                "name": "new name for campaign 5",
                                "guidelines": "new guidelines for campaign 5",
                                "status": 1,
                                "created":
                                {
                                    "date": "2015-06-22 17:55:48",
                                    "timezone_type": 3,
                                    "timezone": "Europe/Paris"
                                },
                                "createdBy":
                                {
                                    "id": 2,
                                    "username": "zarkok@gmail.com",
                                    "email": "zarkok@gmail.com",
                                    "firstName": "zarko",
                                    "lastName": "krneta",
                                    "created":
                                    {
                                        "date": "-0001-11-30 00:00:00",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    },
                                    "modified":
                                    {
                                        "date": "2015-06-10 15:51:30",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    }
                                }
                            },
                            "topic":
                            {
                                "id": 6,
                                "title": "topic1-1",
                                "slug": "topic1-1",
                                "description": "description 11"
                            },
                            "role":
                            {
                                "id": 4,
                                "title": "auditor"
                            },
                            "deadline":
                            {
                                "date": "2015-08-27 16:02:30",
                                "timezone_type": 3,
                                "timezone": "Europe/Paris"
                            }
                        }
                    ]
                }
            ]
        }

    ',
                        'headers' => 'X-User-Id: 1<br />
                                        User-Email: mm@mm.com<br />
                                        Bearer-Token: d1e43c95fd28e2c37b3af7ce602aaa81<br />
                                        Refresh-Token: 05620009b4aaeba18f9e176eff51a3bb'
                    ),
                ),
            ));
            $view->setTemplate('docs/docs/index');

            return $view;
        }

        public function postGetPostInformationAction()
        {
            $view = new ViewModel(array(
                'module' => 'Post Services',
                'service' => 'Get Post Information Service',
                'description' => 'This service will manage the request to get all the information related to the post - iformation about campaign, topic...',
                'method' => 'POST',
                'endpoint' => '/post/getinformation',
                'requestHeaders' => 'Content-Type: application/json; Bearer-Token: 0e1d4b16538f4c2986b2d2c4f5dfdae7',
                'params' => array(
                    array(
                        'name' => 'postId',
                        'required' => true,
                        'type' => 'integer',
                        'format' => '',
                    ),
                ),
                'exampleBody' => '[{  "postId": "10" }]',
                'responses' => array(
                    '200 OK',
                    '400 Bad Request',
                ),
                'exampleResponses' => array(
                    array(
                        'code' => '200',
                        'body' => '

        {
            "result":
            [
                {
                    "id": 10,
                    "topic":
                    {
                        "id": 6,
                        "title": "topic1-1",
                        "slug": "topic1-1",
                        "description": "description 11"
                    },
                    "campaign":
                    {
                        "id": 5,
                        "customer":
                        {
                            "id": 1,
                            "name": "test customer"
                        },
                        "name": "new name for campaign 5",
                        "guidelines": "new guidelines for campaign 5",
                        "status": 1,
                        "created":
                        {
                            "date": "2015-06-22 17:55:48",
                            "timezone_type": 3,
                            "timezone": "Europe/Paris"
                        },
                        "createdBy":
                        {
                            "id": 2,
                            "username": "zarkok@gmail.com",
                            "email": "zarkok@gmail.com",
                            "firstName": "zarko",
                            "lastName": "krneta",
                            "created":
                            {
                                "date": "-0001-11-30 00:00:00",
                                "timezone_type": 3,
                                "timezone": "Europe/Paris"
                            },
                            "modified":
                            {
                                "date": "2015-06-10 15:51:30",
                                "timezone_type": 3,
                                "timezone": "Europe/Paris"
                            }
                        }
                    },
                    "status":
                    {
                        "id": 1,
                        "description": "Creado"
                    },
                    "postType":
                    {
                        "id": 1,
                        "description": "facebook"
                    },
                    "assignedToId": "",
                    "requirements": "requirements",
                    "title": "title 1",
                    "body": "body 1",
                    "link": "",
                    "tags": "tag1, tag2, tag3",
                    "programmed": null,
                    "edited": null,
                    "editedById": "",
                    "created":
                    {
                        "date": "2015-06-22 17:55:48",
                        "timezone_type": 3,
                        "timezone": "Europe/Paris"
                    },
                    "createdById": 2,
                    "publishingDate":
                    {
                        "date": "2015-07-27 18:01:42",
                        "timezone_type": 3,
                        "timezone": "Europe/Paris"
                    }
                }
            ]
        }

    ',
                        'headers' => 'X-User-Id: 1<br />
                                        User-Email: mm@mm.com<br />
                                        Bearer-Token: d1e43c95fd28e2c37b3af7ce602aaa81<br />
                                        Refresh-Token: 05620009b4aaeba18f9e176eff51a3bb'
                    ),
                ),
            ));
            $view->setTemplate('docs/docs/index');

            return $view;
        }


        public function postAddPostCommentAction()
        {
            $view = new ViewModel(array(
                'module' => 'Post Services',
                'service' => 'Add comment to the post',
                'description' => '
                        <p style="font-size: 16px;">
                          This service will manage the request to add a new Post Comment.
                        </p>

                        <p style="font-size: 16px;">
                            It will return the added comment. userId can be optionally set if the user is different than currently logged in user.
                        </p>
                ',
                'method' => 'POST',
                'endpoint' => '/post/comment/',
                'requestHeaders' => 'Content-Type: application/json; Bearer-Token: 0e1d4b16538f4c2986b2d2c4f5dfdae7',
                'params' => array(
                    array(
                        'name' => 'postId',
                        'required' => true,
                        'type' => 'int',
                        'format' => '2',
                    ),
                    array(
                        'name' => 'comment',
                        'required' => true,
                        'type' => 'string',
                        'format' => 'Text representation of comment',
                    ),
                    array(
                        'name' => 'userId',
                        'required' => false,
                        'type' => 'int',
                        'format' => '2',
                    ),
                    array(
                        'name' => 'parentId',
                        'required' => false,
                        'type' => 'int',
                        'format' => '1',
                    ),


                ),
                'exampleBody' => '[{ "postId": "2", "comment":"One more reply", "parentId":"6"}]',
                'responses' => array(
                    '200 OK',
                    '400 Bad Request',
                    '404 Not Found -> invalid userId',
                    '404 Not Found -> invalid postId',
                ),
                'exampleResponses' => array(
                    array(
                        'code' => '200',
                        'body' => '{
      "result": [
        {
          "id": 7,
          "post_id": 2,
          "user_id": 2,
          "body": "One more reply",
          "created": {
            "date": "2015-06-24 15:43:31",
            "timezone_type": 3,
            "timezone": "Europe/Paris"
          }
        }
      ]
    }',
                        'headers' => 'Content-Type: application/json; charset=utf-8'
                    ),
                ),
            ));
            $view->setTemplate('docs/docs/index');

            return $view;
        }
        public function postGetPostListAction()
        {
            $view = new ViewModel(array(
                'module' => 'Post Services',
                'service' => 'Get Post List Service',
                'description' => 'This service returns the list of posts to populate the dashboard. Requires one parameter - Post Status.

    Returns only the posts where logged in user is assigned. It also returns all deadlines associated to the post campaign/topic',
                'method' => 'POST',
                'endpoint' => '/post/getpostlist/',
                'requestHeaders' => 'Content-Type: application/json; Bearer-Token: 0e1d4b16538f4c2986b2d2c4f5dfdae7',
                'params' => array(
                    array(
                        'name' => 'postStatus',
                        'required' => true,
                        'type' => 'string',
                        'format' => '',
                    ),

                ),
                'exampleBody' => '[{ "postStatus": "1"}]',
                'responses' => array(
                    '200 OK',
                    '400 Bad Request',
                    '400 Bad Request -> invalid JSON details',
                    '404 Not Found -> invalid post status',
                ),
                'exampleResponses' => array(
                    array(
                        'code' => '200',
                        'body' => '

        {
            "result":
            [
                {
                    "id": 10,
                    "topic":
                    {
                        "id": 6,
                        "title": "topic1-1",
                        "slug": "topic1-1",
                        "description": "description 11"
                    },
                    "campaign":
                    {
                        "id": 5,
                        "customer":
                        {
                            "id": 1,
                            "name": "test customer"
                        },
                        "name": "new name for campaign 5",
                        "guidelines": "new guidelines for campaign 5",
                        "status": 1,
                        "created":
                        {
                            "date": "2015-06-22 17:55:48",
                            "timezone_type": 3,
                            "timezone": "Europe/Paris"
                        },
                        "createdBy":
                        {
                            "id": 2,
                            "username": "zarkok@gmail.com",
                            "email": "zarkok@gmail.com",
                            "firstName": "zarko",
                            "lastName": "krneta",
                            "created":
                            {
                                "date": "-0001-11-30 00:00:00",
                                "timezone_type": 3,
                                "timezone": "Europe/Paris"
                            },
                            "modified":
                            {
                                "date": "2015-06-10 15:51:30",
                                "timezone_type": 3,
                                "timezone": "Europe/Paris"
                            }
                        }
                    },
                    "status":
                    {
                        "id": 1,
                        "description": "Creado"
                    },
                    "postType":
                    {
                        "id": 1,
                        "description": "facebook"
                    },
                    "assignedToId": "",
                    "requirements": "requirements",
                    "title": "title 1",
                    "body": "body 1",
                    "link": "",
                    "tags": "tag1, tag2, tag3",
                    "programmed": null,
                    "edited": null,
                    "editedById": "",
                    "created":
                    {
                        "date": "2015-06-22 17:55:48",
                        "timezone_type": 3,
                        "timezone": "Europe/Paris"
                    },
                    "createdById": 2,
                    "publishingDate":
                    {
                        "date": "2015-07-27 18:01:42",
                        "timezone_type": 3,
                        "timezone": "Europe/Paris"
                    },
                    "deadlines":
                    [
                        {
                            "id": 1,
                            "campaign":
                            {
                                "id": 5,
                                "customer":
                                {
                                    "id": 1,
                                    "name": "test customer"
                                },
                                "name": "new name for campaign 5",
                                "guidelines": "new guidelines for campaign 5",
                                "status": 1,
                                "created":
                                {
                                    "date": "2015-06-22 17:55:48",
                                    "timezone_type": 3,
                                    "timezone": "Europe/Paris"
                                },
                                "createdBy":
                                {
                                    "id": 2,
                                    "username": "zarkok@gmail.com",
                                    "email": "zarkok@gmail.com",
                                    "firstName": "zarko",
                                    "lastName": "krneta",
                                    "created":
                                    {
                                        "date": "-0001-11-30 00:00:00",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    },
                                    "modified":
                                    {
                                        "date": "2015-06-10 15:51:30",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    }
                                }
                            },
                            "topic":
                            {
                                "id": 6,
                                "title": "topic1-1",
                                "slug": "topic1-1",
                                "description": "description 11"
                            },
                            "role":
                            {
                                "id": 2,
                                "title": "editor"
                            },
                            "deadline":
                            {
                                "date": "2015-07-29 16:01:41",
                                "timezone_type": 3,
                                "timezone": "Europe/Paris"
                            }
                        },
                        {
                            "id": 2,
                            "campaign":
                            {
                                "id": 5,
                                "customer":
                                {
                                    "id": 1,
                                    "name": "test customer"
                                },
                                "name": "new name for campaign 5",
                                "guidelines": "new guidelines for campaign 5",
                                "status": 1,
                                "created":
                                {
                                    "date": "2015-06-22 17:55:48",
                                    "timezone_type": 3,
                                    "timezone": "Europe/Paris"
                                },
                                "createdBy":
                                {
                                    "id": 2,
                                    "username": "zarkok@gmail.com",
                                    "email": "zarkok@gmail.com",
                                    "firstName": "zarko",
                                    "lastName": "krneta",
                                    "created":
                                    {
                                        "date": "-0001-11-30 00:00:00",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    },
                                    "modified":
                                    {
                                        "date": "2015-06-10 15:51:30",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    }
                                }
                            },
                            "topic":
                            {
                                "id": 6,
                                "title": "topic1-1",
                                "slug": "topic1-1",
                                "description": "description 11"
                            },
                            "role":
                            {
                                "id": 3,
                                "title": "writer"
                            },
                            "deadline":
                            {
                                "date": "2015-08-04 16:02:21",
                                "timezone_type": 3,
                                "timezone": "Europe/Paris"
                            }
                        },
                        {
                            "id": 3,
                            "campaign":
                            {
                                "id": 5,
                                "customer":
                                {
                                    "id": 1,
                                    "name": "test customer"
                                },
                                "name": "new name for campaign 5",
                                "guidelines": "new guidelines for campaign 5",
                                "status": 1,
                                "created":
                                {
                                    "date": "2015-06-22 17:55:48",
                                    "timezone_type": 3,
                                    "timezone": "Europe/Paris"
                                },
                                "createdBy":
                                {
                                    "id": 2,
                                    "username": "zarkok@gmail.com",
                                    "email": "zarkok@gmail.com",
                                    "firstName": "zarko",
                                    "lastName": "krneta",
                                    "created":
                                    {
                                        "date": "-0001-11-30 00:00:00",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    },
                                    "modified":
                                    {
                                        "date": "2015-06-10 15:51:30",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    }
                                }
                            },
                            "topic":
                            {
                                "id": 6,
                                "title": "topic1-1",
                                "slug": "topic1-1",
                                "description": "description 11"
                            },
                            "role":
                            {
                                "id": 4,
                                "title": "auditor"
                            },
                            "deadline":
                            {
                                "date": "2015-08-27 16:02:30",
                                "timezone_type": 3,
                                "timezone": "Europe/Paris"
                            }
                        }
                    ]
                },
                {
                    "id": 11,
                    "topic":
                    {
                        "id": 6,
                        "title": "topic1-1",
                        "slug": "topic1-1",
                        "description": "description 11"
                    },
                    "campaign":
                    {
                        "id": 5,
                        "customer":
                        {
                            "id": 1,
                            "name": "test customer"
                        },
                        "name": "new name for campaign 5",
                        "guidelines": "new guidelines for campaign 5",
                        "status": 1,
                        "created":
                        {
                            "date": "2015-06-22 17:55:48",
                            "timezone_type": 3,
                            "timezone": "Europe/Paris"
                        },
                        "createdBy":
                        {
                            "id": 2,
                            "username": "zarkok@gmail.com",
                            "email": "zarkok@gmail.com",
                            "firstName": "zarko",
                            "lastName": "krneta",
                            "created":
                            {
                                "date": "-0001-11-30 00:00:00",
                                "timezone_type": 3,
                                "timezone": "Europe/Paris"
                            },
                            "modified":
                            {
                                "date": "2015-06-10 15:51:30",
                                "timezone_type": 3,
                                "timezone": "Europe/Paris"
                            }
                        }
                    },
                    "status":
                    {
                        "id": 1,
                        "description": "Creado"
                    },
                    "postType":
                    {
                        "id": 1,
                        "description": "facebook"
                    },
                    "assignedToId": "",
                    "requirements": "",
                    "title": "",
                    "body": "",
                    "link": "",
                    "tags": "",
                    "programmed": null,
                    "edited": null,
                    "editedById": "",
                    "created":
                    {
                        "date": "2015-06-22 17:55:48",
                        "timezone_type": 3,
                        "timezone": "Europe/Paris"
                    },
                    "createdById": 2,
                    "publishingDate": null,
                    "deadlines":
                    [
                        {
                            "id": 1,
                            "campaign":
                            {
                                "id": 5,
                                "customer":
                                {
                                    "id": 1,
                                    "name": "test customer"
                                },
                                "name": "new name for campaign 5",
                                "guidelines": "new guidelines for campaign 5",
                                "status": 1,
                                "created":
                                {
                                    "date": "2015-06-22 17:55:48",
                                    "timezone_type": 3,
                                    "timezone": "Europe/Paris"
                                },
                                "createdBy":
                                {
                                    "id": 2,
                                    "username": "zarkok@gmail.com",
                                    "email": "zarkok@gmail.com",
                                    "firstName": "zarko",
                                    "lastName": "krneta",
                                    "created":
                                    {
                                        "date": "-0001-11-30 00:00:00",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    },
                                    "modified":
                                    {
                                        "date": "2015-06-10 15:51:30",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    }
                                }
                            },
                            "topic":
                            {
                                "id": 6,
                                "title": "topic1-1",
                                "slug": "topic1-1",
                                "description": "description 11"
                            },
                            "role":
                            {
                                "id": 2,
                                "title": "editor"
                            },
                            "deadline":
                            {
                                "date": "2015-07-29 16:01:41",
                                "timezone_type": 3,
                                "timezone": "Europe/Paris"
                            }
                        },
                        {
                            "id": 2,
                            "campaign":
                            {
                                "id": 5,
                                "customer":
                                {
                                    "id": 1,
                                    "name": "test customer"
                                },
                                "name": "new name for campaign 5",
                                "guidelines": "new guidelines for campaign 5",
                                "status": 1,
                                "created":
                                {
                                    "date": "2015-06-22 17:55:48",
                                    "timezone_type": 3,
                                    "timezone": "Europe/Paris"
                                },
                                "createdBy":
                                {
                                    "id": 2,
                                    "username": "zarkok@gmail.com",
                                    "email": "zarkok@gmail.com",
                                    "firstName": "zarko",
                                    "lastName": "krneta",
                                    "created":
                                    {
                                        "date": "-0001-11-30 00:00:00",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    },
                                    "modified":
                                    {
                                        "date": "2015-06-10 15:51:30",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    }
                                }
                            },
                            "topic":
                            {
                                "id": 6,
                                "title": "topic1-1",
                                "slug": "topic1-1",
                                "description": "description 11"
                            },
                            "role":
                            {
                                "id": 3,
                                "title": "writer"
                            },
                            "deadline":
                            {
                                "date": "2015-08-04 16:02:21",
                                "timezone_type": 3,
                                "timezone": "Europe/Paris"
                            }
                        },
                        {
                            "id": 3,
                            "campaign":
                            {
                                "id": 5,
                                "customer":
                                {
                                    "id": 1,
                                    "name": "test customer"
                                },
                                "name": "new name for campaign 5",
                                "guidelines": "new guidelines for campaign 5",
                                "status": 1,
                                "created":
                                {
                                    "date": "2015-06-22 17:55:48",
                                    "timezone_type": 3,
                                    "timezone": "Europe/Paris"
                                },
                                "createdBy":
                                {
                                    "id": 2,
                                    "username": "zarkok@gmail.com",
                                    "email": "zarkok@gmail.com",
                                    "firstName": "zarko",
                                    "lastName": "krneta",
                                    "created":
                                    {
                                        "date": "-0001-11-30 00:00:00",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    },
                                    "modified":
                                    {
                                        "date": "2015-06-10 15:51:30",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    }
                                }
                            },
                            "topic":
                            {
                                "id": 6,
                                "title": "topic1-1",
                                "slug": "topic1-1",
                                "description": "description 11"
                            },
                            "role":
                            {
                                "id": 4,
                                "title": "auditor"
                            },
                            "deadline":
                            {
                                "date": "2015-08-27 16:02:30",
                                "timezone_type": 3,
                                "timezone": "Europe/Paris"
                            }
                        }
                    ]
                },
                {
                    "id": 12,
                    "topic":
                    {
                        "id": 6,
                        "title": "topic1-1",
                        "slug": "topic1-1",
                        "description": "description 11"
                    },
                    "campaign":
                    {
                        "id": 5,
                        "customer":
                        {
                            "id": 1,
                            "name": "test customer"
                        },
                        "name": "new name for campaign 5",
                        "guidelines": "new guidelines for campaign 5",
                        "status": 1,
                        "created":
                        {
                            "date": "2015-06-22 17:55:48",
                            "timezone_type": 3,
                            "timezone": "Europe/Paris"
                        },
                        "createdBy":
                        {
                            "id": 2,
                            "username": "zarkok@gmail.com",
                            "email": "zarkok@gmail.com",
                            "firstName": "zarko",
                            "lastName": "krneta",
                            "created":
                            {
                                "date": "-0001-11-30 00:00:00",
                                "timezone_type": 3,
                                "timezone": "Europe/Paris"
                            },
                            "modified":
                            {
                                "date": "2015-06-10 15:51:30",
                                "timezone_type": 3,
                                "timezone": "Europe/Paris"
                            }
                        }
                    },
                    "status":
                    {
                        "id": 1,
                        "description": "Creado"
                    },
                    "postType":
                    {
                        "id": 1,
                        "description": "facebook"
                    },
                    "assignedToId": "",
                    "requirements": "",
                    "title": "",
                    "body": "",
                    "link": "",
                    "tags": "",
                    "programmed": null,
                    "edited": null,
                    "editedById": "",
                    "created":
                    {
                        "date": "2015-06-22 17:55:48",
                        "timezone_type": 3,
                        "timezone": "Europe/Paris"
                    },
                    "createdById": 2,
                    "publishingDate": null,
                    "deadlines":
                    [
                        {
                            "id": 1,
                            "campaign":
                            {
                                "id": 5,
                                "customer":
                                {
                                    "id": 1,
                                    "name": "test customer"
                                },
                                "name": "new name for campaign 5",
                                "guidelines": "new guidelines for campaign 5",
                                "status": 1,
                                "created":
                                {
                                    "date": "2015-06-22 17:55:48",
                                    "timezone_type": 3,
                                    "timezone": "Europe/Paris"
                                },
                                "createdBy":
                                {
                                    "id": 2,
                                    "username": "zarkok@gmail.com",
                                    "email": "zarkok@gmail.com",
                                    "firstName": "zarko",
                                    "lastName": "krneta",
                                    "created":
                                    {
                                        "date": "-0001-11-30 00:00:00",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    },
                                    "modified":
                                    {
                                        "date": "2015-06-10 15:51:30",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    }
                                }
                            },
                            "topic":
                            {
                                "id": 6,
                                "title": "topic1-1",
                                "slug": "topic1-1",
                                "description": "description 11"
                            },
                            "role":
                            {
                                "id": 2,
                                "title": "editor"
                            },
                            "deadline":
                            {
                                "date": "2015-07-29 16:01:41",
                                "timezone_type": 3,
                                "timezone": "Europe/Paris"
                            }
                        },
                        {
                            "id": 2,
                            "campaign":
                            {
                                "id": 5,
                                "customer":
                                {
                                    "id": 1,
                                    "name": "test customer"
                                },
                                "name": "new name for campaign 5",
                                "guidelines": "new guidelines for campaign 5",
                                "status": 1,
                                "created":
                                {
                                    "date": "2015-06-22 17:55:48",
                                    "timezone_type": 3,
                                    "timezone": "Europe/Paris"
                                },
                                "createdBy":
                                {
                                    "id": 2,
                                    "username": "zarkok@gmail.com",
                                    "email": "zarkok@gmail.com",
                                    "firstName": "zarko",
                                    "lastName": "krneta",
                                    "created":
                                    {
                                        "date": "-0001-11-30 00:00:00",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    },
                                    "modified":
                                    {
                                        "date": "2015-06-10 15:51:30",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    }
                                }
                            },
                            "topic":
                            {
                                "id": 6,
                                "title": "topic1-1",
                                "slug": "topic1-1",
                                "description": "description 11"
                            },
                            "role":
                            {
                                "id": 3,
                                "title": "writer"
                            },
                            "deadline":
                            {
                                "date": "2015-08-04 16:02:21",
                                "timezone_type": 3,
                                "timezone": "Europe/Paris"
                            }
                        },
                        {
                            "id": 3,
                            "campaign":
                            {
                                "id": 5,
                                "customer":
                                {
                                    "id": 1,
                                    "name": "test customer"
                                },
                                "name": "new name for campaign 5",
                                "guidelines": "new guidelines for campaign 5",
                                "status": 1,
                                "created":
                                {
                                    "date": "2015-06-22 17:55:48",
                                    "timezone_type": 3,
                                    "timezone": "Europe/Paris"
                                },
                                "createdBy":
                                {
                                    "id": 2,
                                    "username": "zarkok@gmail.com",
                                    "email": "zarkok@gmail.com",
                                    "firstName": "zarko",
                                    "lastName": "krneta",
                                    "created":
                                    {
                                        "date": "-0001-11-30 00:00:00",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    },
                                    "modified":
                                    {
                                        "date": "2015-06-10 15:51:30",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    }
                                }
                            },
                            "topic":
                            {
                                "id": 6,
                                "title": "topic1-1",
                                "slug": "topic1-1",
                                "description": "description 11"
                            },
                            "role":
                            {
                                "id": 4,
                                "title": "auditor"
                            },
                            "deadline":
                            {
                                "date": "2015-08-27 16:02:30",
                                "timezone_type": 3,
                                "timezone": "Europe/Paris"
                            }
                        }
                    ]
                },
                {
                    "id": 13,
                    "topic":
                    {
                        "id": 6,
                        "title": "topic1-1",
                        "slug": "topic1-1",
                        "description": "description 11"
                    },
                    "campaign":
                    {
                        "id": 5,
                        "customer":
                        {
                            "id": 1,
                            "name": "test customer"
                        },
                        "name": "new name for campaign 5",
                        "guidelines": "new guidelines for campaign 5",
                        "status": 1,
                        "created":
                        {
                            "date": "2015-06-22 17:55:48",
                            "timezone_type": 3,
                            "timezone": "Europe/Paris"
                        },
                        "createdBy":
                        {
                            "id": 2,
                            "username": "zarkok@gmail.com",
                            "email": "zarkok@gmail.com",
                            "firstName": "zarko",
                            "lastName": "krneta",
                            "created":
                            {
                                "date": "-0001-11-30 00:00:00",
                                "timezone_type": 3,
                                "timezone": "Europe/Paris"
                            },
                            "modified":
                            {
                                "date": "2015-06-10 15:51:30",
                                "timezone_type": 3,
                                "timezone": "Europe/Paris"
                            }
                        }
                    },
                    "status":
                    {
                        "id": 1,
                        "description": "Creado"
                    },
                    "postType":
                    {
                        "id": 1,
                        "description": "facebook"
                    },
                    "assignedToId": "",
                    "requirements": "",
                    "title": "",
                    "body": "",
                    "link": "",
                    "tags": "",
                    "programmed": null,
                    "edited": null,
                    "editedById": "",
                    "created":
                    {
                        "date": "2015-06-22 17:55:48",
                        "timezone_type": 3,
                        "timezone": "Europe/Paris"
                    },
                    "createdById": 2,
                    "publishingDate": null,
                    "deadlines":
                    [
                        {
                            "id": 1,
                            "campaign":
                            {
                                "id": 5,
                                "customer":
                                {
                                    "id": 1,
                                    "name": "test customer"
                                },
                                "name": "new name for campaign 5",
                                "guidelines": "new guidelines for campaign 5",
                                "status": 1,
                                "created":
                                {
                                    "date": "2015-06-22 17:55:48",
                                    "timezone_type": 3,
                                    "timezone": "Europe/Paris"
                                },
                                "createdBy":
                                {
                                    "id": 2,
                                    "username": "zarkok@gmail.com",
                                    "email": "zarkok@gmail.com",
                                    "firstName": "zarko",
                                    "lastName": "krneta",
                                    "created":
                                    {
                                        "date": "-0001-11-30 00:00:00",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    },
                                    "modified":
                                    {
                                        "date": "2015-06-10 15:51:30",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    }
                                }
                            },
                            "topic":
                            {
                                "id": 6,
                                "title": "topic1-1",
                                "slug": "topic1-1",
                                "description": "description 11"
                            },
                            "role":
                            {
                                "id": 2,
                                "title": "editor"
                            },
                            "deadline":
                            {
                                "date": "2015-07-29 16:01:41",
                                "timezone_type": 3,
                                "timezone": "Europe/Paris"
                            }
                        },
                        {
                            "id": 2,
                            "campaign":
                            {
                                "id": 5,
                                "customer":
                                {
                                    "id": 1,
                                    "name": "test customer"
                                },
                                "name": "new name for campaign 5",
                                "guidelines": "new guidelines for campaign 5",
                                "status": 1,
                                "created":
                                {
                                    "date": "2015-06-22 17:55:48",
                                    "timezone_type": 3,
                                    "timezone": "Europe/Paris"
                                },
                                "createdBy":
                                {
                                    "id": 2,
                                    "username": "zarkok@gmail.com",
                                    "email": "zarkok@gmail.com",
                                    "firstName": "zarko",
                                    "lastName": "krneta",
                                    "created":
                                    {
                                        "date": "-0001-11-30 00:00:00",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    },
                                    "modified":
                                    {
                                        "date": "2015-06-10 15:51:30",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    }
                                }
                            },
                            "topic":
                            {
                                "id": 6,
                                "title": "topic1-1",
                                "slug": "topic1-1",
                                "description": "description 11"
                            },
                            "role":
                            {
                                "id": 3,
                                "title": "writer"
                            },
                            "deadline":
                            {
                                "date": "2015-08-04 16:02:21",
                                "timezone_type": 3,
                                "timezone": "Europe/Paris"
                            }
                        },
                        {
                            "id": 3,
                            "campaign":
                            {
                                "id": 5,
                                "customer":
                                {
                                    "id": 1,
                                    "name": "test customer"
                                },
                                "name": "new name for campaign 5",
                                "guidelines": "new guidelines for campaign 5",
                                "status": 1,
                                "created":
                                {
                                    "date": "2015-06-22 17:55:48",
                                    "timezone_type": 3,
                                    "timezone": "Europe/Paris"
                                },
                                "createdBy":
                                {
                                    "id": 2,
                                    "username": "zarkok@gmail.com",
                                    "email": "zarkok@gmail.com",
                                    "firstName": "zarko",
                                    "lastName": "krneta",
                                    "created":
                                    {
                                        "date": "-0001-11-30 00:00:00",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    },
                                    "modified":
                                    {
                                        "date": "2015-06-10 15:51:30",
                                        "timezone_type": 3,
                                        "timezone": "Europe/Paris"
                                    }
                                }
                            },
                            "topic":
                            {
                                "id": 6,
                                "title": "topic1-1",
                                "slug": "topic1-1",
                                "description": "description 11"
                            },
                            "role":
                            {
                                "id": 4,
                                "title": "auditor"
                            },
                            "deadline":
                            {
                                "date": "2015-08-27 16:02:30",
                                "timezone_type": 3,
                                "timezone": "Europe/Paris"
                            }
                        }
                    ]
                }
            ]
        }

    ',
                        'headers' => 'Content-Type: application/json; charset=utf-8'
                    ),
                ),
            ));
            $view->setTemplate('docs/docs/index');

            return $view;
        }


        public function postGetPostCommentAction()
        {
            $view = new ViewModel(array(
                'module' => 'Post Services',
                'service' => 'Get the list of comments for the post',
                'description' => '
                        <p style="font-size: 16px;">
                          This service will manage the request to get the list of comments for the post.
                        </p>

                        <p style="font-size: 16px;">
                            It will return json formatted list of comments.
                        </p>
                ',
                'method' => 'POST',
                'endpoint' => '/post/getcomment/',
                'requestHeaders' => 'Content-Type: application/json; Bearer-Token: 0e1d4b16538f4c2986b2d2c4f5dfdae7',
                'params' => array(
                    array(
                        'name' => 'postId',
                        'required' => true,
                        'type' => 'int',
                        'format' => '2',
                    ),


                ),
                'exampleBody' => '[{ "postId": "2"}]',
                'responses' => array(
                    '200 OK',
                    '400 Bad Request',
                    '404 Not Found -> invalid userId',
                    '404 Not Found -> invalid postId',
                ),
                'exampleResponses' => array(
                    array(
                        'code' => '200',
                        'body' => '{
      "result": [
        {
          "id": 2,
          "post_id": 2,
          "user_id": 2,
          "body": "Comment for post 2",
          "created": {
            "date": "2015-06-22 20:45:01",
            "timezone_type": 3,
            "timezone": "Europe/Paris"
          },
          "replies": [
            {
              "id": 4,
              "post_id": 2,
              "user_id": 2,
              "body": "Child of comment 2",
              "created": {
                "date": "2015-06-22 20:46:48",
                "timezone_type": 3,
                "timezone": "Europe/Paris"
              }
            }
          ]
        },
        {
          "id": 5,
          "post_id": 2,
          "user_id": 2,
          "body": "Last comment for post 2",
          "created": {
            "date": "2015-06-22 20:47:28",
            "timezone_type": 3,
            "timezone": "Europe/Paris"
          }
        }
      ]
    }',
                        'headers' => 'Content-Type: application/json; charset=utf-8'
                    ),
                ),
            ));
            $view->setTemplate('docs/docs/index');

            return $view;
        }



        public function postGetPostWorkflowAction()
        {
            $view = new ViewModel(array(
                'module' => 'Post Services',
                'service' => 'Get the post workflow',
                'description' => '
                        <p style="font-size: 16px;">
                          This service will manage the request to get the post workflow.
                        </p>

                        <p style="font-size: 16px;">
                            It will return json formatted list.
                        </p>
                ',
                'method' => 'POST',
                'endpoint' => '/post/getworkflow/',
                'requestHeaders' => 'Content-Type: application/json; Bearer-Token: 0e1d4b16538f4c2986b2d2c4f5dfdae7',
                'params' => array(
                    array(
                        'name' => 'postId',
                        'required' => true,
                        'type' => 'int',
                        'format' => '2',
                    ),


                ),
                'exampleBody' => '[{ "postId": "2"}]',
                'responses' => array(
                    '200 OK',
                    '400 Bad Request',
                    '404 Not Found -> invalid postId',
                ),
                'exampleResponses' => array(
                    array(
                        'code' => '200',
                        'body' => '{
      "result": [
        {
          "id": 2,
          "post_id": 2,
          "user_id": 2,
          "body": "Comment for post 2",
          "created": {
            "date": "2015-06-22 20:45:01",
            "timezone_type": 3,
            "timezone": "Europe/Paris"
          },
          "replies": [
            {
              "id": 4,
              "post_id": 2,
              "user_id": 2,
              "body": "Child of comment 2",
              "created": {
                "date": "2015-06-22 20:46:48",
                "timezone_type": 3,
                "timezone": "Europe/Paris"
              }
            }
          ]
        },
        {
          "id": 5,
          "post_id": 2,
          "user_id": 2,
          "body": "Last comment for post 2",
          "created": {
            "date": "2015-06-22 20:47:28",
            "timezone_type": 3,
            "timezone": "Europe/Paris"
          }
        }
      ]
    }',
                        'headers' => 'Content-Type: application/json; charset=utf-8'
                    ),
                ),
            ));
            $view->setTemplate('docs/docs/index');

            return $view;
        }





        public function postAddPostImageAction()
        {
            $view = new ViewModel(array(
                'text' => '
                        <h2>Post Services</h2>
                        <h3>Add Post Image Service</h3>
                        <p style="font-size: 16px;">
                            This service will manage the request to add a new Post Image.
                        </p>
                ',
            ));
            $view->setTemplate('docs/docs/index');

            return $view;
        }

        public function postRemovePostImageAction()
        {
            $view = new ViewModel(array(
                'text' => '
                        <h2>Post Services</h2>
                        <h3>Remove Post Image Service</h3>
                        <p style="font-size: 16px;">
                            This service will manage the request to remove a Post Image.
                        </p>
                ',
            ));
            $view->setTemplate('docs/docs/index');

            return $view;
        }

        public function schedulePostAction()
        {
            $view = new ViewModel(array(
                'module' => 'Post Services',
                'service' => 'Schedule Post Service',
                'description' => '
                        <p style="font-size: 16px;">
                            This service will manage the request to schedule a new Post in order to be published in
                            Facebook or Twitter. It will populate the posts_schedule table in order to be read
                            for the /post/schedule/cron script.
                        </p>
                        <p style="font-size: 16px;">
                            It will receive just three mandatory parameters, and return the
                            scheduled post if it was successful or an array containing the errors.
                        </p>
                ',
                'method' => 'POST',
                'endpoint' => '/post/schedule/',
                'requestHeaders' => 'Content-Type: application/json; Bearer-Token: 0e1d4b16538f4c2986b2d2c4f5dfdae7',
                'params' => array(
                    array(
                        'name' => 'postId',
                        'required' => true,
                        'type' => 'integer',
                        'format' => 'Valid postId',
                    ),
                    array(
                        'name' => 'when',
                        'required' => true,
                        'type' => 'datetime',
                        'format' => 'Date and time for the post to be published (eg "2015-08-20 15:00:45")',
                    ),
                    array(
                        'name' => 'service',
                        'required' => true,
                        'type' => 'string',
                        'format' => 'Name of the social network to publish on. Valid values: "facebook", "twitter"',
                    ),
                ),
                'exampleBody' => '[{  "usersIds": "1,2", "postsIds": "1, 3, 4" }]',
                'responses' => array(
                    '200 OK',
                    '400 Bad Request',
                    '404 Not Found -> invalid service',
                    '404 Not Found -> invalid postId',
                ),
                'exampleResponses' => array(
                    array(
                        'code' => '200',
                        'body' => '{"result":{"id":1,"topic":{"id":1,"title":"Topic","slug":"topic","description":"Descripcion del topico"},"campaign":{"id":1,"customer":{"id":1,"name":"test customer"},"name":"campaign name","guidelines":"campaign guidelines","status":1,"created":{"date":"2015-08-10 00:00:00","timezone_type":3,"timezone":"America\/Buenos_Aires"},"createdBy":{"id":1,"username":"admin","email":"mm@mm.com","firstName":"matias","lastName":"martin","created":{"date":"-0001-11-30 00:00:00","timezone_type":3,"timezone":"America\/Buenos_Aires"},"modified":{"date":"-0001-11-30 00:00:00","timezone_type":3,"timezone":"America\/Buenos_Aires"}}},"status":{"id":1,"description":"Creado"},"postType":{"id":1,"description":"facebook"},"assignedToId":1,"requirements":"requirementsdelpost}","title":"tesrcerrrrreltitledelposttwittsegundoooer","body":"bodydelposttwitter","link":"elinkll","tags":"tag, tags","programmed":{"date":"-0001-11-30 00:00:00","timezone_type":3,"timezone":"America\/Buenos_Aires"},"edited":{"date":"-0001-11-30 00:00:00","timezone_type":3,"timezone":"America\/Buenos_Aires"},"editedById":"","created":{"date":"2015-08-22 00:00:00","timezone_type":3,"timezone":"America\/Buenos_Aires"},"createdById":1,"publishingDate":{"date":"-0001-11-30 00:00:00","timezone_type":3,"timezone":"America\/Buenos_Aires"},"draftFlag":0,"rejectedFlag":0}}',
                        'headers' => 'Content-Type: application/json; charset=utf-8'
                    ),
                ),
            ));
            $view->setTemplate('docs/docs/index');

            return $view;
        }

        public function getFacebookAccountsAction()
        {
            $view = new ViewModel(array(
                'module' => 'Post Services',
                'service' => 'Get Facebook\'s Accounts Service',
                'description' => '
                        <p style="font-size: 16px;">
                            This service will return all the accounts for a Facebook User, based on the received access token.
                        </p>
                ',
                'method' => 'POST',
                'endpoint' => '/post/schedule/getFacebookAccounts',
                'requestHeaders' => 'Content-Type: application/json; Bearer-Token: 0e1d4b16538f4c2986b2d2c4f5dfdae7',
                'params' => array(
                    array(
                        'name' => 'token',
                        'required' => true,
                        'type' => 'string',
                        'format' => 'Valid Facebook\'s Access Token',
                    ),
                ),
                'exampleBody' => '[{  "token": "SiGJIT1ZAIarwoYafLaMEsntEznZAlZBdliWd6nsK1TqUXjtbL13qd1SZCPai5hNBWEztuxRZAdf5YZD" }]',
                'responses' => array(
                    '200 OK',
                    '400 Bad Request',
                    '404 Not Found -> invalid service',
                    '404 Not Found -> invalid postId',
                ),
                'exampleResponses' => array(
                    array(
                        'code' => '200',
                        'body' => '{"result":{"accounts":[{"id":"984854858203871","name":"My branddddd","token":"CAAW9UTCWqnMBAObqJ8a4daxwnqOuJ27GXHFQ3Jj3dZCr4OfBd6JPMEG5FCWRzkZCSbZAZAw7rcVJnGOZC6ULnzJkFhz4IR14vDTkWZChL3ZCXgw2TAc2tHq5yyrji40ZAzBz3zgO8a1TcHoag27kMpdBQWoUNoMgGvY7XStzIaVFcfJXPfnVctcZB"},{"id":"177776302554314","name":"El negocio de mi tia","token":"CAAW9UTCWqnMBABxperojceLbqITv1vw6seHWZAavZBKbB0EfCZCdltzvnJVo8GbPbgipJeoI9o7TaPLwGAdLSBF5DzLau1ZCBZAdV0Sjx18GpLv4VlK4ifbhlQnCJUhqhOADZBNxZCE8qFg3CFxr6NTqf3zmuApJuEJwvv4XJGcYOeAJRfzvwP7"}]}}',
                        'headers' => 'Content-Type: application/json; charset=utf-8'
                    ),
                ),
            ));
            $view->setTemplate('docs/docs/index');

            return $view;
        }

        public function scheduleFacebookPostAction()
        {
            $view = new ViewModel(array(
                'module' => 'Post Services',
                'service' => 'Schedule Facebook Page Post Service',
                'description' => '
                        <p style="font-size: 16px;">
                            This service will manage the request to schedule a new Post on behalf of a specific Page, through a Facebook\'s
                            related User Account. In order to do it, you will need to get an access token from the <a href="facebook/getAccounts">Get Facebook\'s
                            Accounts Service</a>. It will store some publishing info (log purposes), and will return you in case of
                            succeed, the id of the generated scheduled post.
                        </p>
                ',
                'method' => 'POST',
                'endpoint' => '/post/schedule/facebook',
                'requestHeaders' => 'Content-Type: application/json; Bearer-Token: 0e1d4b16538f4c2986b2d2c4f5dfdae7',
                'params' => array(
                    array(
                        'name' => 'postId',
                        'required' => true,
                        'type' => 'integer',
                        'format' => 'Valid postId',
                    ),
                    array(
                        'name' => 'when',
                        'required' => true,
                        'type' => 'datetime',
                        'format' => 'Date and time for the post to be published (eg "2015-09-20 15:00:45"). It '
                        . 'should be greater than 10 minutes, and less than six months.',
                    ),
                    array(
                        'name' => 'token',
                        'required' => true,
                        'type' => 'string',
                        'format' => 'Token you already get from Get Facebook\'s Accounts Service',
                    ),
                ),
                'exampleBody' => '[{ "postId": "1",  "when": "2015-09-06 16:42",  "token": "9SiGJIT1ZAIarwoYafLaMEsntEznZAlZBdliWd6nsK1TqUXjtbL13qd1SZCPai5hNBWEztuxRZAdf5YZD" }]',
                'responses' => array(
                    '200 OK',
                    '400 Bad Request',
                    '404 Not Found -> invalid token',
                ),
                'exampleResponses' => array(
                    array(
                        'code' => '200',
                        'body' => '{"result":{"id":"177776302554314_179017699096841"}}',
                        'headers' => 'Content-Type: application/json; charset=utf-8'
                    ),
                ),
            ));
            $view->setTemplate('docs/docs/index');

            return $view;
        }

        public function postAssignToAction()
        {
            $view = new ViewModel(array(
                'module' => 'Post Services',
                'service' => 'Assign Post To Service',
                'description' => '
                        <p style="font-size: 16px;">
                            This service will manage the request to assign a Post/s to a specific/s User/s.
                        </p>
                        <p style="font-size: 16px;">
                            This will work in 2 different ways depending on the data received:<br />
                            * If the service receives just ONE userId, it will assign unequivocally all the posts to these User,
                            and will remove ALL the existing multiple assignments for these posts.<br />
                            * If the service receives more than one userId, it will make a multiple assignment of these posts for
                            these users, and will remove the unique assignment record.
                        </p>
                        <p style="font-size: 16px;">
                            It will return the assigned posts.
                        </p>
                ',
                'method' => 'POST',
                'endpoint' => '/post/assign/',
                'requestHeaders' => 'Content-Type: application/json; Bearer-Token: 0e1d4b16538f4c2986b2d2c4f5dfdae7',
                'params' => array(
                    array(
                        'name' => 'usersIds',
                        'required' => true,
                        'type' => 'string',
                        'format' => 'Comma separated userId values',
                    ),
                    array(
                        'name' => 'postsIds',
                        'required' => true,
                        'type' => 'string',
                        'format' => 'Comma separated postId values',
                    ),
                ),
                'exampleBody' => '[{  "usersIds": "1,2", "postsIds": "1, 3, 4" }]',
                'responses' => array(
                    '200 OK',
                    '400 Bad Request',
                    '404 Not Found -> invalid userId',
                    '404 Not Found -> invalid postId',
                ),
                'exampleResponses' => array(
                    array(
                        'code' => '200',
                        'body' => '{"result":[{"id":1,"topic":"chau","status":{"id":1,"description":"Creado"},"assignedToId":"","title":"","body":"","link":"","tags":"","programmed":null,"edited":null,"editedById":"","created":{"date":"2015-05-17 00:29:57","timezone_type":3,"timezone":"America\/Buenos_Aires"},"createdById":1},{"id":3,"topic":"chau","status":{"id":1,"description":"Creado"},"assignedToId":"","title":"","body":"","link":"","tags":"","programmed":null,"edited":null,"editedById":"","created":{"date":"2015-05-17 00:29:57","timezone_type":3,"timezone":"America\/Buenos_Aires"},"createdById":1},{"id":4,"topic":"chau","status":{"id":1,"description":"Creado"},"assignedToId":"","title":"","body":"","link":"","tags":"","programmed":null,"edited":null,"editedById":"","created":{"date":"2015-05-17 00:29:57","timezone_type":3,"timezone":"America\/Buenos_Aires"},"createdById":1}]}',
                        'headers' => 'Content-Type: application/json; charset=utf-8'
                    ),
                ),
            ));
            $view->setTemplate('docs/docs/index');

            return $view;
        }

        public function postAssignTopicToAction()
        {
            $view = new ViewModel(array(
                'module' => 'Post Services',
                'service' => 'Assign Topic To Service',
                'description' => '
                        <p style="font-size: 16px;">
                            This service will manage the request to assign a Topic/s to a specific/s User/s.
                        </p>

                        <p style="font-size: 16px;">
                            It will return the assigned topics.
                        </p>
                ',
                'method' => 'POST',
                'endpoint' => '/topic/assign/',
                'requestHeaders' => 'Content-Type: application/json; Bearer-Token: 0e1d4b16538f4c2986b2d2c4f5dfdae7',
                'params' => array(
                    array(
                        'name' => 'usersIds',
                        'required' => true,
                        'type' => 'string',
                        'format' => 'Comma separated userId values',
                    ),
                    array(
                        'name' => 'topicsIds',
                        'required' => true,
                        'type' => 'string',
                        'format' => 'Comma separated postId values',
                    ),
                ),
                'exampleBody' => '[{  "usersIds": "1,2", "topicsIds": "1, 3, 4" }]',
                'responses' => array(
                    '200 OK',
                    '400 Bad Request',
                    '404 Not Found -> invalid userId',
                    '404 Not Found -> invalid topicId',
                ),
                'exampleResponses' => array(
                    array(
                        'code' => '200',
                        'body' => '{"result":
        [
            {
                "id": 2,
                "title": "2",
                "slug": "topic 2",
                "description": "Descripcion del topico 2"
            },
            {
                "id": 6,
                "title": "topic1-1",
                "slug": "topic1-1",
                "description": null
            },
            {
                "id": 7,
                "title": "topic2-1",
                "slug": "topic2-1",
                "description": null
            }
        ]}',
                        'headers' => 'Content-Type: application/json; charset=utf-8'
                    ),
                ),
            ));
            $view->setTemplate('docs/docs/index');

            return $view;
        }


        public function campaignAction()
        {
            $view = new ViewModel(array(
                'text' => '
                        <h2>Campaign Services</h2>
                        <p style="font-size: 16px;">
                            This module takes care of all Campaign related operations.
                        </p>
                        <ul>
                            <li><a href="campaign/add">Add Campaign Service</a></li>
                            <li><a href="campaign/deadlines">Set Campaign Deadlines Service</a></li>
                            <li><a href="campaign/update">Update Campaign Service</a></li>
                            <li><a href="campaign/delete">Delete Campaign Service</a></li>
                            <li><a href="campaign/list">List Campaign Service</a></li>
                        </ul>
                ',
            ));
            $view->setTemplate('docs/docs/index');

            return $view;
        }

        public function blogpostAction()
        {
            $view = new ViewModel(array(
                'text' => '
                        <h2>Blog Post Services</h2>
                        <p style="font-size: 16px;">
                            This module takes care of all Blog Post related operations.
                        </p>
                        <ul>
                            <li><a href="blog-post/add/">Add Blog Post Service</a></li>
                            <li><a href="blog-post/get/">Get Blog Post Service</a></li>
                            <li><a href="blog-post/update/">Update Blog Post Service</a></li>
                            <li><a href="blog-post/update/active">Active Blog Post Service</a></li>
                            <li><a href="blog-post/remove/">Delete Blog Post Service</a></li>
                            <li><a href="blog-post/search/">Search Blog Post Service</a></li>
                            <li><a href="blog-post/top-author/">Top Author Blog Post Service</a></li>
                            <li><a href="blog-post/top-post/">Top Blog Post Service</a></li>
                            <li><a href="blog-post/top-topic/">Top Topic Blog Post Service</a></li>
                            <li><a href="blog-post/analytics/">Analytics Blog Post Service</a></li>
                            <li><a href="blog-post/create/">Create Table Blog Post Service</a></li>
                        </ul>
                ',
            ));
            $view->setTemplate('docs/docs/index');

            return $view;
        }

        public function blogAction()
        {
            $view = new ViewModel(array(
                'text' => '
                        <h2>Blog Post Services</h2>
                        <p style="font-size: 16px;">
                            This module takes care of all Blog related operations.
                        </p>
                        <ul>
                            <li><a href="blog/add/">Add Blog Service</a></li>
                            <li><a href="blog/get/">Get Blog Service</a></li>
                            <li><a href="blog/update/">Update Blog Service</a></li>
                            <li><a href="blog/remove/">Delete Blog Service</a></li>
                            <li><a href="blog/search/">Search Blog By User Service</a></li>
                        </ul>
                ',
            ));
            $view->setTemplate('docs/docs/index');

            return $view;
        }

        public function blogpostAddAction()
        {
            $view = new ViewModel(array(
                'module' => 'Blog Post Services',
                'service' => 'Add Blog Post Service',
                'description' => 'This service will manage the request to create a new
                    Blog Post. The request contain the details of the blog post.',
                'method' => 'POST',
                'endpoint' => '/blog-post/add/',
                'requestHeaders' => 'Content-Type: application/json; Bearer-Token: 0e1d4b16538f4c2986b2d2c4f5dfdae7',
                'params' => array(
                    array(
                        'name' => 'key_api',
                        'required' => true,
                        'type' => 'string',
                        'format' => '',
                    ),
                    array(
                        'name' => 'post_id',
                        'required' => true,
                        'type' => 'integer',
                        'format' => '',
                    ),
                    array(
                        'name' => 'title',
                        'required' => true,
                        'type' => 'string',
                        'format' => '',
                    ),
                    array(
                        'name' => 'date_publishing',
                        'required' => true,
                        'type' => 'date',
                        'format' => '',
                    ),
                    array(
                        'name' => 'url',
                        'required' => true,
                        'type' => 'string',
                        'format' => '',
                    ),
                    array(
                        'name' => 'author',
                        'required' => true,
                        'type' => 'string',
                        'format' => '',
                    ),
                    array(
                        'name' => 'category',
                        'required' => true,
                        'type' => 'string',
                        'format' => '',
                    ),
                    array(
                        'name' => 'avg_session_duration',
                        'required' => true,
                        'type' => 'string',
                        'format' => '',
                    ),
                    array(
                        'name' => 'total_social_count',
                        'required' => true,
                        'type' => 'integer',
                        'format' => '',
                    ),
                    array(
                        'name' => 'view',
                        'required' => true,
                        'type' => 'integer',
                        'format' => '',
                    ),
                    array(
                        'name' => 'social_count_facebook',
                        'required' => true,
                        'type' => 'integer',
                        'format' => '',
                    ),
                    array(
                        'name' => 'social_count_twitter',
                        'required' => true,
                        'type' => 'integer',
                        'format' => '',
                    ),
                    array(
                        'name' => 'social_count_linkedin',
                        'required' => true,
                        'type' => 'integer',
                        'format' => '',
                    ),
    //                array(
    //                    'name' => 'social_count_reddit',
    //                    'required' => true,
    //                    'type' => 'integer',
    //                    'format' => '',
    //                ),
    //                array(
    //                    'name' => 'social_count_stumble_upon',
    //                    'required' => true,
    //                    'type' => 'integer',
    //                    'format' => '',
    //                ),
                    array(
                        'name' => 'social_count_google_plus',
                        'required' => true,
                        'type' => 'integer',
                        'format' => '',
                    ),
    //                array(
    //                    'name' => 'social_count_pinterest',
    //                    'required' => true,
    //                    'type' => 'integer',
    //                    'format' => '',
    //                ),
    //                array(
    //                    'name' => 'social_count_flattr',
    //                    'required' => true,
    //                    'type' => 'integer',
    //                    'format' => '',
    //                ),
    //                array(
    //                    'name' => 'social_count_XING',
    //                    'required' => true,
    //                    'type' => 'integer',
    //                    'format' => '',
    //                ),
//                    array(
//                        'name' => 'sync_date',
//                        'required' => true,
//                        'type' => 'datetime',
//                        'format' => '',
//                    ),
                    array(
                        'name' => 'words',
                        'required' => true,
                        'type' => 'integer',
                        'format' => '',
                    ),
                    array(
                        'name' => 'created',
                        'required' => true,
                        'type' => 'integer',
                        'format' => '',
                    ),
                ),
                'exampleBody' => '[{"key_api":"1-1","post_id": "2592","title": "Testing post","date_publishing": "2015-11-16","url": "www.test.com/post/test.php","author": "The Author","category": "Category Post","avg_session_duration": "00:00:00","total_social_count": "2","view": "2","social_count_facebook": "2","social_count_twitter": "0","social_count_linkedin": "0","social_count_google_plus": "0","words": "59","created": "2015-11-16 21:20:25"}]',
                'responses' => array(
                    '200 OK',
                    '400 Bad Request',
                    '400 Bad Request -> invalid JSON details',
                    '404 Not Found -> Blog Post already exists whith this update',
                ),
                'exampleResponses' => array(
                    array(
                        'code' => '200',
                        'body' => '{"result":[{"id":4,key_api":"1-1","post_id": "2592","title": "Testing post","date_publishing": {"date":"2015-11-16 00:00:00","timezone_type":3,"timezone":"America\/Buenos_Aires"},"url": "www.test.com/post/test.php","author": "The Author","category": "Category Post","avg_session_duration": "00:00:00","total_social_count": "2","view": "2","social_count_facebook": "2","social_count_twitter": "0","social_count_linkedin": "0","social_count_google_plus": "0","words": 59,"status": 1,"created": {"date": "2015-11-16 21:20:25","timezone_type":3,"timezone":"America\/Buenos_Aires"}]}',
                        'headers' => 'Content-Type: application/json; charset=utf-8'
                    ),
                ),
            ));
            $view->setTemplate('docs/docs/index');

            return $view;
        }

        public function blogAddAction()
        {
            $view = new ViewModel(array(
                'module' => 'Blog Services',
                'service' => 'Add Blog Service',
                'description' => 'This service will manage the request to create a new
                    Blog. The request contain the details of the blog.',
                'method' => 'POST',
                'endpoint' => '/blog/add/',
                'requestHeaders' => 'Content-Type: application/json; Bearer-Token: 0e1d4b16538f4c2986b2d2c4f5dfdae7',
                'params' => array(
                    array(
                        'name' => 'id_user',
                        'required' => true,
                        'type' => 'integer',
                        'format' => '',
                    ),
                    array(
                        'name' => 'name',
                        'required' => true,
                        'type' => 'string',
                        'format' => '',
                    ),
                    array(
                        'name' => 'name_updated',
                        'required' => true,
                        'type' => 'string',
                        'format' => '',
                    ),
                ),
                'exampleBody' => '[{"id_user":"1","name": "The Author","name_updated": "The Author"}]',
                'responses' => array(
                    '200 OK',
                    '400 Bad Request',
                    '400 Bad Request -> invalid JSON details',
                    '404 Not Found -> Blog already exists',
                ),
                'exampleResponses' => array(
                    array(
                        'code' => '200',
                        'body' => '{"result":[{"id":"4","id_user":"1","name": "The Author","name_updated": "The Author","status":1]}',
                        'headers' => 'Content-Type: application/json; charset=utf-8'
                    ),
                ),
            ));
            $view->setTemplate('docs/docs/index');

            return $view;
        }

        public function blogUpdateAction()
        {
            $view = new ViewModel(array(
                'module' => 'Blog Services',
                'service' => 'Update Blog Service',
                'description' => 'This service will manage the request to update a
                    Blog. The request contain the details of the blog to upgrade.',
                'method' => 'POST',
                'endpoint' => '/blog/update/:id[/]',
                'requestHeaders' => 'Content-Type: application/json; Bearer-Token: 0e1d4b16538f4c2986b2d2c4f5dfdae7',
                'params' => array(
                    array(
                        'name' => 'id',
                        'required' => true,
                        'type' => 'integer',
                        'format' => '',
                    ),
                    array(
                        'name' => 'id_user',
                        'required' => false,
                        'type' => 'integer',
                        'format' => '',
                    ),
                    array(
                        'name' => 'name',
                        'required' => false,
                        'type' => 'string',
                        'format' => '',
                    ),
                    array(
                        'name' => 'name_updated',
                        'required' => false,
                        'type' => 'string',
                        'format' => '',
                    ),
                    array(
                        'name' => 'status',
                        'required' => false,
                        'type' => 'integer',
                        'format' => '',
                    ),
                ),
                'exampleBody' => '[{"id":"4",name_updated": "The Author"}]',
                'responses' => array(
                    '200 OK',
                    '400 Bad Request',
                    '400 Bad Request -> invalid JSON details',
                    '404 Not Found -> Blog doesn\'t exists',
                ),
                'exampleResponses' => array(
                    array(
                        'code' => '200',
                        'body' => '{"result":[{"id":"4","id_user":"1","name": "Author","name": "BlackTime","status":0]}',
                        'headers' => 'Content-Type: application/json; charset=utf-8'
                    ),
                ),
            ));
            $view->setTemplate('docs/docs/index');

            return $view;
        }

        public function blogpostUpdateAction()
        {
            $view = new ViewModel(array(
                'module' => 'Blog Post Services',
                'service' => 'Update Blog Post Service',
                'description' => 'This service will manage the request to update a
                    Blog Post. The request contain the details of the blog post to upgrade.',
                'method' => 'POST',
                'endpoint' => '/blog-post/update/:userId/:id[/]',
                'requestHeaders' => 'Content-Type: application/json; Bearer-Token: 0e1d4b16538f4c2986b2d2c4f5dfdae7',
                'params' => array(
                    array(
                        'name' => 'userId',
                        'required' => true,
                        'type' => 'integer',
                        'format' => '',
                    ),
                    array(
                        'name' => 'id',
                        'required' => true,
                        'type' => 'integer',
                        'format' => '',
                    ),
                    array(
                        'name' => 'key_api',
                        'required' => false,
                        'type' => 'string',
                        'format' => '',
                    ),
                    array(
                        'name' => 'post_id',
                        'required' => false,
                        'type' => 'integer',
                        'format' => '',
                    ),
                    array(
                        'name' => 'title',
                        'required' => false,
                        'type' => 'string',
                        'format' => '',
                    ),
                    array(
                        'name' => 'date_publishing',
                        'required' => false,
                        'type' => 'date',
                        'format' => '',
                    ),
                    array(
                        'name' => 'url',
                        'required' => false,
                        'type' => 'string',
                        'format' => '',
                    ),
                    array(
                        'name' => 'author',
                        'required' => false,
                        'type' => 'string',
                        'format' => '',
                    ),
                    array(
                        'name' => 'category',
                        'required' => false,
                        'type' => 'string',
                        'format' => '',
                    ),
                    array(
                        'name' => 'avg_session_duration',
                        'required' => false,
                        'type' => 'string',
                        'format' => '',
                    ),
                    array(
                        'name' => 'total_social_count',
                        'required' => false,
                        'type' => 'integer',
                        'format' => '',
                    ),
                    array(
                        'name' => 'view',
                        'required' => false,
                        'type' => 'integer',
                        'format' => '',
                    ),
                    array(
                        'name' => 'social_count_facebook',
                        'required' => false,
                        'type' => 'integer',
                        'format' => '',
                    ),
                    array(
                        'name' => 'social_count_twitter',
                        'required' => false,
                        'type' => 'integer',
                        'format' => '',
                    ),
                    array(
                        'name' => 'social_count_linkedin',
                        'required' => false,
                        'type' => 'integer',
                        'format' => '',
                    ),
                    // array(
                    //     'name' => 'social_count_reddit',
                    //     'required' => false,
                    //     'type' => 'integer',
                    //     'format' => '',
                    // ),
                    // array(
                    //     'name' => 'social_count_stumble_upon',
                    //     'required' => false,
                    //     'type' => 'integer',
                    //     'format' => '',
                    // ),
                    array(
                        'name' => 'social_count_google_plus',
                        'required' => false,
                        'type' => 'integer',
                        'format' => '',
                    ),
                    // array(
                    //     'name' => 'social_count_pinterest',
                    //     'required' => false,
                    //     'type' => 'integer',
                    //     'format' => '',
                    // ),
                    // array(
                    //     'name' => 'social_count_flattr',
                    //     'required' => false,
                    //     'type' => 'integer',
                    //     'format' => '',
                    // ),
                    // array(
                    //     'name' => 'social_count_XING',
                    //     'required' => false,
                    //     'type' => 'integer',
                    //     'format' => '',
                    // ),
                    array(
                        'name' => 'words',
                        'required' => false,
                        'type' => 'integer',
                        'format' => '',
                    ),
                    array(
                        'name' => 'status',
                        'required' => false,
                        'type' => 'integer',
                        'format' => '',
                    ),
                    array(
                        'name' => 'created',
                        'required' => false,
                        'type' => 'integer',
                        'format' => '',
                    ),
                ),
                'exampleBody' => '[{"id":"4","author": "The Author"}]',
                'responses' => array(
                    '200 OK',
                    '400 Bad Request',
                    '400 Bad Request -> invalid JSON details',
                    '404 Not Found -> Blog Post doesn\'t exists',
                ),
                'exampleResponses' => array(
                    array(
                        'code' => '200',
                        'body' => '{"result":[{"id":4,key_api":"1-1","post_id": "2592","title": "Testing post","date_publishing": {"date":"2015-11-16 00:00:00","timezone_type":3,"timezone":"America\/Buenos_Aires"},"url": "www.test.com/post/test.php","author": "The Author","category": "Category Post","avg_session_duration": "00:00:00","total_social_count": "2","view": "2","social_count_facebook": "2","social_count_twitter": "0","social_count_linkedin": "0","social_count_google_plus": "0","words": 59,"status": 1,"created": {"date": "2015-11-16 21:20:25","timezone_type":3,"timezone":"America\/Buenos_Aires"}]}',
                        'headers' => 'Content-Type: application/json; charset=utf-8'
                    ),
                ),
            ));
            $view->setTemplate('docs/docs/index');

            return $view;
        }

        public function campaignAddCampaignAction()
        {
            $view = new ViewModel(array(
                'module' => 'Campaign Services',
                'service' => 'Add Campaign Service',
                'description' => 'This service will manage the request to create a new
                    Campaign. Mostly, it will be used for the admin role in order to create Campaigns. The customerId sent
                    should be a valid and existing one, and the request should contain the details of the campaign. Therefore,
                    this service will create all the neccesary empty posts for each topic/postType and let them in a \'Created\'
                    initial status (1).',
                'method' => 'POST',
                'endpoint' => '/campaign/add/',
                'requestHeaders' => 'Content-Type: application/json; Bearer-Token: 0e1d4b16538f4c2986b2d2c4f5dfdae7',
                'params' => array(
                    array(
                        'name' => 'name',
                        'required' => true,
                        'type' => 'string',
                        'format' => '',
                    ),
                    array(
                        'name' => 'customerId',
                        'required' => true,
                        'type' => 'integer',
                        'format' => '',
                    ),
                    array(
                        'name' => 'guidelines',
                        'required' => true,
                        'type' => 'text',
                        'format' => '',
                    ),
                    array(
                        'name' => 'details',
                        'required' => true,
                        'type' => 'JSON',
                        'format' => 'Should be a valid JSON with the following attributes: topic (string),
                        postType (integer), postsAmount (integer).<br />Note: quotations marks should be escaped.',
                    ),
                ),
                'exampleBody' => '[{  "name": "campaign name", "customerId": "2", "guidelines": "these are the guidelines for this campaign", "details": "[{\"topic\": \"topic1-1\", \"postType\": \"1\", \"postsAmount\": \"4\"}, {\"topic\": \"topic2-1\", \"postType\": \"1\", \"postsAmount\": \"4\"}]" }]',
                'responses' => array(
                    '200 OK',
                    '400 Bad Request',
                    '400 Bad Request -> invalid JSON details',
                    '404 Not Found -> invalid customerId',
                ),
                'exampleResponses' => array(
                    array(
                        'code' => '200',
                        'body' => '{"result":[{"id":4,"customer":{"id":2,"name":"test customer"},"name":"campaign name","guidelines":"these are the guidelines for this campaign","status":1,"created":{"date":"2015-05-17 18:01:42","timezone_type":3,"timezone":"America\/Buenos_Aires"},"createdBy":{"id":1,"username":"admin","email":"mm@mm.com","firstName":"matias","lastName":"martin","created":{"date":"-0001-11-30 00:00:00","timezone_type":3,"timezone":"America\/Buenos_Aires"},"modified":{"date":"-0001-11-30 00:00:00","timezone_type":3,"timezone":"America\/Buenos_Aires"}}}]}',
                        'headers' => 'Content-Type: application/json; charset=utf-8'
                    ),
                ),
            ));
            $view->setTemplate('docs/docs/index');

            return $view;
        }

        public function campaignSetCampaignDeadlinesAction()
        {
            $view = new ViewModel(array(
                'module' => 'Campaign Services',
                'service' => 'Set Campaign Deadlines Service',
                'description' => 'This service will manage the request to set the deadlines for a specific
                    Campaign. It should receive a JSON array with the roles and deadlines. Will return an array containing
                    the updated campaigns.',
                'method' => 'POST',
                'endpoint' => '/campaign/deadlines/',
                'requestHeaders' => 'Content-Type: application/json; Bearer-Token: 0e1d4b16538f4c2986b2d2c4f5dfdae7',
                'params' => array(
                    array(
                        'name' => 'campaignId',
                        'required' => true,
                        'type' => 'JSON',
                        'format' => '',
                    ),
                    array(
                        'name' => 'deadlines',
                        'required' => true,
                        'type' => 'JSON',
                        'format' => 'Should be a valid JSON with the following attributes: role (integer), topic(integer),
                            deadline (date format "YYYY-mm-dd").<br />Note: quotations marks should be escaped.',
                    ),
                ),
                'exampleBody' => '[{ "campaignId": "1", "deadlines": "[{\"role\": \"1\", \"topic\":\"2\", \"deadline\": \"2015-06-12\", \"role\": \"1\",\"topic\":\"2\", \"deadline\": \"2015-06-18\"}]" }]',
                'responses' => array(
                    '200 OK',
                    '400 Bad Request',
                    '400 Bad Request -> invalid JSON details',
                    '404 Not Found -> invalid campaignId',
                    '404 Not Found -> invalid roleId',
                ),
                'exampleResponses' => array(
                    array(
                        'code' => '200',
                        'body' => '{"result":[{"id":1,"customer":{"id":1,"name":"test customer"},"name":"elnombredelacampaign","guidelines":"guidelines","status":1,"created":{"date":"2015-05-17 00:29:57","timezone_type":3,"timezone":"America\/Buenos_Aires"},"createdBy":{"id":1,"username":"admin","email":"mm@mm.com","firstName":"matias","lastName":"martin","created":{"date":"-0001-11-30 00:00:00","timezone_type":3,"timezone":"America\/Buenos_Aires"},"modified":{"date":"-0001-11-30 00:00:00","timezone_type":3,"timezone":"America\/Buenos_Aires"}}}]}',
                        'headers' => 'Content-Type: application/json; charset=utf-8'
                    ),
                ),
            ));
            $view->setTemplate('docs/docs/index');

            return $view;
        }

        public function postUpdatePostStatusAction()
        {
            $view = new ViewModel(array(
                'module' => 'Post Services',
                'service' => 'Update post status Service',
                'description' => 'This service will manage the request to update post status and post workflow. It should receive a JSON array with the postId. Will return an array containing
                    the updated post.',
                'method' => 'POST',
                'endpoint' => '/post/status/:statusId',
                'requestHeaders' => 'Content-Type: application/json; Bearer-Token: 0e1d4b16538f4c2986b2d2c4f5dfdae7',
                'params' => array(
                    array(
                        'name' => 'postId',
                        'required' => true,
                        'type' => 'JSON',
                        'format' => '',
                    ),

                ),
                'exampleBody' => '[{ "postId": "2"}]',
                'responses' => array(
                    '200 OK',
                    '400 Bad Request',
                    '400 Bad Request -> invalid JSON details',
                    '404 Not Found -> invalid postId or invalid statusId',
                ),
                'exampleResponses' => array(
                    array(
                        'code' => '200',
                        'body' => '

        {
            "result":
            [
                {
                    "id": 2,
                    "topic":
                    {
                        "id": 6,
                        "title": "topic1-1",
                        "slug": "topic1-1",
                        "description": null
                    },
                    "status":
                    {
                        "id": 5,
                        "description": "Realizados"
                    },
                    "assignedToId": "",
                    "title": "",
                    "body": "This is the body of the post with id2",
                    "link": "www.post2link.com",
                    "tags": "tag1, tag2, tag3",
                    "programmed": null,
                    "edited":
                    {
                        "date": "2015-06-19 20:14:33",
                        "timezone_type": 3,
                        "timezone": "Europe/Paris"
                    },
                    "editedById": 2,
                    "created":
                    {
                        "date": "2015-06-17 19:05:33",
                        "timezone_type": 3,
                        "timezone": "Europe/Paris"
                    },
                    "createdById": 2
                }
            ]
        }

    ',
                        'headers' => 'Content-Type: application/json; charset=utf-8'
                    ),
                ),
            ));
            $view->setTemplate('docs/docs/index');

            return $view;
        }

        public function postUpdatePostAction()
        {
            $view = new ViewModel(array(
                'module' => 'Post Services',
                'service' => 'Update post Service',
                'description' => 'This service will manage the request to update post. It should receive a JSON array with the postId and optionally body, link, tags and programmed. Will return an array containing
                    the updated post.',
                'method' => 'POST',
                'endpoint' => '/post/update',
                'requestHeaders' => 'Content-Type: application/json; Bearer-Token: 0e1d4b16538f4c2986b2d2c4f5dfdae7',
                'params' => array(
                    array(
                        'name' => 'postId',
                        'required' => true,
                        'type' => 'JSON',
                        'format' => '',
                    ),

                ),
                'exampleBody' => ' [{ "postId": "3", "body":"This is the new body for post with id 3", "link":"www.post3link.com", "tags":"tag1, tag2, tag3"}] ]',
                'responses' => array(
                    '200 OK',
                    '400 Bad Request',
                    '400 Bad Request -> invalid JSON details',
                    '404 Not Found -> invalid postId',
                ),
                'exampleResponses' => array(
                    array(
                        'code' => '200',
                        'body' => '

        {
            "result":
            [
                {
                    "result":
        [
            {
                "id": 3,
                "topic":
                {
                    "id": 6,
                    "title": "topic1-1",
                    "slug": "topic1-1",
                    "description": null
                },
                "status":
                {
                    "id": 1,
                    "description": "Creado"
                },
                "assignedToId": "",
                "title": "",
                "body": "This is the new body for post with id 3",
                "link": "www.post3link.com",
                "tags": "tag1, tag2, tag3",
                "programmed": null,
                "edited":
                {
                    "date": "2015-06-22 17:19:08",
                    "timezone_type": 3,
                    "timezone": "Europe/Paris"
                },
                "editedById": 2,
                "created":
                {
                    "date": "2015-06-17 19:06:08",
                    "timezone_type": 3,
                    "timezone": "Europe/Paris"
                },
                "createdById": 2
            }
        ]
                }
            ]
        }

    ',
                        'headers' => 'Content-Type: application/json; charset=utf-8'
                    ),
                ),
            ));
            $view->setTemplate('docs/docs/index');

            return $view;
        }


        public function postPublishDateAction()
        {
            $view = new ViewModel(array(
                'module' => 'Post Services',
                'service' => 'Set post publish date',
                'description' => 'This service will manage the request to set post publish date. It should receive a JSON array with the postId and date. Will return an array containing
                    the updated post.',
                'method' => 'POST',
                'endpoint' => '/post/publishingdate',
                'requestHeaders' => 'Content-Type: application/json; Bearer-Token: 0e1d4b16538f4c2986b2d2c4f5dfdae7',
                'params' => array(
                    array(
                        'name' => 'postId',
                        'required' => true,
                        'type' => 'JSON',
                        'format' => '',
                    ),
                    array(
                        'name' => 'publishingDate',
                        'required' => true,
                        'type' => 'JSON',
                        'format' => '',
                    ),

                ),
                'exampleBody' => '[{ "postId": "10", "publishingDate":"2015-07-27 18:01:42"}] ',
                'responses' => array(
                    '200 OK',
                    '400 Bad Request',
                    '400 Bad Request -> invalid JSON details',
                    '404 Not Found -> invalid postId',
                ),
                'exampleResponses' => array(
                    array(
                        'code' => '200',
                        'body' => '

        {
      "result": [
        {
          "id": 10,
          "topic": {
            "id": 6,
            "title": "topic1-1",
            "slug": "topic1-1",
            "description": null
          },
          "status": {
            "id": 1,
            "description": "Creado"
          },
          "assignedToId": "",
          "title": "",
          "body": "",
          "link": "",
          "tags": "",
          "programmed": null,
          "edited": null,
          "editedById": "",
          "created": {
            "date": "2015-06-22 17:55:48",
            "timezone_type": 3,
            "timezone": "Europe/Paris"
          },
          "createdById": 2,
          "publishingDate": {
            "date": "2015-07-27 18:01:42",
            "timezone_type": 3,
            "timezone": "Europe/Paris"
          }
        }
      ]
    }

    ',
                        'headers' => 'Content-Type: application/json; charset=utf-8'
                    ),
                ),
            ));
            $view->setTemplate('docs/docs/index');

            return $view;
        }



        public function campaignUpdateCampaignAction()
        {
            $view = new ViewModel(array(
                'module' => 'Campaign Services',
                'service' => 'Update Campaign Service',
                'description' => 'This service will manage the request to update campaign',
                'method' => 'POST',
                'endpoint' => '/campaign/update/',
                'requestHeaders' => 'Content-Type: application/json; Bearer-Token: 0e1d4b16538f4c2986b2d2c4f5dfdae7',
                'params' => array(
                    array(
                        'name' => 'campaignId',
                        'required' => true,
                        'type' => 'string',
                        'format' => '',
                    ),
                    array(
                        'name' => 'name',
                        'required' => false,
                        'type' => 'text',
                        'format' => '',
                    ),
                    array(
                        'name' => 'guidelines',
                        'required' => false,
                        'type' => 'text',
                        'format' => '',
                    ),

                ),
                'exampleBody' => '[{ "campaignId": "5", "name": "new name for campaign 5", "guidelines":"new guidelines for campaign 5" }] ',
                'responses' => array(
                    '200 OK',
                    '400 Bad Request',
                    '400 Bad Request -> invalid JSON details',
                    '404 Not Found -> invalid campaignId',
                ),
                'exampleResponses' => array(
                    array(
                        'code' => '200',
                        'body' => '{
      "result": {
        "id": 5,
        "customer": {
          "id": 1,
          "name": "test customer"
        },
        "name": "new name for campaign 5",
        "guidelines": "new guidelines for campaign 5",
        "status": 1,
        "created": {
          "date": "2015-06-22 17:55:48",
          "timezone_type": 3,
          "timezone": "Europe/Paris"
        },
        "createdBy": {
          "id": 2,
          "username": "zarkok@gmail.com",
          "email": "zarkok@gmail.com",
          "firstName": "zarko",
          "lastName": "krneta",
          "created": {
            "date": "-0001-11-30 00:00:00",
            "timezone_type": 3,
            "timezone": "Europe/Paris"
          },
          "modified": {
            "date": "2015-06-10 15:51:30",
            "timezone_type": 3,
            "timezone": "Europe/Paris"
          }
        }
      }
    }',
                        'headers' => 'Content-Type: application/json; charset=utf-8'
                    ),
                ),
            ));
            $view->setTemplate('docs/docs/index');

            return $view;
        }

        public function campaignDeleteCampaignAction()
        {
            $view = new ViewModel(array(
                'module' => 'Campaign Services',
                'service' => 'Delete Campaign Service',
                'description' => 'This service will manage the request to delete campaign',
                'method' => 'POST',
                'endpoint' => '/campaign/delete/',
                'requestHeaders' => 'Content-Type: application/json; Bearer-Token: 0e1d4b16538f4c2986b2d2c4f5dfdae7',
                'params' => array(
                    array(
                        'name' => 'campaignId',
                        'required' => true,
                        'type' => 'string',
                        'format' => '',
                    ),


                ),
                'exampleBody' => '[{ "campaignId": "5"}]',
                'responses' => array(
                    '200 OK',
                    '400 Bad Request',
                    '400 Bad Request -> invalid JSON details',
                    '404 Not Found -> invalid campaignId',
                ),
                'exampleResponses' => array(
                    array(
                        'code' => '200',
                        'body' => '',
                        'headers' => 'Content-Type: application/json; charset=utf-8'
                    ),
                ),
            ));
            $view->setTemplate('docs/docs/index');

            return $view;
        }

        public function blogGetAction()
        {
            $view = new ViewModel(array(
                'module' => 'Blog Services',
                'service' => 'Get Blog Service',
                'description' => 'This service will provide a list of available blog',
                'method' => 'GET',
                'endpoint' => '/blog/get/[:id][/]',
                'requestHeaders' => 'Content-Type: application/json; Bearer-Token: 0e1d4b16538f4c2986b2d2c4f5dfdae7',
                'params' => array(
                    array(
                        'name' => 'id',
                        'required' => false,
                        'type' => 'integer',
                        'format' => '',
                    ),
                ),
                'exampleBody' => '',
                'responses' => array(
                    '200 OK',
                    '400 Bad Request',
                    '400 Bad Request -> invalid JSON details',
                    '404 Not Found -> Blog doesn\'t exists',
                ),
                'exampleResponses' => array(
                    array(
                        'code' => '200',
                        'body' => '{"result":[{"id": 1,"id_user": 1,"name": "Content Marketing La","name": "App","status":1}]}',
                        'headers' => 'Content-Type: application/json; charset=utf-8'
                    ),
                ),
            ));
            $view->setTemplate('docs/docs/index');

            return $view;
        }

        public function blogpostGetAction()
        {
            $view = new ViewModel(array(
                'module' => 'Blog Post Services',
                'service' => 'Get Blog Post Service',
                'description' => 'This service will provide a list of available blog posts',
                'method' => 'GET',
                'endpoint' => '/blog-post/get/[:userId]/[:postId][/]',
                'requestHeaders' => 'Content-Type: application/json; Bearer-Token: 0e1d4b16538f4c2986b2d2c4f5dfdae7',
                'params' => array(
                    array(
                        'name' => 'postId',
                        'required' => false,
                        'type' => 'integer',
                        'format' => '',
                    ),
                    array(
                        'name' => 'userId',
                        'required' => true,
                        'type' => 'integer',
                        'format' => '',
                    ),
                ),
                'exampleBody' => '',
                'responses' => array(
                    '200 OK',
                    '400 Bad Request',
                    '400 Bad Request -> invalid JSON details',
                    '404 Not Found -> Blog Post doesn\'t exists',
                ),
                'exampleResponses' => array(
                    array(
                        'code' => '200',
                        'body' => '{"result":[{"id":4,key_api":"1-1","post_id": "2592","title": "Testing post","date_publishing": {"date":"2015-11-16 00:00:00","timezone_type":3,"timezone":"America\/Buenos_Aires"},"url": "www.test.com/post/test.php","author": "The Author","category": "Category Post","avg_session_duration": "00:00:00","total_social_count": "2","view": "2","social_count_facebook": "2","social_count_twitter": "0","social_count_linkedin": "0","social_count_google_plus": "0","words":"59","status":"1","created": {"date": "2015-11-16 21:20:25","timezone_type":3,"timezone":"America\/Buenos_Aires"}]}',
                        'headers' => 'Content-Type: application/json; charset=utf-8'
                    ),
                ),
            ));
            $view->setTemplate('docs/docs/index');

            return $view;
        }

        public function blogpostActiveAction()
        {
            $view = new ViewModel(array(
                'module' => 'Blog Post Services',
                'service' => 'Active Blog Post Service',
                'description' => 'This service update the blog posts to status active, defined as status 2 for the post updated in the resync after having been removed',
                'method' => 'POST',
                'endpoint' => '/blog-post/update/active[/]',
                'requestHeaders' => 'Content-Type: application/json; Bearer-Token: 0e1d4b16538f4c2986b2d2c4f5dfdae7',
                'params' => array(
                    array(
                        'name' => 'key_api',
                        'required' => true,
                        'type' => 'string',
                        'format' => '',
                    ),
                ),
                'exampleBody' => '',
                'responses' => array(
                    '200 OK',
                    '400 Bad Request',
                    '400 Bad Request -> invalid JSON details',
                ),
                'exampleResponses' => array(
                    array(
                        'code' => '200',
                        'body' => '',
                        'headers' => 'Content-Type: application/json; charset=utf-8'
                    ),
                ),
            ));
            $view->setTemplate('docs/docs/index');

            return $view;
        }

        public function blogpostCreateTableAction()
        {
            $view = new ViewModel(array(
                'module' => 'Blog Post Services',
                'service' => 'Create Table Blog Post Service',
                'description' => 'This service create a table "ca_blog_post_user_{userId}" for a user especified',
                'method' => 'POST',
                'endpoint' => '/blog-post/create[/]',
                'requestHeaders' => 'Content-Type: application/json; Bearer-Token: 0e1d4b16538f4c2986b2d2c4f5dfdae7',
                'params' => array(
                    array(
                        'name' => 'userId',
                        'required' => true,
                        'type' => 'integer',
                        'format' => '',
                    ),
                ),
                'exampleBody' => '',
                'responses' => array(
                    '200 OK',
                    '400 Bad Request',
                    '400 Bad Request -> invalid JSON details',
                    '404 Not Found -> Blog Post User Table already exists',
                ),
                'exampleResponses' => array(
                    array(
                        'code' => '200',
                        'body' => '{"result":[{"table":"ca_blog_posts_user_1"}]}',
                        'headers' => 'Content-Type: application/json; charset=utf-8'
                    ),
                ),
            ));
            $view->setTemplate('docs/docs/index');

            return $view;
        }

        public function blogpostAnalyticsAction()
        {
            $view = new ViewModel(array(
                'module' => 'Blog Post Services',
                'service' => 'Analytics Blog Post Service',
                'description' => 'This service will provide a small analytics blogspot',
                'method' => 'POST',
                'endpoint' => '/blog-post/analytics[/]',
                'requestHeaders' => 'Content-Type: application/json; Bearer-Token: 0e1d4b16538f4c2986b2d2c4f5dfdae7',
                'params' => array(
                    array(
                        'name' => 'key_api',
                        'required' => true,
                        'type' => 'string',
                        'format' => '',
                    ),
                    array(
                        'name' => 'date1',
                        'required' => true,
                        'type' => 'string',
                        'format' => '',
                    ),
                    array(
                        'name' => 'date2',
                        'required' => true,
                        'type' => 'string',
                        'format' => '',
                    ),
                ),
                'exampleBody' => ' [{"key_api":"1-1","date1": "2015-07-27","date2": "2015-07-30"}]',

                'responses' => array(
                    '200 OK',
                    '400 Bad Request',
                    '400 Bad Request -> invalid JSON details',
                    '404 Not Found -> Information not found',
                ),
                'exampleResponses' => array(
                    array(
                        'code' => '200',
                        'body' => '{"result":{"total_time": "00:35:11","total_persons": 154,"tt_person": "00:01:45","tt_500": "00:02:20"}}',
                        'headers' => 'Content-Type: application/json; charset=utf-8'
                    ),
                ),
            ));
            $view->setTemplate('docs/docs/index');

            return $view;
        }

        public function blogpostTopAuthorAction()
        {
            $view = new ViewModel(array(
                'module' => 'Blog Post Services',
                'service' => 'Top Author Blog Post Service',
                'description' => 'This service will provide a list of top author in blog posts',
                'method' => 'POST',
                'endpoint' => '/blog-post/top-author[/]',
                'requestHeaders' => 'Content-Type: application/json; Bearer-Token: 0e1d4b16538f4c2986b2d2c4f5dfdae7',
                'params' => array(
                    array(
                        'name' => 'key_api',
                        'required' => true,
                        'type' => 'string',
                        'format' => '',
                    ),
                    array(
                        'name' => 'author',
                        'required' => false,
                        'type' => 'string',
                        'format' => '',
                    ),
                    array(
                        'name' => 'date1',
                        'required' => true,
                        'type' => 'string',
                        'format' => '',
                    ),
                    array(
                        'name' => 'date2',
                        'required' => true,
                        'type' => 'string',
                        'format' => '',
                    ),
                ),
                'exampleBody' => ' [{"key_api":"1-1","date1": "2015-07-27","date2": "2015-07-30"}] ',

                'responses' => array(
                    '200 OK',
                    '400 Bad Request',
                    '400 Bad Request -> invalid JSON details',
                    '404 Not Found -> Blog Post doesn\'t exists',
                ),
                'exampleResponses' => array(
                    array(
                        'code' => '200',
                        'body' => '{"result":[{"author": "Alicia Quen","title": "Testing post","shares": 2,"facebook": 2,"twitter": 0,"linkedin": 0,"google_plus": 0,"shares_posts": 2,"total_shares_author": 132}]}',
                        'headers' => 'Content-Type: application/json; charset=utf-8'
                    ),
                ),
            ));
            $view->setTemplate('docs/docs/index');

            return $view;
        }

        public function blogpostTopTopicAction()
        {
            $view = new ViewModel(array(
                'module' => 'Blog Post Services',
                'service' => 'Top Topic Blog Post Service',
                'description' => 'This service will provide a list of top topic in blog posts',
                'method' => 'POST',
                'endpoint' => '/blog-post/top-topic[/]',
                'requestHeaders' => 'Content-Type: application/json; Bearer-Token: 0e1d4b16538f4c2986b2d2c4f5dfdae7',
                'params' => array(
                    array(
                        'name' => 'key_api',
                        'required' => true,
                        'type' => 'string',
                        'format' => '',
                    ),
                    array(
                        'name' => 'category',
                        'required' => false,
                        'type' => 'string',
                        'format' => '',
                    ),
                    array(
                        'name' => 'date1',
                        'required' => true,
                        'type' => 'string',
                        'format' => '',
                    ),
                    array(
                        'name' => 'date2',
                        'required' => true,
                        'type' => 'string',
                        'format' => '',
                    ),
                ),
                'exampleBody' => ' [{"key_api":"1-1","date1": "2015-07-27","date2": "2015-07-30"}] ',

                'responses' => array(
                    '200 OK',
                    '400 Bad Request',
                    '400 Bad Request -> invalid JSON details',
                    '404 Not Found -> Blog Post doesn\'t exists',
                ),
                'exampleResponses' => array(
                    array(
                        'code' => '200',
                        'body' => '{"result":[{"Category": "marketing de contenido","Total Posts": "2","Shares": 32,"Facebook": 21,"Twitter": 1,"Linkedin": 3,"Google Plus": 8,"Shares / Qt Posts": 16},{"Category": "marketing digital","Total Posts": "1","Shares": 23,"Facebook": 19,"Twitter": 1,"Linkedin": 3,"Google Plus": 0,"Shares / Qt Posts": 23}]}',
                        'headers' => 'Content-Type: application/json; charset=utf-8'
                    ),
                ),
            ));
            $view->setTemplate('docs/docs/index');

            return $view;
        }

        public function blogpostTopAction()
        {
            $view = new ViewModel(array(
                'module' => 'Blog Post Services',
                'service' => 'Top Blog Post Service',
                'description' => 'This service will provide a list of top blog posts',
                'method' => 'POST',
                'endpoint' => '/blog-post/top-post[/]',
                'requestHeaders' => 'Content-Type: application/json; Bearer-Token: 0e1d4b16538f4c2986b2d2c4f5dfdae7',
                'params' => array(
                    array(
                        'name' => 'key_api',
                        'required' => true,
                        'type' => 'string',
                        'format' => '',
                    ),
                    array(
                        'name' => 'date1',
                        'required' => true,
                        'type' => 'string',
                        'format' => '',
                    ),
                    array(
                        'name' => 'date2',
                        'required' => true,
                        'type' => 'string',
                        'format' => '',
                    ),
                ),
                'exampleBody' => ' [{"key_api":"1-1","date1": "2015-07-27","date2": "2015-07-30"}] ',

                'responses' => array(
                    '200 OK',
                    '400 Bad Request',
                    '400 Bad Request -> invalid JSON details',
                    '404 Not Found -> Blog Post doesn\'t exists',
                ),
                'exampleResponses' => array(
                    array(
                        'code' => '200',
                        'body' => '{"result":[{"Title": "7 Vantagens do Plano de Marketing Digital","Date":{"date": "2015-10-05 00:00:00","timezone_type": 3,"timezone": "America/Sao_Paulo"},"Time": "00:02:23","Words": 59,"Facebook": 19,"Twitter": 1,"Linkedin": 3,"Google Plus": 0,"Shares": 23,"View": 11},"Title": "Testing post","Date":{"date": "2015-11-16 00:00:00","timezone_type": 3,"timezone": "America/Sao_Paulo"},"Time": "00:00:00","Words": 59,"Facebook": 2,"Twitter": 0,"Linkedin": 0,"Google Plus": 0,"Shares": 9,"View": 8},{"Title": "Testing post","Date":{"date": "2015-11-16 00:00:00","timezone_type": 3,"timezone": "America/Sao_Paulo"},"Time": "00:00:00","Words": 59,"Facebook": 2,"Twitter": 0,"Linkedin": 0,"Google Plus": 0,"Shares": 7,"View": 16}]}',
                        'headers' => 'Content-Type: application/json; charset=utf-8'
                    ),
                ),
            ));
            $view->setTemplate('docs/docs/index');

            return $view;
        }

        public function blogpostSearchAction()
        {
            $view = new ViewModel(array(
                'module' => 'Blog Post Services',
                'service' => 'Search Blog Post Service',
                'description' => 'This service will provide a list of blog posts in a range of dates.',
                'method' => 'POST',
                'endpoint' => '/blog-post/search[/]',
                'requestHeaders' => 'Content-Type: application/json; Bearer-Token: 0e1d4b16538f4c2986b2d2c4f5dfdae7',
                'params' => array(
                    array(
                        'name' => 'api_key',
                        'required' => true,
                        'type' => 'string',
                        'format' => '',
                    ),
                    array(
                        'name' => 'date1',
                        'required' => true,
                        'type' => 'string',
                        'format' => '',
                    ),
                    array(
                        'name' => 'date2',
                        'required' => true,
                        'type' => 'string',
                        'format' => '',
                    ),
                ),
                'exampleBody' => '[{ "key_api": "1-1", "date1": "2015-07-27","date2": "2015-07-30"}] ',
                'responses' => array(
                    '200 OK',
                    '400 Bad Request',
                    '400 Bad Request -> invalid JSON details',
                    '404 Not Found -> Blog Post doesn\'t exists',
                ),
                'exampleResponses' => array(
                    array(
                        'code' => '200',
                        'body' => '{"result":[{"id":4,key_api":"1-1","post_id": "2592","title": "Testing post","date_publishing": {"date":"2015-11-16 00:00:00","timezone_type":3,"timezone":"America\/Buenos_Aires"},"url": "www.test.com/post/test.php","author": "The Author","category": "Category Post","avg_session_duration": "00:00:00","total_social_count": "2","view": "2","social_count_facebook": "2","social_count_twitter": "0","social_count_linkedin": "0","social_count_google_plus": "0","words":"259","status":"1","created": {"date": "2015-11-16 21:20:25","timezone_type":3,"timezone":"America\/Buenos_Aires"}]}',
                        'headers' => 'Content-Type: application/json; charset=utf-8'
                    ),
                ),
            ));
            $view->setTemplate('docs/docs/index');

            return $view;
        }

        public function blogRemoveAction()
        {
            $view = new ViewModel(array(
                'module' => 'Blog Services',
                'service' => 'Remove Blog Service',
                'description' => 'This service remove a blog specified',
                'method' => 'DELETE',
                'endpoint' => '/blog/remove/:id[/]',
                'requestHeaders' => 'Content-Type: application/json; Bearer-Token: 0e1d4b16538f4c2986b2d2c4f5dfdae7',
                'params' => array(
                    array(
                        'name' => 'id',
                        'required' => true,
                        'type' => 'integer',
                        'format' => '',
                    ),
                ),
                'exampleBody' => '',
                'responses' => array(
                    '200 OK',
                    '400 Bad Request',
                    '400 Bad Request -> invalid JSON details',
                    '404 Not Found -> Blog doesn\'t exists',
                ),
                'exampleResponses' => array(
                    array(
                        'code' => '200',
                        'body' => '',
                        'headers' => 'Content-Type: application/json; charset=utf-8'
                    ),
                ),
            ));
            $view->setTemplate('docs/docs/index');

            return $view;
        }

        public function blogSearchByUserAction()
        {
            $view = new ViewModel(array(
                'module' => 'Blog Services',
                'service' => 'Search Blog By User Service',
                'description' => 'This service search blogs associated to a user especified',
                'method' => 'GET',
                'endpoint' => '/blog/search/:id_user[/]',
                'requestHeaders' => 'Content-Type: application/json; Bearer-Token: 0e1d4b16538f4c2986b2d2c4f5dfdae7',
                'params' => array(
                    array(
                        'name' => 'id_user',
                        'required' => true,
                        'type' => 'integer',
                        'format' => '',
                    ),
                ),
                'exampleBody' => '',
                'responses' => array(
                    '200 OK',
                    '400 Bad Request',
                    '400 Bad Request -> invalid JSON details',
                    '404 Not Found -> Blog Post doesn\'t exists',
                ),
                'exampleResponses' => array(
                    array(
                        'code' => '200',
                        'body' => '{"id": 1,"id_user": 1,"name": "Content Marketing La","name_updated": "App","status":1},',
                        'headers' => 'Content-Type: application/json; charset=utf-8'
                    ),
                ),
            ));
            $view->setTemplate('docs/docs/index');

            return $view;
        }

        public function blogpostRemoveAction()
        {
            $view = new ViewModel(array(
                'module' => 'Blog Post Services',
                'service' => 'Remove Blog Post Service',
                'description' => 'This service remove a blog post specified logically',
                'method' => 'DELETE',
                'endpoint' => '/blog-post/remove/:key_api[/]',
                'requestHeaders' => 'Content-Type: application/json; Bearer-Token: 0e1d4b16538f4c2986b2d2c4f5dfdae7',
                'params' => array(
                    array(
                        'name' => 'key_api',
                        'required' => true,
                        'type' => 'string',
                        'format' => '',
                    ),
                    array(
                        'name' => 'post_id',
                        'required' => false,
                        'type' => 'integer',
                        'format' => '',
                    ),
                ),
                'exampleBody' => '',
                'responses' => array(
                    '200 OK',
                    '400 Bad Request',
                    '400 Bad Request -> invalid JSON details',
                    '404 Not Found -> Blog Post doesn\'t exists',
                ),
                'exampleResponses' => array(
                    array(
                        'code' => '200',
                        'body' => '',
                        'headers' => 'Content-Type: application/json; charset=utf-8'
                    ),
                ),
            ));
            $view->setTemplate('docs/docs/index');

            return $view;
        }

        public function campaignListCampaignAction()
        {
            $view = new ViewModel(array(
                'module' => 'Campaign Services',
                'service' => 'List Campaign Service',
                'description' => 'This service will manage the request to delete campaign',
                'method' => 'GET',
                'endpoint' => '/campaign/list/',
                'requestHeaders' => 'Content-Type: application/json; Bearer-Token: 0e1d4b16538f4c2986b2d2c4f5dfdae7',
                'params' => array(



                ),
                'exampleBody' => '',
                'responses' => array(
                    '200 OK',
                    '400 Bad Request',
                    '400 Bad Request -> invalid JSON details',
                    '404 Not Found -> invalid campaignId',
                ),
                'exampleResponses' => array(
                    array(
                        'code' => '200',
                        'body' => '',
                        'headers' => 'Content-Type: application/json; charset=utf-8'
                    ),
                ),
            ));
            $view->setTemplate('docs/docs/index');

            return $view;
        }





    }
