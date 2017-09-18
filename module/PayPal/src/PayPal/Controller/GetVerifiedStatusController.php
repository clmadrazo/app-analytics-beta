<?php
namespace PayPal\Controller;

use App\Mvc\Controller\RestfulController;
use Zend\View\Model\JsonModel;
use Zend\Mvc\MvcEvent;


/**
 * This controller handles all post module requests.
 * 
 */
class GetVerifiedStatusController extends RestfulController
{  
    protected $_allowedMethod = "post";
    protected $requestData;
    protected $_em = null;

    public function indexAction()
    {
        $this->_em = $this->getEntityManager();
        $request = $this->getRequest();
        $this->requestData = $this->processBodyContent($request);
        $email = $this->requestData[0]['email'];
        $first_name = $this->requestData[0]['firstName'];
        $last_name = $this->requestData[0]['lastName'];

        // Generate curl request
        $ch = curl_init();

        // Create http client, set auth headers & parameters
        //Live Endpoints: "https://svcs.paypal.com/AdaptiveAccounts/GetVerifiedStatus"
        curl_setopt($ch, CURLOPT_URL, "https://svcs.sandbox.paypal.com/AdaptiveAccounts/GetVerifiedStatus"); //Sandbox Endpoint

        $headers = array();
        $params = array(
            'requestEnvelope.errorLanguage'  => "en_US",
            'requestEnvelope.detailLevel'  => "ReturnAll",
            'emailAddress'   => $email,
            'firstName'        => $first_name,
            'lastName'   => $last_name,
            'matchCriteria'      => "NAME",
        );
        $params = json_encode($params);

        // API Credentials
        $headers[] = 'X-PAYPAL-SECURITY-USERID: madrazoreyescarloslazaro-facilitator_api1.gmail.com';
        $headers[] = 'X-PAYPAL-SECURITY-PASSWORD: ZCSYB6VXRBPH6FU9';
        $headers[] = "X-PAYPAL-SECURITY-SIGNATURE: A5jmfccrTN4nQ08jYjVM3v6vvLIlAUzY-DEBJ6oY7QI1bwFhg5I3yuCW";

        // Input and output formats
        $headers[] = "X-PAYPAL-REQUEST-DATA-FORMAT: JSON";
        $headers[] = "X-PAYPAL-RESPONSE-DATA-FORMAT: NV";

        // Static Sandbox AppID
        $headers[] = "X-PAYPAL-APPLICATION-ID: APP-80W284485P519543T";

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt ($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // obtain response
        $response = curl_exec($ch);
        curl_close($ch);
        $responseAr = explode('&', $response);
        $parsedResponseAr = array();
        foreach($responseAr as $i => $value) {
            $tmpAr = explode('=', $value);
            if(!empty($tmpAr))
                $parsedResponseAr[$tmpAr[0]] = urldecode($tmpAr[1]);
        }
        $return = $parsedResponseAr["responseEnvelope.ack"] == "Success" && $parsedResponseAr["accountStatus"] == "VERIFIED";

        if ($return) {
            $this->getResponse()->setStatusCode(200);
        }
        else {
            $this->getResponse()->setStatusCode(404);
        }

        return new JsonModel(array("result" => $return));
    }

    public function parse($response)
    {
        $responseArray = explode("&", $response);
        $result = array();
        if (count($responseArray) > 0)
        {
            foreach ($responseArray as $i => $value)
            {
                $keyValuePair = explode("=", $value);
                if(sizeof($keyValuePair) > 1)
                {
                    $result[$keyValuePair[0]] = urldecode($keyValuePair[1]);
                }
            }
        }
        return empty($result) ? null : $result;
    }
}

