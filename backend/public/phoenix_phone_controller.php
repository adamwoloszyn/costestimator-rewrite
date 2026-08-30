<?php
	header('Access-Control-Allow-Origin: http://localhost:5173');
	header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
	header('Access-Control-Allow-Headers: Content-Type, X-CSRF-Token');
	header('Access-Control-Allow-Credentials: true');
	header('Content-Type: application/json');
	
	if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
	    http_response_code(200);
	    exit;
	}
	
	error_reporting(E_ALL & ~E_DEPRECATED);
	ini_set("log_errors", 1);
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
			    'endpoint' => 'https://patientws-qa.labcorp.com/patient-portal-ws/api/phoneNumber'
		    ),
		    'staging' => array(
			    'endpoint' => 'https://patientws-stage.labcorp.com/patient-portal-ws/api/phoneNumber'
		    ),
		    'old-prod' => array(
			    'endpoint' => 'https://patient.labcorp.com/patient-portal-ws/api/phoneNumber'
		    ),
		    'prod' => array(
			    'endpoint' => 'https://patientws.labcorp.com/patient-portal-ws/api/phoneNumber'
		    )
	    );
		
		public function __construct() {
			require '../vendor/autoload.php';
			$this->setLogger('../../../logs');
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
		    // Validate and sanitize phone number input
		    if (!isset($_GET['phoneNumber'])) {
		        return array("phone_lookup" => array("error" => "Phone number is required"));
		    }
		    
		    $phoneNumber = filter_var($_GET['phoneNumber'], FILTER_SANITIZE_STRING);
		    $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
		    
		    if (strlen($phoneNumber) < 10 || strlen($phoneNumber) > 15) {
		        return array("phone_lookup" => array("error" => "Invalid phone number format"));
		    }
		    
		    $credentials = $this->getCredentials();
			$instance_url = $credentials[$this->environment]['endpoint'];
			$endpoint = "?phoneNumber=" . urlencode($phoneNumber);
			
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
			    "Postman-Token: e7be217d-adce-7a7d-9ece-9b0f7ba7ff30"
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