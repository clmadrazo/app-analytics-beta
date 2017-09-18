<?php
namespace BlogPost\Entity;

use App\Mvc\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\EntityManager;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Entity Class representing a BlogPost of our Application.
 *
 * @ORM\Entity
 * @ORM\Table(name="ca_blog_posts")
 */
class BlogPost extends BaseEntity
{
    const ERR_BLOGPOST_NOT_FOUND = "Blog Post doesn't exists";
    const ERR_BLOGPOST_ALREADY_EXISTS_WITH_THIS_UPDATE = "Blog Post already exists whith this update";
    const ERR_INFORMATION_NOT_FOUND = "Information not found";


    /**
     * Primary Identifier
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * Key Api
     *
     * @ORM\Column(type="string")
     */
    protected $key_api;

    /**
     * Post Id
     *
     * @ORM\Column(type="integer")
     */
    protected $post_id;

    /**
     * Title
     *
     * @ORM\Column(type="string")
     */
    protected $title = "";

    /**
     * Date Publishing
     *
     * @ORM\Column(type="date")
     */
    protected $date_publishing;

    /**
     * URL
     *
     * @ORM\Column(type="string")
     */
    protected $url;

    /**
     * Author
     *
     * @ORM\Column(type="string")
     */
    protected $author;

    /**
     * Category
     *
     * @ORM\Column(type="string")
     */
    protected $category;

    /**
     * Average Session Duration
     *
     * @ORM\Column(type="string")
     */
    protected $avg_session_duration;

    /**
     * Total Social Count
     *
     * @ORM\Column(type="integer")
     */
    protected $total_social_count;

    /**
     * View
     *
     * @ORM\Column(type="integer")
     */
    protected $view;

    /**
     * Social Count Facebook
     *
     * @ORM\Column(type="integer")
     */
    protected $social_count_facebook;

    /**
     * Social Count Twitter
     *
     * @ORM\Column(type="integer")
     */
    protected $social_count_twitter;

    /**
     * Social Count Linkedin
     *
     * @ORM\Column(type="integer")
     */
    protected $social_count_linkedin;

//    /**
//     * Social Count Reddit
//     *
//     * @ORM\Column(type="integer")
//     */
//    protected $social_count_reddit;
//
//    /**
//     * Social Count Stumble Upon
//     *
//     * @ORM\Column(type="integer")
//     */
//    protected $social_count_stumble_upon;

    /**
     * Social Count Google Plus
     *
     * @ORM\Column(type="integer")
     */
    protected $social_count_google_plus;

//    /**
//     * Social Count Pinterest
//     *
//     * @ORM\Column(type="integer")
//     */
//    protected $social_count_pinterest;
//
//    /**
//     * Social Count Flattr
//     *
//     * @ORM\Column(type="integer")
//     */
//    protected $social_count_flattr;
//
//    /**
//     * Social Count XING
//     *
//     * @ORM\Column(type="integer")
//     */
//    protected $social_count_XING;

//    /**
//     * Sync_date date
//     *
//     * @ORM\Column(type="datetime")
//     */
//    protected $sync_date;

    /**
     * Words
     *
     * @ORM\Column(type="integer")
     */
    protected $words;

    /**
     * Status
     *
     * @ORM\Column(type="integer")
     */
    protected $status;

    /**
     * Created date
     *
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $keyApi
     * @return BlogPost
     */
    public function setKeyApi($keyApi)
    {
        $this->key_api = $keyApi;
        return $this;
    }

    /**
     * @return String
     */
    public function getKeyApi()
    {
        return $this->key_api;
    }

    /**
     * @param int $postId
     * @return BlogPost
     */
    public function setPostId($postId)
    {
        $this->post_id = $postId;
        return $this;
    }

    /**
     * @return int
     */
    public function getPostId()
    {
        return $this->post_id;
    }

    /**
     * @param string $title
     * @return BlogPost
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return String
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param String $datePublishing
     * @example 2001-12-23
     * @return BlogPost
     */
    public function setDatePublishing($datePublishing)
    {
        $this->date_publishing = new DateTime($datePublishing);
        return $this;
    }

    /**
     * @return Object date
     */
    public function getDatePublishing()
    {
        return $this->date_publishing;
    }

    /**
     * @param string $url
     * @return BlogPost
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return String
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $author
     * @return BlogPost
     */
    public function setAuthor($author)
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @return String
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param int $category
     * @return BlogPost
     */
    public function setCategory($category)
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @return String
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param string $avgSessionDuration
     * @return BlogPost
     */
    public function setAvgSessionDuration($avgSessionDuration)
    {
        $this->avg_session_duration = $avgSessionDuration;
        return $this;
    }

    /**
     * @return String
     */
    public function getAvgSessionDuration()
    {
        return $this->avg_session_duration;
    }

    /**
     * @param int $totalSocialCount
     * @return BlogPost
     */
    public function setTotalSocialCount($totalSocialCount)
    {
        $this->total_social_count = $totalSocialCount;
        return $this;
    }

    /**
     * @return String
     */
    public function getTotalSocialCount()
    {
        return $this->total_social_count;
    }

    /**
     * @param int $view
     * @return BlogPost
     */
    public function setView($view)
    {
        $this->view = $view;
        return $this;
    }

    /**
     * @return String
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * @param int $socialCountFacebook
     * @return BlogPost
     */
    public function setSocialCountFacebook($socialCountFacebook)
    {
        $this->social_count_facebook = $socialCountFacebook;
        return $this;
    }

    /**
     * @return String
     */
    public function getSocialCountFacebook()
    {
        return $this->social_count_facebook;
    }

    /**
     * @param int $socialCountTwitter
     * @return BlogPost
     */
    public function setSocialCountTwitter($socialCountTwitter)
    {
        $this->social_count_twitter = $socialCountTwitter;
        return $this;
    }

    /**
     * @return String
     */
    public function getSocialCountTwitter()
    {
        return $this->social_count_twitter;
    }

    /**
     * @param int $socialCountLinkedin
     * @return BlogPost
     */
    public function setSocialCountLinkedin($socialCountLinkedin)
    {
        $this->social_count_linkedin = $socialCountLinkedin;
        return $this;
    }

    /**
     * @return String
     */
    public function getSocialCountLinkedin()
    {
        return $this->social_count_linkedin;
    }

//    /**
//     * @param int $socialCountReddit
//     * @return BlogPost
//     */
//    public function setSocialCountReddit($socialCountReddit)
//    {
//        $this->social_count_reddit = $socialCountReddit;
//        return $this;
//    }
//
//    /**
//     * @return String
//     */
//    public function getSocialCountReddit()
//    {
//        return $this->social_count_reddit;
//    }
//
//    /**
//     * @param int $socialCountStumbleUpon
//     * @return BlogPost
//     */
//    public function setSocialCountStumbleUpon($socialCountStumbleUpon)
//    {
//        $this->social_count_stumble_upon = $socialCountStumbleUpon;
//        return $this;
//    }
//
//    /**
//     * @return String
//     */
//    public function getSocialCountStumbleUpon()
//    {
//        return $this->social_count_stumble_upon;
//    }

    /**
     * @param int $socialCountGooglePlus
     * @return BlogPost
     */
    public function setSocialCountGooglePlus($socialCountGooglePlus)
    {
        $this->social_count_google_plus = $socialCountGooglePlus;
        return $this;
    }

    /**
     * @return String
     */
    public function getSocialCountGooglePlus()
    {
        return $this->social_count_google_plus;
    }

//    /**
//     * @param int $socialCountPinterest
//     * @return BlogPost
//     */
//    public function setSocialCountPinterest($socialCountPinterest)
//    {
//        $this->social_count_pinterest = $socialCountPinterest;
//        return $this;
//    }
//
//    /**
//     * @return String
//     */
//    public function getSocialCountPinterest()
//    {
//        return $this->social_count_pinterest;
//    }
//
//    /**
//     * @param int $socialCountFlattr
//     * @return BlogPost
//     */
//    public function setSocialCountFlattr($socialCountFlattr)
//    {
//        $this->social_count_flattr = $socialCountFlattr;
//        return $this;
//    }
//
//    /**
//     * @return String
//     */
//    public function getSocialCountFlattr()
//    {
//        return $this->social_count_flattr;
//    }
//
//    /**
//     * @param int $socialCountXING
//     * @return BlogPost
//     */
//    public function setSocialCountXING($socialCountXING)
//    {
//        $this->social_count_XING = $socialCountXING;
//        return $this;
//    }
//
//    /**
//     * @return String
//     */
//    public function getSocialCountXING()
//    {
//        return $this->social_count_XING;
//    }

//    /**
// * @param String $sync_date
// * @example 2001-12-23 00:03:25
// * @return BlogPost
// */
//    public function setSyncDate($sync_date)
//    {
//        $this->sync_date = new DateTime($sync_date);
//        return $this;
//    }
//
//    /**
//     * @return Object datetime
//     */
//    public function getSyncDate()
//    {
//        return $this->sync_date;
//    }

    /**
     * @param String $created
     * @example 2001-12-23 00:03:25
     * @return BlogPost
     */
    public function setCreated($created)
    {
        $this->created = new DateTime($created);
        return $this;
    }

    /**
     * @return Object datetime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param int $words
     * @return BlogPost
     */
    public function setWords($words)
    {
        $this->words = $words;
        return $this;
    }

    /**
     * @return int
     */
    public function getWords()
    {
        return $this->words;
    }

    /**
     * @param int $status
     * @return BlogPost
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @see App\Mvc\Entity\BaseEntity
     */
    public function getExpectedArray($params = array())
    {
        return array(
            'id'                        => $this->getId(),
            'key_api'                   => $this->getKeyApi(),
            'post_id'                   => $this->getPostId(),
            'title'                     => $this->getTitle(),
            'date_publishing'           => $this->getDatePublishing(),
            'url'                       => $this->getUrl(),
            'author'                    => $this->getAuthor(),
            'category'                  => $this->getCategory(),
            'avg_session_duration'      => $this->getAvgSessionDuration(),
            'total_social_count'        => $this->getTotalSocialCount(),
            'view'                      => $this->getView(),
            'social_count_facebook'     => $this->getSocialCountFacebook(),
            'social_count_twitter'      => $this->getSocialCountTwitter(),
            'social_count_linkedin'     => $this->getSocialCountLinkedin(),
//            'social_count_reddit'       => $this->getSocialCountReddit(),
//            'social_count_stumble_upon' => $this->getSocialCountStumbleUpon(),
            'social_count_google_plus'  => $this->getSocialCountGooglePlus(),
//            'social_count_pinterest'    => $this->getSocialCountPinterest(),
//            'social_count_flattr'       => $this->getSocialCountFlattr(),
//            'social_count_XING'         => $this->getSocialCountXING(),
//            'sync_date'                   => $this->getSyncDate(),
            'words'                     => $this->getWords(),
            'status'                     => $this->getStatus(),
            'created'                     => $this->getCreated(),
        );
    }
}
