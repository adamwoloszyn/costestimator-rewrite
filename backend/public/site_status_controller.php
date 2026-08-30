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
	
	// Secure session configuration
	ini_set('session.cookie_httponly', '1');
	ini_set('session.cookie_samesite', 'Strict');
	ini_set('session.use_strict_mode', '1');
	
	session_start();
	ini_set("error_log", "../../logs/php_errors.log");
	include '../db.php';
	
	$_SESSION["PHPSESSID"] = session_id();
	$_SESSION['token'] = bin2hex(random_bytes(32));
	
	class CostEstimateController { 
		
		const PHOENIX = 'phoenix';
		const POKITDOK = 'pokitdok';
		const NONE = 'none';
		 
		private $customerData;		 
		private $phoenixController;
		private $pokitdokController;
		private $salesforceController;
		private $frontEndResponse;
		private $logger; 
		
		public function __construct() {
			require '../vendor/autoload.php';
			$this->setLogger('../../../logs');
			
			require_once('salesforce_controller.php');
			
			$this->salesforceController = new SalesforceController; 
	    }
		public function getSalesforceController(){
			return $this->salesforceController;
		}
		
		private function setLogger($path){
		    $this->logger = new Katzgrau\KLogger\Logger($path);
		}
		public function getLogger(){
			return $this->logger;
		}
		
		
	}
	try{
		$cec = new CostEstimateController;
		$logger = $cec->getLogger();	
		
		$salesforceController = $cec->getSalesforceController();
		$returnData = array();
		
		$site_status = $salesforceController->getSiteStatus($logger);
		$returnData["site_status"] = $site_status;
		
		$returnData["token"] = $_SESSION['token'];
		$returnData["id"] = $_SESSION['PHPSESSID'];
			
		print_r(json_encode($returnData));
	} catch (Exception $e) {
		//$logger->debug('API Call Failure: Get Site Status', $e);
	}