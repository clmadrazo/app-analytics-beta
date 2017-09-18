<?php

namespace BlogPost\Controller;

use App\Mvc\Controller\RestfulController;
use Zend\View\Model\JsonModel;
use Zend\Mvc\MvcEvent;
use BlogPost\Entity\BlogPost;

/**
 *
 */
class AnalyticsBlogPostController extends RestfulController {
    protected $_allowedMethod = "post";
    protected $em;

    public function indexAction() {
        $this->em = $this->getEntityManager();
        $request = $this->getRequest();
        $requestData = $this->processBodyContent($request);
        $key = $requestData[0]['key_api'];
        $date1 = $requestData[0]['date1'].' 00:00:00';
        $date2 = $requestData[0]['date2'].' 23:59:59';
        $parts = explode("-", $key);
        $first = array_shift($parts);
        $user = $first;
        $cm = $this->em->getClassMetadata('BlogPost\Entity\BlogPost');
        $cm->setTableName('ca_blog_posts_user_'.$user);
        $select = null;
        $data = array();

        $responses = ($this->_Time_Words_View_BlogPost($key,$date1,$date2) != null) ? $this->_Time_Words_View_BlogPost($key,$date1,$date2) : array();
        $cantPost = count($responses);
        if($responses != null){
            $data["total_time"] = $this->timeTotal($responses);
            $time = $this->timeTotal($responses);
        }
        else {
            $data["total_time"] = $time = "00:00:00";
        }
        $cantP = 0;
        foreach($responses as $response)
        {
            $cantP += $response->getView();
        }
        $data["total_persons"] = $cantP;

        $seconds = $this->hoursToSecods($time);
        if($cantPost != 0)
            $avg = floor($seconds/$cantPost);
        else
            $avg = 0;
        $data["tt_person"] = $this->conversorSegundosHoras($avg);
        $total_segundos = 0;
        foreach($responses as $response)
        {
            $time = $response->getAvgSessionDuration();
            $seconds = $this->hoursToSecods($time);
            $words = $response->getWords();
            $seconds_word = $seconds/$words;
            $seconds_500words = $seconds_word*500;
            $total_segundos += $seconds_500words;
        }
        if($cantPost != 0)
            $avg_seconds_500words = floor($total_segundos/$cantPost);
        else
            $avg_seconds_500words = 0;
        $data["tt_500"] = $this->conversorSegundosHoras($avg_seconds_500words);

        if (!empty($data)) {
            $return = $data;
            $this->getResponse()->setStatusCode(200);
        } else {
            $this->getResponse()->setStatusCode(404);
            $return = array("errors" => \BlogPost\Entity\BlogPost::ERR_INFORMATION_NOT_FOUND);
        }

        return new JsonModel(array("result" => $return));
    }

    public function conversorSegundosHoras($tiempo_en_segundos) {
        $horas = floor($tiempo_en_segundos / 3600);
        $minutos = floor(($tiempo_en_segundos - ($horas * 3600)) / 60);
        $segundos = $tiempo_en_segundos - ($horas * 3600) - ($minutos * 60);
        if($segundos > 59){
            $segundos = $segundos-60;
            $minutos++;
        }
        if($segundos < 10)
            $segundos = "0".$segundos;
        if($segundos == 0)
            $segundos = "00";
        if($minutos < 10)
            $minutos = "0".$minutos;
        if($minutos == 0)
            $minutos = "00";
        if($horas == 0)
            $horas = "00";
        return $horas . ':' . $minutos . ":" . $segundos;
    }

    public function hoursToSecods($hour) { // $hour must be a string type: "HH:mm:ss"

        $parts = explode(":",$hour);
        $hours = $parts[0];
        $minutes = $parts[1];
        $seconds = $parts[2];

        return (int) $hours * 3600 + (int) $minutes * 60 + (int) $seconds;

    }

    //Calculates the total time
    public function timeTotal($arrayTime){
        $hours = $minutes = $seconds= 0;
        foreach($arrayTime as $response)
        {
            $time = $response->getAvgSessionDuration();
            $parts = explode(":",$time);
            $hours += $parts[0];
            $minutes += $parts[1];
            $seconds += $parts[2];
            if($seconds > 59){
                $seconds = $seconds-60;
                $minutes++;
            }
            if($seconds < 10)
                $seconds = "0".$seconds;
            if($seconds == 0)
                $seconds = "00";
            if($minutes > 59){
                $minutes = $minutes-60;
                $hours++;
            }
            if($minutes < 10)
                $minutes = "0".$minutes;
            if($minutes == 0)
                $minutes = "00";
            if($hours < 10)
                $hours = "0".$hours;
            if($hours == 0)
                $hours = "00";
        }

        $time = $hours.":".$minutes.":".$seconds;
        return $time;
    }

    private function _Time_Words_View_BlogPost($key,$date1,$date2)
    {
//        $query = $this->em->createQuery("SELECT u.post_id,max(u.created) upd
//                                      FROM BlogPost\Entity\BlogPost u
//                                      WHERE u.key_api=?1 and u.status <> 0 and u.created BETWEEN ?2 and ?3
//                                      group by u.post_id");
        $query = $this->em->createQuery("SELECT u.post_id,max(u.created) upd
                                      FROM BlogPost\Entity\BlogPost u
                                      WHERE u.key_api=?1 and u.status <> 0 and u.date_publishing BETWEEN ?2 and ?3
                                      group by u.post_id");
        $query->setParameter(1,$key);
        $query->setParameter(2,$date1);
        $query->setParameter(3,$date2);
        $queryResult =  $query->getResult();
        foreach ($queryResult as $rec)
        {
            if($rec['post_id'] == null)
                break;
            $q = $this->em->createQuery("SELECT partial bp.{id,avg_session_duration,words,view}
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
