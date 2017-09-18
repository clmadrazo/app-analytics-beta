<?php

namespace BlogPost\Controller;

use App\Mvc\Controller\RestfulController;
use Zend\View\Model\JsonModel;
use Zend\Mvc\MvcEvent;
use BlogPost\Entity\BlogPost;

/**
 * 
 */
class SearchBlogPostController extends RestfulController {    
    protected $_allowedMethod = "post";
    protected $date1;
    protected $date2;
    protected $em;
    protected $key;

    public function indexAction() {
        $this->em = $this->getEntityManager();
        $request = $this->getRequest();
        $requestData = $this->processBodyContent($request);
        $this->key = $requestData[0]['key_api'];
        $parts = explode("-", $this->key);
        $first = array_shift($parts);
        $user = $first;
        $cm = $this->em->getClassMetadata('BlogPost\Entity\BlogPost');
        $cm->setTableName('ca_blog_posts_user_'.$user);
        $this->date1 = $requestData[0]['date1'];
        $this->date2 = $requestData[0]['date2'];
        $blogPosts = $this->_getAllBlogPost();
        //$blogPosts = (!empty($blogPosts)) ? $blogPosts : $this->_getAllBlogPost(true);
        if (!empty($blogPosts)) {
            $return = $blogPosts;
            $this->getResponse()->setStatusCode(200);
        } else {
            $this->getResponse()->setStatusCode(404);
            $return = array("errors" => \BlogPost\Entity\BlogPost::ERR_BLOGPOST_NOT_FOUND);
        }

        return new JsonModel(array("result" => $return));
    }

    //Selects the latest updates from all posts in a range of dates
    private function _getAllBlogPost()
    {
        $query = $this->em->createQuery("SELECT bp.post_id, max(bp.created) upd
                                        FROM BlogPost\Entity\BlogPost bp where bp.key_api=?1 and bp.status <> 0 and bp.date_publishing between ?4 and ?5
                                        group by bp.post_id");
        $query->setParameter(1, $this->key);
        $query->setParameter(4, $this->date1);
        $query->setParameter(5, $this->date2);
        $queryResult =  $query->getResult();
        $resultArray = array();
        foreach ($queryResult as $rec)
        {
            $qb = $this->em->createQueryBuilder();
            $qb->select('u')
                ->from('BlogPost\Entity\BlogPost', 'u')
                ->where('u.post_id = ?1 and u.created=?2')
                ->groupBy('u.id, u.key_api, u.post_id, u.title, u.date_publishing, u.url, u.author, u.category, u.avg_session_duration, u.total_social_count, u.view, u.social_count_facebook, u.social_count_twitter, u.social_count_linkedin, u.social_count_google_plus, u.words, u.created, u.status');
            $qb->setParameter(1, $rec['post_id']);
            $qb->setParameter(2, $rec['upd']);
            $blogpost = $qb->getQuery()->getSingleResult();
            $resultArray[] = $blogpost->getExpectedArray();
        }
        return $resultArray;
    }
}
