<?php
	session_start();
	header('Access-Control-Allow-Origin: *');  
	header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
	header('Access-Control-Allow-Headers: X-CSRF-Token, Accept, Content-Type');
	if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit(); }
	header('Content-Type: application/json');
	ini_set("error_log", "../../../logs/php_errors.log");
	include '../../db.php';
	
	$_SESSION["PHPSESSID"] = session_id();
	$_SESSION['token'] = md5(uniqid(mt_rand(), true));
	
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
			$this->setLogger('/var/www/vhosts/sites/prod/logs-application');
			
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