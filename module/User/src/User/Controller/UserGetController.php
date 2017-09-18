<?php

namespace User\Controller;

use App\Mvc\Controller\RestfulController;
use User\Entity\User;
use Zend\View\Model\JsonModel;

/**
 * 
 */
class UserGetController extends RestfulController {

    protected $_allowedMethod = 'get';
    
    const SEARCH_USER_MIN_RATING_FILTER = 3;
    const MAX_ITEM_COUNT_PER_PAGE = 100;

    /**
     * @link http://www.yami-ec.com.ar/wiki/index.php?title=Search_User Service API documentation
     * @return Zend\View\Model\JsonModel
     */
    public function searchAction() 
    {
        $requestQueryParams = (array) $this->getRequest()->getQuery();

        $configQueryParams = [
            'sortBy' => [
                'field' => false, 
                'value' => 'user.lastname', 
                'allowOverride' => true, 
                'allowedValues' => [
                    'lastname' => 'user.lastname',
                    'name' => 'user.name', 
                    'topic' => 'term.title',
                ]
            ],
            'status' => [
                'field' => 'user.status', 
                'value' => User::STATUS_ACTIVE, 
                'allowOverride' => false,
            ],
            'name' => [
                'field' => ['user.name', 'user.lastname', 'term.title', 'skill.name', 'user.writingExperience'], 
                'value' => false, 
                'allowOverride' => true,
            ],
            'lastname' => [
                'field' => 'user.lastname', 
                 'value' => false, 
                 'allowOverride' => true,
             ],
            'topic' => [
                'field' => ['term.id', 'term.title'],
                'value' => false, 
                'allowOverride' => true,
            ],
            'rating' => [
                'field' => 'utests.rating', 
                'value' => false, 
                'allowOverride' => true,
            ],
            'languageRegionId' => [
                'field' => 'user.language_region_id', 
                'value' => false, 
                'allowOverride' => true,
            ],
            'skillId' => [
                'field' => ['uskills.skill_id'],
                'value' => false,
                'allowOverride' => true,
            ],
            'skillName' => [
                'field' => ['skill.name'],
                'value' => false,
                'allowOverride' => true,
            ],
            'price' => [
                'field' => 'uprices.price',
                'value' => false,
                'allowOverride' => true,
            ],
        ];

        // Search Query
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $queryBuilder->select('user')
            ->distinct()
            ->from('User\Entity\User', 'user')
            ->leftJoin('user.user_tests', 'utests')
            ->leftJoin('user.user_topics', 'utopics')
            ->leftJoin('utopics.term', 'term')
            ->leftJoin('user.user_skills', 'uskills')
            ->leftJoin('uskills.skill', 'skill')
            ->leftJoin('user.user_price', 'uprices')
            ->leftJoin('user.user_works', 'uworks')
            ->leftJoin('user.user_educations', 'ueducations');

        // Search Filters
        // User must have at least one rating entry greather than or equal to 3
        $queryBuilder->andWhere($queryBuilder->expr()->gte('utests.rating', ':ratingFilter'));
        $queryBuilder->setParameter('ratingFilter', self::SEARCH_USER_MIN_RATING_FILTER);
        // User must have at least one work entry
        $queryBuilder->andWhere($queryBuilder->expr()->isNotNull('uworks.id'));
        // User must have at least one education entry
        $queryBuilder->andWhere($queryBuilder->expr()->isNotNull('ueducations.id'));

        if (isset($requestQueryParams['minPrice'])) {
            $queryBuilder->andWhere($queryBuilder->expr()->gte('uprices.price', ':minPrice'));
            $queryBuilder->setParameter('minPrice', $requestQueryParams['minPrice']);
        }
        if (isset($requestQueryParams['maxPrice'])) {
            $queryBuilder->andWhere($queryBuilder->expr()->lte('uprices.price', ':maxPrice'));
            $queryBuilder->setParameter('maxPrice', $requestQueryParams['maxPrice']);
        }

        //To avoid DB server memory leak we set a max LIMIT of self::MAX_ITEM_COUNT_PER_PAGE
        if (isset($requestQueryParams['itemCountPerPage']) 
            && (int) $requestQueryParams['itemCountPerPage'] > self::MAX_ITEM_COUNT_PER_PAGE) {
            $requestQueryParams['itemCountPerPage'] = self::MAX_ITEM_COUNT_PER_PAGE;
        }

        $callback = function(User $user) {
            return $user->getExpectedFullArray();
        };
        

        $queryPaginatorResults = $this->QueryPaginator()->processQuery($queryBuilder, $configQueryParams, $requestQueryParams, $callback, false);
       
        if (isset($requestQueryParams['name'])) {
            $queryPaginatorResults = $this->_addMatchedUsersToQueryPaginator($queryPaginatorResults, $requestQueryParams['name']);
            if (count($queryPaginatorResults['items'])) {
                $this->getResponse()->setStatusCode(200);
            }
        }

        return new JsonModel($queryPaginatorResults);
    }

    /**
     * @link http://www.yami-ec.com.ar/wiki/index.php?title=User_Get_Article_Notifications Service API documentation
     * @return Zend\View\Model\JsonModel
     */
    public function getArticleNotificationsAction()
    {
        try {

            $userId = $this->getEvent()->getRouteMatch()->getParam('userId');
            
            /* @var $userWorkFlow \User\Model\Workflow\UserWorkflow */
            $userWorkFlow = $this->getServiceLocator()->get('UserWorkflow');
            $user = $userWorkFlow->getUserById($userId);

            if ($user) {
                $result = [
                    'userId' => $user->getId(),
                    'articleNotifications' => $user->getNewArticleNotificationsInRandom(),
                ];
                $this->getResponse()->setStatusCode(200);
            } else {
                $result = [];
                $this->getResponse()->setStatusCode(404);
            }


        } catch (\Exception $exc) {

            $this->getResponse()->setStatusCode(500);

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
    
    private function _addMatchedUsersToQueryPaginator($queryPaginatorResults, $name)
    {
        $profileHelper = new \Profile\Model\Helper\ProfileHelper();
        $profileHelper->setServiceLocator($this->getServiceLocator());
        $matchs = $profileHelper->getUsersIdUsingMatchAgainst($name);
        foreach ($matchs as $matchedUserId) {
            $alreadyExist = false;

            foreach ($queryPaginatorResults['items'] as $queryPaginatorItem) {
                if ($queryPaginatorItem['id'] === $matchedUserId['id']) {
                    $alreadyExist = true;
                }
            }

            if (!$alreadyExist) {
                $queryPaginatorResults['items'][] = $profileHelper->getUser($matchedUserId['id'])->getExpectedFullArray();
                $queryPaginatorResults['currentItemCount'] += 1;
                $queryPaginatorResults['totalItemCount'] += 1;
                $queryPaginatorResults['pageCount'] = (int)ceil($queryPaginatorResults['totalItemCount'] / self::MAX_ITEM_COUNT_PER_PAGE);
            }
        }
        
        return $queryPaginatorResults;
    }
}
