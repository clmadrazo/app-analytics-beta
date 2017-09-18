<?php

namespace BlogPost\Controller;

use App\Mvc\Controller\RestfulController;
use Zend\View\Model\JsonModel;
use Zend\Mvc\MvcEvent;
use BlogPost\Entity\BlogPost;

/**
 *
 */
class TopAuthorBlogPostController extends RestfulController {
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
        $cm->setTableName('ca_blog_posts_user_'.$user);
        $date1 = $requestData[0]['date1'].' 00:00:00';
        $date2 = $requestData[0]['date2'].' 23:59:59';
        $authorData = null;
        if(isset($requestData[0]['author']))
            $authorData = $requestData[0]['author'];

        $data = array();
        $blogposts = $this->CountBlogPost_Shares_ByAuthor($key,$authorData,$date1,$date2);
//        if(empty($blogPosts)) {
//            $date1 = '0000-00-00 00:00:00';
//            $blogposts = $this->CountBlogPost_Shares_ByAuthor($key,$authorData,$date1,$date2);
//        }
        $blogposts = $this->Top($blogposts);
        foreach($blogposts as $blogpost)
        {
            $cont = $blogpost['cont'];
            $author = $blogpost['author'];
            $shares = $blogpost['shares'];
            $blogpostsSocial = $this->_SocialBlogPostByAuthor($key,$author,$date1,$date2);
            if($blogpostsSocial !=null)
            {
                foreach($blogpostsSocial as $bpSocial)
                {
                    $item = array();
                    //Social data from the last post of the author
                    $item['author'] = $bpSocial->getAuthor();
                    $item['title'] = $bpSocial->getTitle();
                    $item['shares'] = $bpSocial->getTotalSocialCount();
                    $item['facebook'] = $bpSocial->getSocialCountFacebook();
                    $item['twitter'] = $bpSocial->getSocialCountTwitter();
                    $item['linkedin'] = $bpSocial->getSocialCountLinkedin();
                    $item['google_plus'] = $bpSocial->getSocialCountGooglePlus();
                    $item['shares_posts'] = round($bpSocial->getTotalSocialCount()/$cont, 2);
                    //Aggregated data
                    $item['total_shares_author'] = $shares;
                    array_push($data, $item);
                }
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

    //Select the amount of post published by a / each author between two dates
    private function CountBlogPost_Shares_ByAuthor($key,$author,$date1,$date2)
    {
        if($author == null)
        {
//            $query = $this->em->createQuery("SELECT u.post_id, max(u.created) upd
//                                      FROM BlogPost\Entity\BlogPost u
//                                      WHERE u.key_api=?1 and u.status <> 0 and u.created BETWEEN ?3 and ?4
//                                      group by u.post_id");
            $query = $this->em->createQuery("SELECT u.post_id, max(u.created) upd
                                      FROM BlogPost\Entity\BlogPost u
                                      WHERE u.key_api=?1 and u.status <> 0 and u.author <> ?5 and u.date_publishing BETWEEN ?3 and ?4
                                      group by u.post_id");
        }
        else
        {
//            $query = $this->em->createQuery("SELECT u.post_id, max(u.created) upd
//                                      FROM BlogPost\Entity\BlogPost u
//                                      WHERE u.key_api=?1 and u.status <> 0 and u.author = ?2 and u.created BETWEEN ?3 and ?4");
            $query = $this->em->createQuery("SELECT u.post_id, max(u.created) upd
                                      FROM BlogPost\Entity\BlogPost u
                                      WHERE u.key_api=?1 and u.status <> 0 and u.author = ?2 and u.date_publishing BETWEEN ?3 and ?4");
            $query->setParameter(2,$author);
        }
        $query->setParameter(1,$key);
        $query->setParameter(3,$date1);
        $query->setParameter(4,$date2);
        $query->setParameter(5,"");
        $queryResult =  $query->getResult();

        $resultArray = array();

        foreach ($queryResult as $rec)
        {
            $qb = $this->em->createQueryBuilder();
            $qb->select('u')
                ->from('BlogPost\Entity\BlogPost', 'u')
                ->where('u.post_id = ?1 and u.created=?2')
                ->groupBy('u');
            $qb->setParameter(1, $rec['post_id']);
            $qb->setParameter(2, $rec['upd']);
            $blogpost = $qb->getQuery()->getSingleResult();

            $resultArray[] = $blogpost;
        }
        $response = $authors = array();

        foreach ($resultArray as $result)
        {
            $aux_author = $result->getAuthor();
            if(!in_array($aux_author, $authors)){
                $item = array();
                $item['author'] = $aux_author;
                array_push($authors,$aux_author);
                $social_total = $cont = 0;
                foreach ($resultArray as $r){
                    if($r->getAuthor() == $aux_author) {
                        $cont++;
                        $social_total += $r->getTotalSocialCount();
                    }
                }
                $item['cont'] = $cont;
                $item['shares'] = $social_total;
                array_push($response,$item);
            }
        }
        return $response;
    }

    //Selects social data from the last post of an author between two dates
    private function _SocialBlogPostByAuthor($key,$author,$date1,$date2)
    {
        //Select the last post of an author
//        $query1 = $this->em->createQuery("SELECT bp.post_id, max(bp.date_publishing) upub
//                                      FROM BlogPost\Entity\BlogPost bp
//                                      WHERE bp.key_api=?1 and bp.author=?2
//                                      group by bp.post_id");
//        $query1->setParameter(1, $key);
//        $query1->setParameter(2, $author);
        $query1 = $this->em->createQuery("SELECT bp.post_id, max(bp.date_publishing) upub
                                      FROM BlogPost\Entity\BlogPost bp
                                      WHERE bp.key_api=?1 and bp.author=?2 and bp.date_publishing BETWEEN ?3 and ?4
                                      group by bp.post_id");
        $query1->setParameter(1, $key);
        $query1->setParameter(2, $author);
        $query1->setParameter(3,$date1);
        $query1->setParameter(4,$date2);
        $queryResult1 =  $query1->getResult();
        foreach ($queryResult1 as $rec1)
        {
            if($rec1['post_id'] == null)
                break;
            //Select the last update of the last post in a date range
//            $query2 = $this->em->createQuery("SELECT bp.post_id, bp.date_publishing,max(bp.created) upd
//                                      FROM BlogPost\Entity\BlogPost bp
//                                      WHERE bp.post_id=?1 and bp.date_publishing=?2 and bp.status <> 0 and bp.created BETWEEN ?3 and ?4
//                                      group by bp.post_id");
//            $query2->setParameter(1, $rec1['post_id']);
//            $query2->setParameter(2, $rec1['upub']);
//            $query2->setParameter(3,$date1);
//            $query2->setParameter(4,$date2);
            $query2 = $this->em->createQuery("SELECT bp.post_id, bp.date_publishing,max(bp.created) upd
                                      FROM BlogPost\Entity\BlogPost bp
                                      WHERE bp.post_id=?1 and bp.date_publishing=?2 and bp.status <> 0
                                      group by bp.post_id");
            $query2->setParameter(1, $rec1['post_id']);
            $query2->setParameter(2, $rec1['upub']);
            $blogpost = $query2->getResult();
        }
        if(isset($blogpost))
            $queryResult2 =  $blogpost;
        else return null;
        foreach ($queryResult2 as $rec2)
        {
            if($rec2['post_id'] == null)
                break;
            $q = $this->em->createQuery("SELECT partial bp.{id,author,title,total_social_count,social_count_facebook,social_count_twitter,social_count_google_plus,social_count_linkedin}
                                      FROM BlogPost\Entity\BlogPost bp
                                      WHERE bp.post_id = ?1 and bp.date_publishing=?2 and bp.created=?3");
            $q->setParameter(1, $rec2['post_id']);
            $q->setParameter(2, $rec2['date_publishing']);
            $q->setParameter(3, $rec2['upd']);
            $q->setMaxResults(1);
            $blogpost = $q->getSingleResult();
            $resultArray[] = $blogpost;
        }
        if(isset($resultArray))
            return $resultArray;
        else return null;
    }
}
