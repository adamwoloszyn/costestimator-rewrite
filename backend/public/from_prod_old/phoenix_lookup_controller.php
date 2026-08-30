<?php
	header('Access-Control-Allow-Origin: *');   
	header('Content-Type: application/json');
	ini_set("error_log", "../../../logs/php_errors.log");
	class PhoenixLookUpController { 
		
		private $logger; 
		public $environment = 'prod';
		private $credentials = array(
		    'qa' => array(
			    'endpoint' => 'https://patientws-qa.labcorp.com/geneticestimator/lookup'
		    ),
		    'staging' => array(
			    'endpoint' => 'https://patientws-stage.labcorp.com/geneticestimator/lookup'
		    ),
		    'old-prod' => array(
			    'endpoint' => 'https://patient.labcorp.com/geneticestimator/lookup'
		    ),
		    'prod' => array(
			    'endpoint' => 'https://patientws.labcorp.com/geneticestimator/lookup'
		    )
	    );
		
		public function __construct() {
			require '../vendor/autoload.php';
			$this->setLogger('/var/www/vhosts/sites/prod/logs-application');
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
	    public function returnLookUp($logger){
		    $credentials = $this->getCredentials();
			$instance_url = $credentials[$this->environment]['endpoint'];
			$endpoint = '';
			
			$logger->info('---------------- Phoenix Lookup ----------------');
			$phoenix_lookup = $this->getCurlResult($instance_url, $endpoint, $logger);
			
		    return array("phoenix_lookup" => $phoenix_lookup);
	    }
	    
	    private function getCurlResult($url, $endpoint, $logger){
		    $curl = curl_init();

			curl_setopt_array($curl, array(
			  CURLOPT_URL => $url,
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_SSLVERSION => 6,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "GET",
			  CURLOPT_HTTPHEADER => array(
			    "Cache-Control: no-cache",
			    "Postman-Token: e7be217d-adce-7a7d-9ece-9b0f7ba7ff30"
			  ),
			));
			
			$result = curl_exec($curl);
			$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			$err = curl_error($curl);
			
			curl_close($curl);
			
			if ($err) {
				$json = json_decode($result, true);
				//$logger->debug('API Call Failure: Phoenix Lookup', $json);
				$logger->info('API Call Failure: Phoenix Lookup', $json);
				
				return array("http_code" => $httpcode, "results" => $json, "error" => $err);
			} else {
				$json = json_decode($result, true);
				
				if(is_null($json)){
					$logger->debug('API Call Failure: Phoenix Lookup', $json);
					
					return array("http_code" => $httpcode, "results" => $json, "error" => $err);
				}else{
					if(!isset($json['error']) || $json['error'] == ''){
						$logger->info('API Call Success: Phoenix Lookup');
					}else{
						$logger->info('API Call Failure: Phoenix Lookup', $json);
					}
					return array("http_code" => $httpcode, "results" => $json);
				}
			}
			

	    }
	}
	
	$pluc = new PhoenixLookUpController; 
	
	$logger = $pluc->getLogger();	
	$lookup = $pluc->returnLookUp($logger);
	
	print_r(json_encode($lookup));
?>