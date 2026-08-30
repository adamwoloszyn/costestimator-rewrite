<?php
	ini_set("log_errors", 1);
	ini_set("error_log", "../../../logs/php_errors.log");
	
	class PhoenixController { 
		
		
		private $logger; 
		public $environment = 'qa-new';
		private $pdClient;
		private $credentials = array(
		    'qa-new' => array(
			    'endpoint' => 'https://estimator-api-qa.ald01.cws.labcorp.com/estimate'
		    ),
		    'qa' => array(
			    'endpoint' => 'https://patientws-qa.labcorp.com/geneticestimator/estimate'
		    ), 
		    'staging' => array(
			    'endpoint' => 'https://patientws-stage.labcorp.com/geneticestimator/estimate'
		    ),
		    'old-prod' => array(
			    'endpoint' => 'https://patient.labcorp.com/geneticestimator/estimate'
		    ),
		    'prod' => array(
			    'endpoint' => 'https://patientws.labcorp.com/geneticestimator/estimate'
		    )

	    );
		
		public function __construct() {
			require '../vendor/autoload.php';
			$this->setLogger('/var/www/vhosts/sites/stage/logs-application');
		}
		private function setLogger($path){
		    $this->logger = new Katzgrau\KLogger\Logger($path);
		}
		public function getLogger(){
			return $this->logger;
		}
		private function getCredentials(){
			return $this->credentials;
		}
	    public function returnEstimate($data, $successfulEstimationForTesting, $testLive, $logger){
		    if($testLive){
			    $credentials = $this->getCredentials();
			    $access_token = '';
				$instance_url = $credentials[$this->environment]['endpoint'];
				$endpoint = '';
				$contentType = 'application/json';
				
				$formattedBirthday = date("Y-m-d", strtotime($data["Member_BirthDate"]));
				
				$fields = array(
					"captchaResponse" => $data['RecaptchaResponse'],
					"patient" => array(
						"firstName" => $data['Member_FirstName'],
						"lastName" => $data['Member_LastName'],
						"dateOfBirth" => $formattedBirthday,
						"gender" => $data['Member_Gender']
					),
					"insurance" => array(
						"id" => $data['TradingPartnerId']['key'],
						"subscriberId" => $data['Member_Id']
					),
					"tests" => $data['tests']
				);
				//"id" => $data['TradingPartnerId']['key'],
				$phoenix_estimate = $this->getCurlResult($instance_url, $endpoint, json_encode($fields), false, $access_token, $contentType, 'POST', $logger);
		    }else{
			    $phoenix_estimate = array(
				    'captchaVerified' => true,
				    'callUsPhoneNumber' => '555-555-5555',
				    'estimatedAmount' => 15.0,
				    'legalMessage' => '<strong>Please note that this is only an estimate and assumes your health plan will cover the testing and approve payment. You should contact your health plan directly to verify the service will be covered.</strong> Some health plans require prior authorization or limit coverage based on a specific diagnosis provided by your physician. Additionally, your physician may request laboratory services that will trigger additional testing procedures based on certain clinical indications or your physician may determine it necessary to order additional testing. Your actual out-of-pocket expense may be higher than the amount provided even if this estimate indicates zero. LabCorp invoices patients based on the response received from their health plan, so again, please contact your health plan directly to verify the service will be covered.',
				    'messageText' => 'our estimated out-of-pocket cost for this test is:',
				    'successfulEstimation' => $successfulEstimationForTesting
			    );
			}
		    return array("phoenix_estimate" => $phoenix_estimate, "phoenix_payload" => $fields);
	    }
	    
	    private function getCurlResult($url, $endpoint, $fields, $needsAccessToken, $accessToken, $contenType, $method, $logger){
		    $ch = curl_init();		
			if($needsAccessToken){
			    $access_token = $accessToken;
				$instance_url = $url . $endpoint;
				curl_setopt($ch, CURLOPT_URL, $instance_url);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer $access_token", "Content-type: " . $contenType));
		    }else{
			    curl_setopt($ch, CURLOPT_URL, $url);
			    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-type: " . $contenType));
		    }
		    
		    $fields_string = "";
		    if($contenType == 'string'){
			    //url-ify the data for the POST
			    foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
			    rtrim($fields_string, '&');
			    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
		    }else{
			    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		    }
		    curl_setopt($ch, CURLOPT_HEADER, false);
		    if($method == 'POST'){
			    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		    }else if($method == 'PUT'){
			    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
		    }
		    curl_setopt($ch, CURLOPT_SSLVERSION, 6);
		    		
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);		
			$result = curl_exec($ch);
			$err = curl_error($ch);
			$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			
			//close connection
			curl_close($ch);
			
			if($httpcode == 200){ //success
				$json = json_decode($result, true);
				if (json_last_error() === JSON_ERROR_NONE) {
				    // JSON is valid
				    $json = json_decode($result, true);
				}else{
					$json = $result;
				}
				$logger->info('API Call Success: Phoenix Estimate Lookup');
				return array("http_code" => $httpcode, "results" => $json, "instance_url" => $url . $endpoint);
			}else{
				//$ch = json_decode($ch, true);
				$decodedResult = json_decode($result, true);
			    if (json_last_error() !== JSON_ERROR_NONE) {
			        $decodedResult = $result;
			    }
			
			    $logger->info('API Call Failure: Phoenix Estimate Lookup - ' . $httpcode);
			
			    return array(
			        "http_code" => $httpcode,
			        "results" => $decodedResult,
			        "error" => $err,
			        "instance_url" => $url . $endpoint
			    );
				//return array("http_code" => $httpcode, "results" => null, "full_result" => $result, "instance_url" => $url . $endpoint);
			}
	    }
	}
?>