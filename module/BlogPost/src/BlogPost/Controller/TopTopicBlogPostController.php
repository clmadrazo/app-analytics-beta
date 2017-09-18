<?php

namespace BlogPost\Controller;

use App\Mvc\Controller\RestfulController;
use Zend\View\Model\JsonModel;
use Zend\Mvc\MvcEvent;
use BlogPost\Entity\BlogPost;

/**
 *
 */
class TopTopicBlogPostController extends RestfulController {
    protected $_allowedMethod = "post";
    protected $em;
    protected $date1;
    protected $date2;
    protected $key;
    protected $data = array();

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
        $this->date1 = $requestData[0]['date1'].' 00:00:00';
        $this->date2 = $requestData[0]['date2'].' 23:59:59';
        $categoryData = null;
        if(isset($requestData[0]['category']))
            $categoryData = $requestData[0]['category'];

        $blogposts = $this->_CountBlogPostByCategory($this->key,$categoryData);
//        if(empty($blogposts)) {
//            $this->date1 = '0000-00-00 00:00:00';
//            $blogposts = $this->_CountBlogPostByCategory($this->key,$categoryData);
//        }
        foreach($blogposts as $blogpost)
        {
            $cont = $blogpost['cont'];
            $category = $blogpost['category'];

            $pos = strpos($category, ",");
            if($pos == false)
            {
                $blogpostsSocial = $this->_SocialBlogPostByCategory($this->key,$category);
                $this->take($blogpostsSocial,$category);
            }
            else
            {
                $parts = explode(",", $category);
                foreach($parts as $part)
                {
                    $blogpostsSocial = $this->_SocialBlogPostByCategory($this->key,$part);
                    $this->take($blogpostsSocial,$part);
                }
            }

        }

        $this->data = $this->Top($this->data);
        if (!empty($this->data)) {
            $return = $this->data;
            $this->getResponse()->setStatusCode(200);
        } else {
            $this->getResponse()->setStatusCode(404);
            $return = array("errors" => \BlogPost\Entity\BlogPost::ERR_BLOGPOST_NOT_FOUND);
        }

        return new JsonModel(array("result" => $return));
    }

    private function take($blogpostsSocial,$category)
    {
        if($blogpostsSocial !=null)
        {
            $item = array();
            $item['category'] = $category;
            $bp = $this->_CountBlogPostByCategory($this->key,$category);
            $item['total_posts'] = $bp['0']['cont'];
            $sumFacebook = null;
            $sumTwitter = null;
            $sumLinkedin = null;
            $sumGPlus = null;
            $sumShares = null;
            foreach($blogpostsSocial as $bpSocial)
            {
                $sumShares += $bpSocial->getTotalSocialCount();
                $sumFacebook += $bpSocial->getSocialCountFacebook();
                $sumTwitter += $bpSocial->getSocialCountTwitter();
                $sumLinkedin += $bpSocial->getSocialCountLinkedin();
                $sumGPlus += $bpSocial->getSocialCountGooglePlus();
            }
            $item['shares'] = $sumShares;
            $item['facebook'] = $sumFacebook;
            $item['twitter'] = $sumTwitter;
            $item['linkedin'] = $sumLinkedin;
            $item['google_plus'] = $sumGPlus;

            foreach($this->data as $d)
            {
                if($category == $d['category']){
                    return null;
                }
            }
            $div = $item['shares']/$item['total_posts'];
            $item['shares_posts'] = round($div, 2);
            array_push($this->data, $item);
        }
    }

    private function Top($data){
        usort(
            $data,
            function( $a, $b ) {
                if( $a['shares'] == $b['shares'] ) return 0;
                return ( ( $a['shares'] > $b['shares'] ) ? -1 : 1 );
            }
        );
        return $data;
    }

    //Selects the number of posts published in a category between two dates
    private function _CountBlogPostByCategory($key,$category)
    {
        if($category == null)
        {
//            $query = $this->em->createQuery("SELECT COUNT(DISTINCT u.post_id) cont, u.category
//                                      FROM BlogPost\Entity\BlogPost u
//                                      WHERE u.key_api=?1 and u.status <> 0 and u.created BETWEEN ?2 and ?3
//                                      group by u.category");
            $query = $this->em->createQuery("SELECT COUNT(DISTINCT u.post_id) cont, u.category
                                      FROM BlogPost\Entity\BlogPost u
                                      WHERE u.key_api=?1 and u.status <> 0 and u.date_publishing BETWEEN ?2 and ?3
                                      group by u.category");
        }
        else
        {
//            $query = $this->em->createQuery("SELECT COUNT(DISTINCT u.post_id) cont, u.category
//                                      FROM BlogPost\Entity\BlogPost u
//                                      WHERE u.key_api=?1 and u.category like '%$category%' and u.status <> 0 and u.created BETWEEN ?2 and ?3");
            $query = $this->em->createQuery("SELECT COUNT(DISTINCT u.post_id) cont, u.category
                                      FROM BlogPost\Entity\BlogPost u
                                      WHERE u.key_api=?1 and u.category like '%$category%' and u.status <> 0 and u.date_publishing BETWEEN ?2 and ?3");
        }
        $query->setParameter(1,$key);
        $query->setParameter(2,$this->date1);
        $query->setParameter(3,$this->date2);
        return $query->getArrayResult();
    }

    //Selects a category data between two dates
    private function _SocialBlogPostByCategory($key,$category)
    {
//        $query = $this->em->createQuery("SELECT bp.post_id, max(bp.created) upd
//                                      FROM BlogPost\Entity\BlogPost bp
//                                      WHERE bp.key_api=?1 and bp.category like '%$category%' and bp.status <> 0 and bp.created BETWEEN ?2 and ?3
//                                      group by bp.post_id");
        $query = $this->em->createQuery("SELECT bp.post_id, max(bp.created) upd
                                      FROM BlogPost\Entity\BlogPost bp
                                      WHERE bp.key_api=?1 and bp.category like '%$category%' and bp.status <> 0 and bp.date_publishing BETWEEN ?2 and ?3
                                      group by bp.post_id");
        $query->setParameter(1, $key);
        $query->setParameter(2,$this->date1);
        $query->setParameter(3,$this->date2);
        $queryResult =  $query->getResult();
        foreach ($queryResult as $rec)
        {
            if($rec['post_id'] == null)
                break;
            $q = $this->em->createQuery("SELECT partial bp.{id,category,social_count_facebook,social_count_twitter,social_count_google_plus,social_count_linkedin,total_social_count}
                                      FROM BlogPost\Entity\BlogPost bp
                                      WHERE bp.post_id = ?1 and bp.created=?2");
            $q->setParameter(1, $rec['post_id']);
            $q->setParameter(2, $rec['upd']);
            $q->setMaxResults(1);
            $blogpost = $q->getSingleResult();
            $resultArray[] = $blogpost;
        }
        if(isset($resultArray))
            return $resultArray;
        else return null;
    }
}
