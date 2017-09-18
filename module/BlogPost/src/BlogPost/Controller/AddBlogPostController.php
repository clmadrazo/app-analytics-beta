<?php
namespace BlogPost\Controller;

use App\Mvc\Controller\RestfulController;
use Zend\View\Model\JsonModel;
use Zend\Mvc\MvcEvent;
use BlogPost\Entity\BlogPost;


/**
 * This controller handles all post module requests.
 * 
 */
class AddBlogPostController extends RestfulController
{  
    protected $_allowedMethod = "post";
    protected $requestData;
    protected $_em = null;
    protected $post_id;
    protected $avg_session_duration;
    protected $total_social_count;
    protected $words;
    protected $date_publishing;
    protected $view;
    protected $title;
    protected $author;
    protected $url;
    protected $category;
    protected $key_api;

    public function indexAction()
    {
        $this->_em = $this->getEntityManager();
        $request = $this->getRequest();
        $this->requestData = $this->processBodyContent($request);
        $this->post_id = $this->requestData[0]['post_id'];
        $this->title = $this->requestData[0]['title'];
        $this->author = $this->requestData[0]['author'];
        $this->category = $this->requestData[0]['category'];
        $this->url = $this->requestData[0]['url'];
        $this->avg_session_duration = $this->requestData[0]['avg_session_duration'];
        $this->date_publishing = $this->requestData[0]['date_publishing'];
        $this->total_social_count = $this->requestData[0]['total_social_count'];
        $this->key_api = $this->requestData[0]['key_api'];
        $parts = explode("-", $this->key_api);
        $first = array_shift($parts);
        $user = $first;
        $cm = $this->_em->getClassMetadata('BlogPost\Entity\BlogPost');

        $cm->setTableName('ca_blog_posts_user_'.$user);
        $this->words = $this->requestData[0]['words'];
        $this->view = $this->requestData[0]['view'];

        $post = $this->_em->getRepository('BlogPost\Entity\BlogPost')->findOneBy(array('post_id' => $this->post_id, 'key_api' => $this->key_api), array('id' => 'DESC'));
        $return = null;
        if (empty($post)) {
            $create = $this->create($post);
            $return = array($create);
            $this->getResponse()->setStatusCode(200);
        } else {
            $utf8_ansi2 = array("00bf" =>"¿","\u2026" =>"...","u00a1" =>"¡","\u00c0" =>"À","\u00c1" =>"Á","\u00c2" =>"Â","\u00c3" =>"Ã","\u00c4" =>"Ä","\u00c5" =>"Å","\u00c6" =>"Æ","\u00c7" =>"Ç","\u00c8" =>"È","\u00c9" =>"É","\u00ca" =>"Ê","\u00cb" =>"Ë","\u00cc" =>"Ì","\u00cd" =>"Í","\u00ce" =>"Î","\u00cf" =>"Ï","\u00d1" =>"Ñ","\u00d2" =>"Ò","\u00d3" =>"Ó","\u00d4" =>"Ô","\u00d5" =>"Õ","\u00d6" =>"Ö","\u00d8" =>"Ø","\u00d9" =>"Ù","\u00da" =>"Ú","\u00db" =>"Û","\u00dc" =>"Ü","\u00dd" =>"Ý","\u00df" =>"ß","\u00e0" =>"à","\u00e1" =>"á","\u00e2" =>"â","\u00e3" =>"ã","\u00e4" =>"ä","\u00e5" =>"å","\u00e6" =>"æ","\u00e7" =>"ç","\u00e8" =>"è","\u00e9" =>"é","\u00ea" =>"ê","\u00eb" =>"ë","\u00ec" =>"ì","\u00ed" =>"í","\u00ee" =>"î","\u00ef" =>"ï","\u00f0" =>"ð","\u00f1" =>"ñ","\u00f2" =>"ò","\u00f3" =>"ó","\u00f4" =>"ô","\u00f5" =>"õ","\u00f6" =>"ö","\u00f8" =>"ø","\u00f9" =>"ù","\u00fa" =>"ú","\u00fb" =>"û","\u00fc" =>"ü","\u00fd" =>"ý","\u00ff" =>"ÿ");
            $title = strtr($this->title, $utf8_ansi2);
            $avg_duration = ($this->avg_session_duration != "") ? $this->avg_session_duration : "00:00:00";
            $view = ($this->view != "") ? $this->view : 0;
            $date_post = $post->getDatePublishing()->format('Y-m-d');
            $duration_post = $post->getAvgSessionDuration();
            $social_post = $post->getTotalSocialCount();
            $words_post = $post->getWords();
            $view_post = $post->getView();
            $title_post = $post->getTitle();
            $author_post = $post->getAuthor();
            $category_post = $post->getCategory();
            $url_post = $post->getUrl();
            if($category_post != $this->category || $author_post != $this->author || $url_post != $this->url || $date_post != $this->date_publishing || $title_post != $title || $duration_post != $avg_duration || $view_post != $view || $social_post != $this->total_social_count || $words_post != $this->words)
            {
                $create = $this->create($post);
                $return = array($create);
                $this->getResponse()->setStatusCode(200);
            }
            else
            {
                $this->getResponse()->setStatusCode(404);
                $return = array("errors" => \BlogPost\Entity\BlogPost::ERR_BLOGPOST_ALREADY_EXISTS_WITH_THIS_UPDATE);
            }
        }
        
        return new JsonModel(array("result" => $return));
    }

    public function create($post) {
            $parts = explode("-", $this->key_api);
            $first = array_shift($parts);
            $user = $first;
            $cm = $this->_em->getClassMetadata('BlogPost\Entity\BlogPost');
            $cm->setTableName('ca_blog_posts_user_'.$user);
            $post = new \BlogPost\Entity\BlogPost;
            $post->setPostId($this->post_id);
            $post->setKeyApi($this->key_api);
            $post->setTitle($this->title);
            $post->setDatePublishing($this->date_publishing);
            $post->setUrl($this->url);
            $post->setAuthor($this->author);
            $post->setCategory($this->category);
            $post->setAvgSessionDuration($this->avg_session_duration);
            $post->setTotalSocialCount($this->total_social_count);
            $view = ($this->view != "") ? $this->view : 0;
            $post->setView($view);
            $post->setSocialCountFacebook($this->requestData[0]['social_count_facebook']);
            $post->setSocialCountTwitter($this->requestData[0]['social_count_twitter']);
            $post->setSocialCountLinkedin($this->requestData[0]['social_count_linkedin']);
//            $post->setSocialCountReddit($this->requestData[0]['social_count_reddit']);
//            $post->setSocialCountStumbleUpon($this->requestData[0]['social_count_stumble_upon']);
            $post->setSocialCountGooglePlus($this->requestData[0]['social_count_google_plus']);
//            $post->setSocialCountPinterest($this->requestData[0]['social_count_pinterest']);
//            $post->setSocialCountFlattr($this->requestData[0]['social_count_flattr']);
//            $post->setSocialCountXING($this->requestData[0]['social_count_XING']);
            $post->setWords($this->words);
            $post->setStatus(1);
            $post->setCreated($this->requestData[0]['created']);
            $this->_em->persist($post);
            $this->_em->flush();
            $this->getResponse()->setStatusCode(200);
            return $post->getExpectedArray();
    }
}

