<?php
	header('Access-Control-Allow-Origin: *');  
	header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header('Access-Control-Allow-Headers: X-CSRF-Token, Accept, Content-Type');
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(204);
        exit();
    }
	header('Content-Type: application/json');
	ini_set("log_errors", 1);
	ini_set("error_log", "../../../logs/php_errors.log");
	
	class PhoenixLookUpController { 
		
		private $logger; 
		public $environment = 'prod';
		private $credentials = array(
		    'qa' => array(
			    'endpoint' => 'https://patientws-qa.labcorp.com/patient-portal-ws/api/phoneNumber'
		    ),
		    'staging' => array(
			    'endpoint' => 'https://patientws-stage.labcorp.com/patient-portal-ws/api/phoneNumber'
		    ),
		    'old-prod' => array(
			    'endpoint' => 'https://patient.labcorp.com/patient-portal-ws/api/phoneNumber'
		    ),
		    'prod' => array(
			    'endpoint' => 'https://portal-api.patient.cws.labcorp.com/guest/phoneNumber'
		    )
	    );
		
		public function __construct() {
			require '../vendor/autoload.php';
			$this->setLogger('/var/www/vhosts/sites/prod/logs-application');
		}
		private function getCredentials(){
			return $this->credentials;
		}
		private function setLogger($path){
		    $this->logger = new Katzgrau\KLogger\Logger($path);
		}
		public function getLogger(){
			return $this->logger;
		}
	    public function returnLookUp($logger){
		    $credentials = $this->getCredentials();
			$instance_url = $credentials[$this->environment]['endpoint'];
			$endpoint = "?phoneNumber=" . $_GET['phoneNumber'];
			
			$phoenix_lookup = $this->getCurlResult($instance_url, $endpoint);
		    
		    
			$logger->info('---------------- Phoenix Phone Lookup   ----------------');
			$logger->info('Starting lookup');
			
			
		    if($phoenix_lookup['http_code'] == 200){		
			   	$logger->debug('Phoenix Phone Response', $phoenix_lookup);
			    $logger->info('API Call Success: Phoenix Phone Lookup');
				return array("phone_lookup" => $phoenix_lookup);
			}else{
				if(!isset($phoenix_lookup['results'])){
					$logger->info('API Call Failure: Phoenix Phone Lookup - System Down');
					return array("phone_lookup" => array(
						"results" => array(
							"status" => "SystemDown"
						)
					));
				}else if(isset($phoenix_lookup['results']['errors']) && $phoenix_lookup['results']['errors'] != ''){
					$logger->info('API Call Failure: Phoenix Phone Lookup - ' . $phoenix_lookup['results']['description']);
					$logger->debug('Phoenix Phone Errors', $phoenix_lookup['results']['description']);
					return array("phone_lookup" => $phoenix_lookup['results']['description']);
				}else{
					$logger->info('API Call Success: Phoenix Phone Lookup');
					$logger->debug('Phoenix Phone Response', $phoenix_lookup);
					return array("phone_lookup" => $phoenix_lookup);
				}
			}
	    }
	    
	    private function getCurlResult($url, $endpoint){
		    $curl = curl_init();

			curl_setopt_array($curl, array(
			  CURLOPT_URL => $url . $endpoint,
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_SSLVERSION => 6,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "GET",
			  CURLOPT_HTTPHEADER => array(
			    "Cache-Control: no-cache",
			    "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.121 Safari/537.36"
			  ),
			));
			
			$result = curl_exec($curl);
			$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			$err = curl_error($curl);
			
			curl_close($curl);
			
			if ($err) {
				$json = json_decode($result, true);
				return array("http_code" => $httpcode, "results" => $json, "error" => $err);
			} else {
				$json = json_decode($result, true);
				return array("http_code" => $httpcode, "results" => $json);
			}
			

	    }
	}
	
	$pluc = new PhoenixLookUpController; 
	$logger = $pluc->getLogger();		
	
	$lookup = $pluc->returnLookUp($logger);
	
	print_r(json_encode($lookup));
?>