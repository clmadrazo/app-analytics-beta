<?php

namespace BlogPost\Controller;

use App\Mvc\Controller\RestfulController;
use Zend\View\Model\JsonModel;
use Zend\Mvc\MvcEvent;
use BlogPost\Entity\BlogPost;

/**
 *
 */
class TopBlogPostController extends RestfulController {

    protected $_allowedMethod = "post";
    protected $em;

    public function indexAction() {
        $this->em = $this->getEntityManager();
        $request = $this->getRequest();
        $requestData = $this->processBodyContent($request);
        $key = $requestData[0]['key_api'];
        $parts = explode("-", $key);
        $first = array_shift($parts);
        $user = $first;
        $cm = $this->em->getClassMetadata('BlogPost\Entity\BlogPost');
        $cm->setTableName('ca_blog_posts_user_' . $user);
        $date1 = $requestData[0]['date1'];
        $date2 = $requestData[0]['date2'];

        $data = array();

        $blogpostsSocial = $this->_SocialTopBlogPost($key, $date1, $date2);
//        if(empty($blogpostsSocial)) {
//            $date1 = '0000-00-00 00:00:00';
//            $blogpostsSocial = $this->_SocialTopBlogPost($key,$date1,$date2);
//        }
        if ($blogpostsSocial != null) {
            foreach ($blogpostsSocial as $bpSocial) {
                $item = array();
                $item['title'] = $bpSocial->getTitle();
                $item['date'] = $bpSocial->getDatePublishing();
                $item['time'] = $bpSocial->getAvgSessionDuration();
                $item['words'] = $bpSocial->getWords();
                $item['facebook'] = $bpSocial->getSocialCountFacebook();
                $item['twitter'] = $bpSocial->getSocialCountTwitter();
                $item['linkedin'] = $bpSocial->getSocialCountLinkedin();
                $item['google_plus'] = $bpSocial->getSocialCountGooglePlus();
                $item['shares'] = $bpSocial->getTotalSocialCount();
                $item['view'] = $bpSocial->getView();
                array_push($data, $item);
            }
        }

        if (!empty($data)) {
            $return = $data;
            $this->getResponse()->setStatusCode(200);
        } else {
            $this->getResponse()->setStatusCode(404);
            $return = array("errors" => \BlogPost\Entity\BlogPost::ERR_BLOGPOST_NOT_FOUND);
        }

        return new JsonModel(array("result" => $return));
    }

    //Select the top post in a date range
    private function _SocialTopBlogPost($key, $date1, $date2) {
//        $query = $this->em->createQuery("SELECT bp.post_id, max(bp.created) upd
//                                      FROM BlogPost\Entity\BlogPost bp
//                                      WHERE bp.key_api=?1 and bp.status <> 0 and bp.created BETWEEN ?2 and ?3
//                                      group by bp.post_id
//                                      ORDER BY bp.total_social_count DESC,bp.view DESC");
        $query = $this->em->createQuery("SELECT bp.post_id, bp.total_social_count, bp.view, max(bp.created) upd
                                      FROM BlogPost\Entity\BlogPost bp
                                      WHERE bp.key_api=?1 and bp.status <> 0 and bp.date_publishing BETWEEN ?2 and ?3
                                      GROUP BY bp.post_id, bp.total_social_count, bp.view
                                      ORDER BY bp.total_social_count DESC,bp.view DESC");
        $query->setParameter(1, $key);
        $query->setParameter(2, $date1 . ' 00:00:00');
        $query->setParameter(3, $date2 . ' 23:59:59');
        $queryResult = $query->getResult();

        $addedPosts = array();
        foreach ($queryResult as $rec) {
            if ($rec['post_id'] == null) {
                break;
            }
            $q = $this->em->createQuery("SELECT partial bp.{id,title,post_id,date_publishing,avg_session_duration,words,social_count_facebook,social_count_twitter,social_count_google_plus,social_count_linkedin,total_social_count,view}
                                      FROM BlogPost\Entity\BlogPost bp
                                      WHERE bp.post_id = ?1 and bp.created=?2");
            $q->setParameter(1, $rec['post_id']);
            $q->setParameter(2, $rec['upd']);
            $q->setMaxResults(1);
            $blogpost = $q->getSingleResult();
            
            if (!in_array($blogpost->getPostId(), $addedPosts)) {
                $resultArray[] = $blogpost;
                $addedPosts[] = $blogpost->getPostId();
            }
        }
        if (isset($resultArray)) {
            return $resultArray;
        } else {
            return null;
        }
    }

}
