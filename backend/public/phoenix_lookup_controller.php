<?php
	error_reporting(E_ALL & ~E_DEPRECATED);
	header('Access-Control-Allow-Origin: http://localhost:5173');
	header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
	header('Access-Control-Allow-Headers: Content-Type, X-CSRF-Token');
	header('Access-Control-Allow-Credentials: true');
	header('Content-Type: application/json');
	
	// Handle preflight request
	if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
	    http_response_code(200);
	    exit;
	}
	
	ini_set("error_log", "../../logs/php_errors.log");
	
	// Secure session configuration
	ini_set('session.cookie_httponly', '1');
	ini_set('session.cookie_samesite', 'Strict');
	ini_set('session.use_strict_mode', '1');
	
	session_start();
	
	// Validate CSRF token
	$headers = getallheaders();
	$token = isset($headers['X-CSRF-Token']) ? $headers['X-CSRF-Token'] : null;
	
	if (!$token || !isset($_SESSION['token']) || $token !== $_SESSION['token']) {
	    header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden');
	    echo json_encode(['error' => 'Invalid or missing CSRF token']);
	    exit;
	}
	class PhoenixLookUpController { 
		
		private $logger; 
		public $environment = 'qa';
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
			$this->setLogger('../../../logs');
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
				$logger->info('API Call Failure: Phoenix Lookup', $json);
				
				return array("http_code" => $httpcode, "results" => $json, "error" => $err);
			} else {
				$json = json_decode($result, true);
				
				if(is_null($json)){
					$logger->info('API Call Failure: Phoenix Lookup', $json);
					
					return array("http_code" => $httpcode, "results" => $json, "error" => $err);
				}else{
					// if(!isset($json['error']) || $json['error'] == ''){
					// 	//$logger->info('API Call Success: Phoenix Lookup');
					// }else{
					// 	//$logger->info('API Call Failure: Phoenix Lookup', $json);
					// }
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