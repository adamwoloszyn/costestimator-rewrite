<?php
	header('Access-Control-Allow-Origin: *');  
	header('Content-Type: application/json');
	ini_set('display_errors', '1');
	ini_set("log_errors", 1);
	ini_set("error_log", "../../../logs/php_errors.log");
	include '../../db.php';
	
	$customerData = (array) json_decode(file_get_contents('php://input'), TRUE);
	session_id($customerData['user']["id"]);
	session_start();	
	

		
	class CostEstimateController { 
		
		const PHOENIX = 'phoenix';
		const CHC = 'chc';
		const NONE = 'none';

		private $customerData;
		 
		private $phoenixController;
		private $chcLookupController;
		private $salesforceController;
		private $frontEndResponse;
		private $logger; 
		
		public function __construct() {
					
			require '../vendor/autoload.php';
			require_once('phoenix_controller.php');
			require_once('chc_controller.php');
			require_once('salesforce_controller.php');
			require_once('frontend_response.php');
			
			$this->phoenixController = new PhoenixController; 
			$this->chcLookupController = new CHCLookUpController; 
			$this->salesforceController = new SalesforceController; 
			$this->frontEndResponse = new FrontEndResponse; 
			$this->setLogger('/var/www/vhosts/sites/prod/logs-application');
	    }
		public function getPhoenixController(){
			return $this->phoenixController;
		}
		public function getCHCLookupController(){
			return $this->chcLookupController;
		}
		public function getSalesforceController(){
			return $this->salesforceController;
		}
		public function getFrontEndResponse(){
			return $this->frontEndResponse;
		}
		public function getCustomerData(){
			return $this->customerData;
		}
		public function setCustomerData($data){
			$this->customerData = $data;
		}
		private function setLogger($path){
			$this->logger = new Katzgrau\KLogger\Logger($path);
		}
		public function getLogger(){
			return $this->logger;
		}
		public function generateEstimateID(){
		    if (function_exists('com_create_guid') === true)
		    {
		        return trim(com_create_guid(), '{}');
		    }
		
		    return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
		}
		private function roundUpToAny($n,$x=5){
	        return (round($n)%$x === 0) ? round($n) : round(($n+$x/2)/$x)*$x;
	    }
	    private function calculateFutureAge($birth_date, $future_date){

	        list($yr,$mo,$day) = explode('-',$birth_date);
	        list($future_y,$future_m,$future_day) = explode('-',$future_date);
	
	        $now = ($future_y*10000+$future_m*100+$future_day);
	
	        $past = ($yr*10000+$mo*100+$day);
	        $diff = ($past-$now);
	        if ($diff>0) { $age = 0 ; }
	        else
	        {
	            $age = (($future_y-$yr)-1);
	            if (($future_m>$mo) || (($future_m>=$mo) && ($future_day>=$day))) { $age++; }
	        }
	        return $age;
	    }
		
		public function calculateInitialCostObjectValues($fromService, $data, $estimateData){

			$dataToEvaluate = array();
			$customerData = $data;

			if($data["MultiFetalGestation"] == "No"){
				$MultiFetalGestation = false;
			}else if($data["MultiFetalGestation"] == "Twins"){
				$MultiFetalGestation = true;
			}else if($data["MultiFetalGestation"] == "Triplets"){
				$MultiFetalGestation = true;
			}else if($data["MultiFetalGestation"] == "More than three"){
				$MultiFetalGestation = true;
			}
			if($MultiFetalGestation){
				$customerData["MultiFetalGestation"] = true;
			}else{
				$customerData["MultiFetalGestation"] = false;
			}
			$customerData["NumberOfChildren"] = $data["MultiFetalGestation"];
			
			if($fromService == $this::PHOENIX){
				$customerData["CostEstimationTool"] = $this::PHOENIX;
				
				if($data["ActualTestCategory"] == "NIPT" && $MultiFetalGestation){
					$customerData["NotCoveredByMedicalPolicy"] = true;
				}
				
				$costEstimate = $estimateData["estimatedAmount"];
				
				$chcLookupController = $this->getCHCLookupController();
				
				$isAverageRiskTradingPartner = $chcLookupController->isAverageRiskPartner($data["PayerName"], "phoenix_payer_id");
				
				$customerData["CostEstimate"] = $costEstimate;
				$customerData["CostEstimateCurrency"] = "USD";
				$customerData["isAverageRiskTradingPartner"] = $isAverageRiskTradingPartner["result"];
				$customerData["isAverageRiskTradingPartner_Date"] = $isAverageRiskTradingPartner["date"];
				$customerData["isAverageRiskTradingPartner_Query"] = $isAverageRiskTradingPartner["query"];
				$customerData["CoverageActive"] = true;
				$serviceStatus = "Active";
				
			}else if($fromService == $this::CHC){
				$estimateData = json_decode($estimateData, true);
				
/*
				if(!$customerData["CoverageActive"]){
					$customerData["CoverageActive"] = false;
				}else{
					$customerData["CoverageActive"] = true;
				}
*/
				//TODO figure out errors.
				// if(!empty($estimateData->data->errors)){
				// 	$customerData["CoverageActive"] = false;
				// 	$customerData["ErrorText"] = (isset($estimateData->data->errors) ? $estimateData->data->errors : "");
				// 	$customerData["CHC_ActivityID"] = (isset($estimateData->meta->traceId) ? $estimateData->meta->traceId : "");
				// }

				$customerData["CostEstimationTool"] = $this::CHC;


				if(!empty($estimateData["planStatus"])){
					
					if(count($estimateData["planStatus"]) == 1){
						
						
						if($estimateData["planStatus"][0]["status"] == "Active Coverage"){
							
							$customerData["CoverageActive"] = true;
							$serviceStatus = "Active";

						}
						
					}else if(count($estimateData["planStatus"]) > 1){
						
						$activeCoverage = false;
						
						
						//loop through all
						foreach($estimateData["planStatus"] as $plan){
							
							if($plan["status"] == "Active Coverage"){
								$customerData["CoverageActive"] = true;
								$serviceStatus = "Active";
						
							}
							
						}
					}else{
						
						$customerData["CoverageActive"] = false;
						$serviceStatus = "No active coverage. Estimate cannot be calculated.";
						$customerData["CostEstimate"] = null;
						
					}
					
/*
					if($estimateData["planStatus"][0]["status"] != "Inactive"){
						$customerData["CoverageActive"] = true;
						$serviceStatus = "Active";
					}else{
						
						
						$customerData["CoverageActive"] = false;
						$serviceStatus = "No active coverage. Estimate cannot be calculated.";
						$customerData["CostEstimate"] = null;
						
						
					}
*/
					
				}


				if(!empty($estimateData["planInformation"])){
					$customerData["GroupNumber"] = (isset($estimateData["planInformation"]["groupNumber"]) ? $estimateData["planInformation"]["groupNumber"] : "");
					$customerData["PlanBeginDate"] = null;
					
					

					//TODO replace these before production
					//$customerData["PlanBeginDate"] = (isset($estimateData->data->eligibility->coverage->plan_begin_date) ? $estimateData->data->eligibility->coverage->plan_begin_date : "");
					//$customerData["CoverageActive"] = (isset($estimateData->data->eligibility->coverage->active) ? $estimateData->data->eligibility->coverage->active : "");
				}

				// we are going to check if they have coinsurance.
				if($estimateData["COAmount"] >= 0){
					$customerData["A_InsideCoInsurance"] = 'Top';
					$customerData["Coinsurance"] = $estimateData["COAmount"];
					$customerData["CostEstimate"] = $estimateData["CostEstimate"];
					$data["CostEstimate"] = $estimateData["CostEstimate"];
				}else{
					// - if they don't
					// - user the previous estimate calculation logic of high/low and log "no" coninsurance
					$customerData["MadeItInConinsurance"] = 'Bottom';
					$customerData["Coinsurance"] = "no";
					$customerData["CostEstimateOriginal"] = $estimateData["CostEstimate"];
				}

				if($data["ActualTestCategory"] == "NIPT" && $MultiFetalGestation){
					$customerData["NotCoveredByMedicalPolicy"] = true;
				}

				//update our customer object globally with final values
				$customerData["Eligibility"] = null;
				//$customerData["CostEstimatesArray"] = (isset($estimateData->meta->calculation->estimate) ? $estimateData->meta->calculation->estimate : "");
				$customerData["DeductibleBenefitAmount"] = (isset($estimateData["DDAmount"]) ? $estimateData["DDAmount"] : null);
				//$customerData["CostEstimateCurrency"] = (isset($estimateData->meta->calculation->price->currency) ? $estimateData->data->calculation->price->currency : "");
				
				//$customerData["PokitDok_Calculation"] = (isset($estimateData->meta->calculation) ? $estimateData->meta->calculation : 0);
				$customerData["CHC_Meta"] = (isset($estimateData["meta"]) ? $estimateData["meta"] : "");
				$customerData["CHC_ActivityID"] = (isset($estimateData["meta"]["traceId"]) ? $estimateData["meta"]["traceId"] : "");

				$costEstimate = (isset($estimateData["CostEstimate"]) ? $estimateData["CostEstimate"] : null);
				
				$chcLookupController = $this->getCHCLookupController();
				//check what to query by
				if($data["TradingPartnerId"]["value"] != ""){
					// try by CHC ID first
					$isAverageRiskTradingPartner = $chcLookupController->isAverageRiskPartner($data["TradingPartnerId"]["value"], "chc_partner_id");
				}else{
					//else go for phoenix version
					$isAverageRiskTradingPartner = $chcLookupController->isAverageRiskPartner($data["PayerName"], "phoenix_payer_id");
				}
				
				$customerData["isAverageRiskTradingPartner"] = $isAverageRiskTradingPartner["result"];
				$customerData["isAverageRiskTradingPartner_Date"] = $isAverageRiskTradingPartner["date"];
				$customerData["isAverageRiskTradingPartner_Query"] = $isAverageRiskTradingPartner["query"];

			}else{
				
				$costEstimate = (isset($data["CostEstimate"]) ? $data["CostEstimate"] : null);
				$customerData["Coinsurance"] = "no";
				$customerData["NotCoveredByMedicalPolicy"] = true;
				$customerData["CoverageActive"] = false;
				$customerData["CostEstimate"] = null;
				$costEstimate = null;
			}
			
			/*
				Round COST_ESTIMATE  
					o	If Estimate = 0, do not round up,
					o	Estimate <$275, round up to nearest $25 (i.e. 242 become 250)
					o	If estimate >= 275, do not round and use actual estimate
					
				//pokitdok actually does this calculation for us.
				Calculate Out of Pocket (OOP) values for COST_ESTIMATE
					OOP  = lower between COST_ESTIMATE and deductible
			*/
			if($costEstimate == 0){
				$finalCostEstimate = $costEstimate;
			}else if($costEstimate <= 275){
				$finalCostEstimate = $this->roundUpToAny($costEstimate, 25);
			}else if($costEstimate > 275){
				$finalCostEstimate = number_format((float)$costEstimate, 2, '.', '');
			}else{
				$finalCostEstimate = "";
			}
			
			/*
				Calculate MEMBER_ESTIMATED_AGE_AT_DELIVERY
			*/
			if($data["DueDate"] != ""){
				
				$formattedBirthday = date("Y-m-d", strtotime($data["Member_BirthDate"]));

				$formattedDueDate = substr($data["DueDate"], 0, 10);
				$customerData["DueDate"] = $formattedDueDate;
				// - If customer provided answer to estimated date of delivery, MEMBER_ESTIMATED_AGE_AT_DELIVERY = Estimated_Delivery_Date - Date_Of_Birth
				$age = $this->calculateFutureAge($formattedBirthday, $formattedDueDate);
				
				$customerData["Member_BirthDate"] = $formattedBirthday;
			}else{
				$customerData["DueDate"] = date("Y-m-d");
				
				$formattedBirthday = date("Y-m-d", strtotime($data["Member_BirthDate"]));
				
				// - If customer did not provide answer to estimated date of delivery, MEMBER_ESTIMATED_AGE_AT_DELIVERY = Today's Date - Date_Of_Birth
				$age = $this->calculateFutureAge($formattedBirthday, date("Y-m-d"));
				
				$customerData["Member_BirthDate"] = $formattedBirthday;
			}
			
			if($data["HighRisk"] == "Yes"){
				$isHighRisk = true;
			}else{
				$isHighRisk = false;
			}
			/*
				Update HIGH_RISK value
					- If Age > 35 and test is NIPT, change HIGH_RISK question answer to "Yes"
			*/	
			if($age > 35 && $data["ActualTestCategory"] == "NIPT"){
				$isHighRisk = true;
			}
			
			if($data["ConsentToLeaveMessage"] == "Yes"){
				$customerData["ConsentToLeaveMessage"] = true;
			}else{
				$customerData["ConsentToLeaveMessage"] = false;
			}
			if($data["ConsentToContact"] == "Yes"){
				$customerData["ConsentToContact"] = true;
			}else{
				$customerData["ConsentToContact"] = false;
			}
			
			if($data["coverage_twins"] != ""){
				$today = date("Y-m-d H:i:s");
                $date = date("Y-m-d H:i:s", strtotime($data["coverage_twins"]));
				if($date < $today){
					$customerData["coverage_twins"] = true;
				}else{
					$customerData["coverage_twins"] = false;
				}
			}else{
				$customerData["coverage_twins"] = false;
			}
			if($data["coverage_triplets"] != ""){
				$today = date("Y-m-d H:i:s");
                $date = date("Y-m-d H:i:s", strtotime($data["coverage_triplets"]));
				if($date < $today){
					$customerData["coverage_triplets"] = true;
				}else{
					$customerData["coverage_triplets"] = false;
				}
			}else{
				$customerData["coverage_triplets"] = false;
			}
			if($data["coverage_four_or_more"] != ""){
				$today = date("Y-m-d H:i:s");
                $date = date("Y-m-d H:i:s", strtotime($data["coverage_four_or_more"]));
				if($date < $today){
					$customerData["coverage_four_or_more"] = true;
				}else{
					$customerData["coverage_four_or_more"] = false;
				}
			}else{
				$customerData["coverage_four_or_more"] = false;
			}
			
/*
			if($customerData["CoverageActive"] == true){
				$serviceStatus = "Active";
			}else{
				$serviceStatus = "No active coverage. Estimate cannot be calculated.";
				$customerData["CostEstimate"] = null;
			}
*/
			
			$customerData["CostEstimate"] = $finalCostEstimate;
			$customerData["EstimateId"] = (isset($estimateID) ? $estimateID : "");
			$customerData["Member_AgeAtDelivery"] = $age;
			$customerData["AverageRiskPartner"] = (isset($isAverageRiskTradingPartner) ? $isAverageRiskTradingPartner : "");
			$customerData["HighRisk"] = (isset($isHighRisk) ? $isHighRisk : "");
			
			

			$dataToEvaluate = array(
				"cost_estimate" => $customerData["CostEstimate"],
				"insurance_selection" => ($data['insuranceProvider'] ? $data['insuranceProvider'] : ""),
				"test_category" => ($data['ActualTestCategory'] ? $data['ActualTestCategory'] : ""),
				"test_name" => ($data['TestName'] ? $data['TestName'] : ""),
				"pregnant_with_multiples" => $customerData['MultiFetalGestation'],
				"number_of_children" => $customerData["NumberOfChildren"],
				"coverage_twins" => $customerData['coverage_twins'],
				"coverage_triplets" => $customerData['coverage_triplets'],
				"coverage_four_or_more" => $customerData['coverage_four_or_more'],
				"error_text" => (isset($data['ErrorText']) ? $data['ErrorText'] : ""),
				"eligibility" => (isset($customerData['Eligibility']) ? $customerData['Eligibility'] : ""),
				"OOP_Cost" => $finalCostEstimate,
				"member_estimate_age_at_delivery" => ($age ? $age : ""),
				"average_risk_partner" => (isset($isAverageRiskTradingPartner) ? $isAverageRiskTradingPartner : ""),
				"high_risk" => (isset($isHighRisk) ? $isHighRisk : ""),
				"service" => (isset($fromService) ? $fromService : ""),
				"service_status" => $serviceStatus
			);
			$customerData["dataToEvaluate"] = (isset($dataToEvaluate) ? $dataToEvaluate : "");
			$this->setCustomerData($customerData);
			return $dataToEvaluate;
		}
		
	}
	
	$cec = new CostEstimateController;
	$phoenixController = $cec->getPhoenixController();
	$chcLookupController = $cec->getCHCLookupController();
	$salesforceController = $cec->getSalesforceController();
	$frontEndResponse = $cec->getFrontEndResponse();
	$returnData = array();
	
	//TODO: if they have selected either of the top two insurance options:
	//a Cost Estimate Record and Case will be created and the user will receive specified feedback 

	$customerData = (array) json_decode(file_get_contents('php://input'), TRUE);
	//$customerData = (array) json_decode('{"user":{"Member_FirstName":"Sara","Member_LastName":"Panebianco","Member_BirthDate":"07/27/1990","Member_Gender":"Female","Member_State":"Alabama","Member_PrimaryPhone":"8588378077","Member_PrimaryPhoneType":"Mobile","Member_SecondaryPhone":"","Member_SecondaryPhoneType":"","GeneticCounselingReceived":"I dont know","ConsentToLeaveMessage":"Yes","ConsentToContact":"Yes","Member_Email":"test@labcorp.com","Member_Identifier":"Patient","CallbackSelection":"PM | 2pm – 4pm EST","isMember_StateRequired":true,"isMember_GenderRequired":false,"isMember_PrimaryPhoneValid":true,"ActualTestCategory":"NIPT","TestName":"MaterniT 21 PLUS","TestBundle":{"key":"81420"},"insuranceProvider":"279","TradingPartnerId":{"key":"449","value":""},"PayerId":"EMPME","PayerName":"United Healthcare","MultiFetalGestation":"No","HighRisk":"No","Member_Id":"933543482","isMember_Id_Enabled":true,"isMember_Id_Required":true,"DueDate":null,"isMember_DueDateValid":true,"ServiceType":{"key":"5"},"CoverageActive":true,"Provider_Name":"Sequenom, Inc.","Member_Id_Is_Enabled":true,"RecaptchaResponse":"03AGdBq242r3UqATZrDhnoMvhUGDG69cOFL7czZQ8OzySgBK4QbZfHrJG6zjF8FhLqlo073YUqt_g56NWoBjEdYsEb2SakFPkPLD57j5MbaI8R2OmRC717dQ3eGzDQb6m2grc6-fIG0EpcZWFEZ9Iis6w5DKcRXeKZajYq15Yr4SL1N27w9pWG4zFnCGk7bdDPqS7VYjeHYGIueYIrdPeANoarikutMO0Gy2hJ7DISj43BmvhloROSb2XuNxXqL7QC_iMwkzjehR8TrM8H06Rf2k1So0z3d6EUkgGHqHHo29XqIKQQwmACHsrUSfcTwppOaxs58FMDmBEeFx_LRq91gXgflj01ZNDuM7UyzSM1yzssS93T_VauKTJqbNC2M0EgaJ17Yy6-6CJ1LxOKsFYZFW0LE4Sy4NDBXgxPhciohpVtAkEqXkZJyhBmxPo6DQfs2lNRxBDUsYUt","QuestionAnswer":[null,null,null,{"id":3,"answer":{"id":"1"}},null,null,null,null,{"id":8,"answer":{"id":"1"}}],"tests":[{"id":6,"questions":[{"id":3,"answer":{"id":"1"}},{"id":8,"answer":{"id":"1"}}]}],"action":"newEstimateRequest"}}', TRUE);
	//$customerData = (array) json_decode('{"user":{"Member_FirstName":"Virginia","Member_LastName":"Taylor","Member_BirthDate":"1990-07-27","Member_Gender":"Female","Member_State":"Alabama","Member_PrimaryPhone":"8588378077","Member_PrimaryPhoneType":"Mobile","Member_SecondaryPhone":"","Member_SecondaryPhoneType":"","GeneticCounselingReceived":"I dont know","ConsentToLeaveMessage":true,"ConsentToContact":true,"Member_Email":"test@labcorp.com","Member_Identifier":"Patient","CallbackSelection":"PM | 2pm – 4pm EST","isMember_StateRequired":true,"isMember_GenderRequired":false,"isMember_PrimaryPhoneValid":true,"ActualTestCategory":"NIPT","TestName":"MaterniT 21 PLUS","TestBundle":{"key":"81420"},"insuranceProvider":"279","TradingPartnerId":{"key":"449","value":""},"PayerId":"EMPME","PayerName":"United Healthcare","MultiFetalGestation":false,"HighRisk":false,"Member_Id":"933543482","isMember_Id_Enabled":true,"isMember_Id_Required":true,"DueDate":"2021-12-30","isMember_DueDateValid":true,"ServiceType":{"key":"5"},"CoverageActive":true,"Provider_Name":"Sequenom, Inc.","Member_Id_Is_Enabled":true,"RecaptchaResponse":"03AGdBq242r3UqATZrDhnoMvhUGDG69cOFL7czZQ8OzySgBK4QbZfHrJG6zjF8FhLqlo073YUqt_g56NWoBjEdYsEb2SakFPkPLD57j5MbaI8R2OmRC717dQ3eGzDQb6m2grc6-fIG0EpcZWFEZ9Iis6w5DKcRXeKZajYq15Yr4SL1N27w9pWG4zFnCGk7bdDPqS7VYjeHYGIueYIrdPeANoarikutMO0Gy2hJ7DISj43BmvhloROSb2XuNxXqL7QC_iMwkzjehR8TrM8H06Rf2k1So0z3d6EUkgGHqHHo29XqIKQQwmACHsrUSfcTwppOaxs58FMDmBEeFx_LRq91gXgflj01ZNDuM7UyzSM1yzssS93T_VauKTJqbNC2M0EgaJ17Yy6-6CJ1LxOKsFYZFW0LE4Sy4NDBXgxPhciohpVtAkEqXkZJyhBmxPo6DQfs2lNRxBDUsYUt","QuestionAnswer":[null,null,null,{"id":3,"answer":{"id":"1"}},null,null,null,null,{"id":8,"answer":{"id":"1"}}],"tests":[{"id":6,"questions":[{"id":3,"answer":{"id":"1"}},{"id":8,"answer":{"id":"1"}}]}],"action":"newEstimateRequest","CostEstimationTool":"pokitdok","CostEstimate":0,"EstimateId":"540E2E77-2EE1-4623-959B-8DA809DEB9B7","Member_AgeAtDelivery":31,"AverageRiskPartner":false,"NotCoveredByMedicalPolicy":false,"ActivityId":""}}', TRUE);

	$cec->setCustomerData($customerData['user']);
	$customerData = $cec->getCustomerData();
	
	$estimateID = $cec->generateEstimateID();
	$logger = $cec->getLogger();
	$logger->info('---------------- New Cost Estimate ----------------');
	$logger->info('---------------- Cost Estimate ID:' . $estimateID . ' ----------------');

	$token = $customerData['token'];
/*
	echo("token");
	echo($token);
	echo("session token");
	echo($_SESSION['token']);
*/
	
	//check for token
	if (!$token || $token !== $_SESSION['token']) {
	//	$something = true;
	//if (!$something) {	
		
		/*
			$returnData["passed token"] = $token;
			$returnData["session token"] = $_SESSION['token'];
			$returnData["adam session"] = $_SESSION['adam'];
			$returnData["test session"] = $_SESSION['test session'];
						
			print_r(json_encode($returnData));
			
		*/
	    // return 405 http status code
	    header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
	    exit;
	    
	} else {
		try{
			$stmt = $dbh->prepare("SELECT * FROM used_tokens WHERE token = ? AND usage_count >= 2");
		    $stmt->execute([$token]);
		    
		    if ($stmt->fetch()) {
		        // Token has been used before, return 405 Method Not Allowed
		        header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
		        exit;
		    }
		} catch (Exception $e) {
			print($e);
			exit;
		}
		
		if($customerData['action'] == 'final_phoenix_retry'){
			
				
		}else if($customerData['action'] == 'newEstimateRequest'){
			$stmt = $dbh->prepare("INSERT INTO used_tokens (token, usage_count) VALUES (?, 1)");
			$stmt->execute([$token]);
			
			// check for R2MSC
			if(($customerData["insuranceProvider"] == "I don't have insurance" || $customerData["insuranceProvider"] == "1") || ($customerData["insuranceProvider"] == "I don't see my insurance" || $customerData["insuranceProvider"] == "2")){
			//if(($customerData["insuranceProvider"] == "I don't have insurance" || $customerData["insuranceProvider"] == "1") || ($customerData["insuranceProvider"] == "I don't see my insurance" || $customerData["insuranceProvider"] == "2")){
				$estimate = array(
					"skipCHCAndPhoenix"=>"true"
				);
				
				$returnData["CostEstimationTool"] = "N/A";
				$customerData["CostEstimationTool"] = "N/A";
				$dataForFrontEndEvaluation["CostEstimationTool"] = "N/A";
				$dataForFrontEndEvaluation["insurance_selection"] = $customerData["insuranceProvider"];			
				$dataForFrontEndEvaluation = $cec->calculateInitialCostObjectValues($cec::NONE, $customerData, null);
				$dataForFrontEndEvaluation["skipCHCAndPhoenix"] = true;
				$dataForFrontEndEvaluation["CostEstimate"] = null;
								
				$frontEndMessage = $frontEndResponse->displayFrontEndMessage($dataForFrontEndEvaluation);
	
				$returnData["ADAM_TEST"] = "we skipped calling";
	
			}else{
				$phoenixResult = $phoenixController->returnEstimate($customerData, false, true, $logger); //$data, $successfulEstimationForTesting, $testLive
				$returnData["Phoenix_Estimate"] = $phoenixResult["phoenix_estimate"];
				$returnData["Phoenix_Payload"] = $phoenixResult["phoenix_payload"];
			
				//if($phoenixResult['phoenix_estimate']['results']['successfulEstimation'] && ($customerData['PayerId'] != 'R2MSC' && $customerData['PayerName'] != 'R2MSC')){
					//we will want to make sure we populate a few things like the estimate amount, eligibility, etc, before we send it over to calculateInitialCostObjectValues
				if($phoenixResult['phoenix_estimate']['results']['successfulEstimation'] && $customerData['PayerId'] != 'R2MSC'){
					$dataForFrontEndEvaluation = $cec->calculateInitialCostObjectValues($cec::PHOENIX, $customerData, $phoenixResult['phoenix_estimate']['results']);
					$dataForFrontEndEvaluation["skipPokitDok"] = false;
					$dataForFrontEndEvaluation["CoverageActive"] = true;
					$frontEndMessage = $frontEndResponse->displayFrontEndMessage($dataForFrontEndEvaluation);
					
	
					$returnData["NEWCOST"] = $phoenixResult['phoenix_estimate']['results']['estimatedAmount'];
					$returnData["NEWCOST2"] = "hi";
					//reconsider
					
				}else{
					
					//temporary diversion
					
					//$dataForEstimate = json_decode($dataForEstimate, true);
					$dataForEstimate["DDAmount"] = null;
					$dataForEstimate["COAmount"] = null;
					$dataForEstimate["TestPrice"] = null;
					$dataForEstimate["CostEstimate"] = null;
					//$dataForEstimate = json_encode($dataForEstimate);
					//$customerData["ActiveCoverage"] = false;
					$customerData["CoverageActive"] = false;
					$customerData["CostEstimateCurrency"] = null;
					$customerData["CostEstimate"] = null;

					//$returnData["CHC_Payload"] = $chcResult["chc_payload"];
					//$returnData["CHC_Estimate"] = $chcResult["chc_estimate"];
					$returnData["DDRetry"] = "false";
					$returnData["DDAmount"] = null;
					$returnData["COAmount"] = null;
					$returnData["TestPrice"] = 0;
					$returnData["CostEstimate"] = null;

					$dataForFrontEndEvaluation = $cec->calculateInitialCostObjectValues($cec::CHC, $customerData, $dataForEstimate);
					//$frontEndMessage = $frontEndResponse->displayFrontEndMessage($dataForFrontEndEvaluation);
					
					$frontEndMessage = array(
						"rule" => 6, 
						"response_index" => 6, 
						"response" => array(
							"response_for_front_end" => "We are unable to return an estimate based on the information you provided. Please verify the insurance information entered is current.  If you would like to call one of our team members directly, contact us at 844.799.3243.",
							"include_legal_disclaimer" => false,
							"description_of_message" => "* No active coverage. * Error getting eligibility from trading partner * User error, selecting incorrect health plan or entering incorrect data *Insurance may have other information on file (common discrepancies: Last name, DOB)[NUM_FETAL]",
							"likely_to_proceed" => false,
							"create_cost_estimate" => true,
							"create_salesforce_case" => false,
							"additional_actions" => "",
							"additional_actions_message" => "Thank you! We have an estimate for [TEST]. Details below.",
							"legal_disclaimer" => "",
							"language_disclaimer" => "If you speak Spanish please contact a member from our team at 844.799.3243 (we would translate this to Spanish)",
							"cost_estimate_id_message" => "Your cost estimate number is <b>[COST_ESTIMATE_ID]</b>. Please have your cost estimator ID number available when you speak to one of our team members. ",
							"contains_cost_estimate" => false
						), 
						"data_to_evaluate" => array()
					);
						
					/*	
					//need to query here...
					$testprice = $chcLookupController->getTestPrice($customerData['TradingPartnerId']['value'], $customerData['TestBundle']['key']);
	
					$serviceTypeCodes = '"5"';
					$serviceTypeCodes = '"5","73","CL","10","82","1","30"';
					$chcResult = $chcLookupController->returnEstimate($customerData, false, true, $logger, $serviceTypeCodes); //$data, $successfulEstimationForTesting, $testLive
					$dataForEstimate = json_encode($chcResult["chc_estimate"]["results"]);
					
					if(count($chcResult["chc_estimate"]["results"]["planStatus"]) == 1){
						$returnData["ADAM_TEST_THIS"] = "made it here 1";
						
						if($chcResult["chc_estimate"]["results"]["planStatus"][0]["status"] == "Active Coverage"){
							$activeCoverage = true;
						}
					}else if(count($chcResult["chc_estimate"]["results"]["planStatus"]) > 1){
						$activeCoverage = false;
						$returnData["ADAM_TEST_THIS"] = "made it here 2";
						//loop through all
						foreach($chcResult["chc_estimate"]["results"]["planStatus"] as $plan){
							if($plan["status"] == "Active Coverage"){
								$activeCoverage = true;
							}
						}
					}else{
						$returnData["ADAM_TEST_THIS"] = "made it here 3";
						$activeCoverage = false;
					}
					
					
					//if($chcResult["chc_estimate"]["results"]["planStatus"][0]["status"] != "Active Coverage"){ //check status 
					if(!$activeCoverage){
						// echo("<div class='result'><div style='width:600px;height:500px;overflow:hidden;padding:20px;'>");
						// echo ('<h3>7. Response for Frontend</h3>');
						// echo ('<p>'.$chcResult["chc_estimate"]["results"]["planStatus"][0]["status"].'</p>');
						// echo("</div></div>");
	
						// we do not have active coverage
	
						//we can't estimate.
						
						$dataForEstimate = json_decode($dataForEstimate, true);
						$dataForEstimate["DDAmount"] = null;
						$dataForEstimate["COAmount"] = null;
						$dataForEstimate["TestPrice"] = null;
						$dataForEstimate["CostEstimate"] = null;
						//$dataForEstimate["ActiveCoverage"] = false;
						$dataForEstimate["CoverageActive"] = false;
						
						$dataForEstimate["TradingPartnerId"]["value"] = $customerData['TradingPartnerId']['value'];
	
						$dataForEstimate = json_encode($dataForEstimate);
						$customerData["CoverageActive"] = false;
						$customerData["CostEstimateCurrency"] = null;
						$customerData["CostEstimate"] = null;
						$customerData["TradingPartnerId"]["value"] = $customerData['TradingPartnerId']['value'];
	
						$returnData["CHC_Payload"] = $chcResult["chc_payload"];
						$returnData["CHC_Estimate"] = $chcResult["chc_estimate"];
						$returnData["DDRetry"] = "false";
						$returnData["DDAmount"] = null;
						$returnData["COAmount"] = null;
						$returnData["TestPrice"] = $testprice;
						$returnData["CostEstimate"] = null;
						$returnData["CoverageActive"] = false;
	
						$returnData["ADAM_TEST"] = "we don't have active coverage";
						$returnData["customer_data_before_calculation"] = $customerData;
						$customerData["gotta be here"] = "something";
						
						$dataForFrontEndEvaluation = $cec->calculateInitialCostObjectValues($cec::CHC, $customerData, $dataForEstimate);
						$frontEndMessage = $frontEndResponse->displayFrontEndMessage($dataForFrontEndEvaluation);
	
						
						// $dataForEstimate = json_decode($dataForEstimate, true);
						// $dataForEstimate["DDAmount"] = $DDAmount;
						// $dataForEstimate["COAmount"] = $COAmount;
						// $dataForEstimate["TestPrice"] = $testprice;
						// $dataForEstimate["CostEstimate"] = $costEstimate;
						// $dataForEstimate = json_encode($dataForEstimate);
						// $dataForFrontEndEvaluation = $cec->calculateInitialCostObjectValues($cec::CHC, $customerData, $dataForEstimate);
						// $frontEndMessage = $frontEndResponse->displayFrontEndMessage($dataForFrontEndEvaluation);
	
						// $returnData["CHC_Payload"] = $chcResult["chc_payload"];
						// $returnData["CHC_Estimate"] = $chcResult["chc_estimate"];
						// $returnData["DDRetry"] = "false";
						// $returnData["DDAmount"] = $DDAmount;
						// $returnData["COAmount"] = $COAmount;
						// $returnData["TestPrice"] = $testprice;
						// $returnData["CostEstimate"] = $costEstimate;
	
					}else if(!empty($result->errors)){ //check errors 
						//we have errors to check.
	
						if($result->errors[1]->code == 72){
							echo("<div class='result'><div style='width:600px;height:500px;overflow:hidden;padding:20px;'>");
							echo ('<h3>8. Response for Frontend</h3>');
							echo ('<p>Invalid Subscriber - User error, selecting incorrect health plan or entering incorrect data</p>');
							echo("</div></div>");
						}else if($result->errors[1]->code == 48){
							echo("<div class='result'><div style='width:600px;height:500px;overflow:hidden;padding:20px;'>");
							echo ('<h3>9. Response for Frontend</h3>');
							echo ('<p>Invalid/Missing Referring Provider Identification Number</p>');
							echo("</div></div>");
						}else{
							echo("<div class='result'><div style='width:600px;height:500px;overflow:hidden;padding:20px;'>");
							echo ('<h3>10. Response for Frontend</h3>');
							echo ('<p>'.$result->errors[0]->description.'</p>');
							echo("</div></div>");
						}
	
						//we can't estimate.
						$dataForEstimate = json_decode($dataForEstimate, true);
						$dataForEstimate["DDAmount"] = null;
						$dataForEstimate["COAmount"] = null;
						$dataForEstimate["TestPrice"] = null;
						$dataForEstimate["CostEstimate"] = null;
						$dataForEstimate = json_encode($dataForEstimate);
						//$customerData["ActiveCoverage"] = false;
						$customerData["CoverageActive"] = false;
						$customerData["CostEstimateCurrency"] = null;
						$customerData["CostEstimate"] = null;
	
						$returnData["CHC_Payload"] = $chcResult["chc_payload"];
						$returnData["CHC_Estimate"] = $chcResult["chc_estimate"];
						$returnData["DDRetry"] = "false";
						$returnData["DDAmount"] = null;
						$returnData["COAmount"] = null;
						$returnData["TestPrice"] = $testprice;
						$returnData["CostEstimate"] = null;
	
						$dataForFrontEndEvaluation = $cec->calculateInitialCostObjectValues($cec::CHC, $customerData, $dataForEstimate);
						$frontEndMessage = $frontEndResponse->displayFrontEndMessage($dataForFrontEndEvaluation);
	
						
	
					}else{
						$DDAmountTemp = $chcLookupController->startDDCostEstimate($dataForEstimate, false);
						
						$DDAmount = $DDAmountTemp['finalDeductible'];
						$DDAmountNode = $DDAmountTemp['finalDeductibleNode'];
						$returnData["DDAmountNode"] = $DDAmountNode;
	
						$returnData["ADAM_TEST"] = "we might need to retry, we will check: " . $DDAmount;
									
						if($DDAmount == "retry" && $DDAmount != "0"){
							sleep(2);
							$serviceTypeCodes2 = '"30"';
							$chcResult2 = $chcLookupController->returnEstimate($customerData, false, true, $logger, $serviceTypeCodes2); //$data, $successfulEstimationForTesting, $testLive
							$dataForEstimate2 = json_encode($chcResult2["chc_estimate"]["results"]);
							
							$DDAmount2 = $chcLookupController->startDDCostEstimate($dataForEstimate2, false);
	
							if($DDAmount2 == "retry"){
								$DDAmount2 = 0;
							}
							$COAmount = $chcLookupController->startCOCostEstimate($dataForEstimate2);
							$costEstimate = $chcLookupController->GetEstimatedOOP(floatval($DDAmount2), floatval($COAmount), floatval($testprice));
	
							$dataForEstimate2 = json_decode($dataForEstimate2, true);
							$dataForEstimate2["DDAmount"] = $DDAmount2;
							$dataForEstimate2["COAmount"] = $COAmount;
							$dataForEstimate2["TestPrice"] = $testprice;
							$dataForEstimate2["CostEstimate"] = $costEstimate;
							$dataForEstimate2 = json_encode($dataForEstimate2);
							//$customerData["ActiveCoverage"] = false;
							$customerData["CoverageActive"] = false;
							$customerData["CostEstimate"] = $costEstimate;
							$customerData["CostEstimateCurrency"] = "USD";
							$cec->setCustomerData($customerData);
	
							$returnData["CHC_Payload"] = $chcResult2["chc_payload"];
							$returnData["CHC_Estimate"] = $chcResult2["chc_estimate"];
							$returnData["DDRetry"] = "true";
							$returnData["DDAmount"] = $DDAmount2;
							$returnData["COAmount"] = $COAmount;
							$returnData["TestPrice"] = $testprice;
							$returnData["CostEstimate"] = $costEstimate;
							$returnData["CostEstimateCurrency"] = "USD";
	
							$dataForFrontEndEvaluation = $cec->calculateInitialCostObjectValues($cec::CHC, $customerData, $dataForEstimate2);
							$frontEndMessage = $frontEndResponse->displayFrontEndMessage($dataForFrontEndEvaluation);
	
							
						}else{
							// echo("i don't need to retry");
							// echo($DDAmount);
							$COAmount = $chcLookupController->startCOCostEstimate($dataForEstimate);
							$costEstimate = $chcLookupController->GetEstimatedOOP(floatval($DDAmount), floatval($COAmount), floatval($testprice));
	
							$returnData["ADAM_TEST"] = "we should have called: " . $COAmount;
							$returnData["ADAM_TEST_cost"] = "we should have estimate: " . $costEstimate;
	
							$returnData["ADAM_costEstimate"] = "we should have called: " . $costEstimate;
							
	
							$dataForEstimate = json_decode($dataForEstimate, true);
							$dataForEstimate["DDAmount"] = $DDAmount;
							$dataForEstimate["COAmount"] = $COAmount;
							$dataForEstimate["TestPrice"] = $testprice;
							$dataForEstimate["CostEstimate"] = $costEstimate;
							$dataForEstimate = json_encode($dataForEstimate);
							//$customerData["ActiveCoverage"] = false;
							$customerData["CoverageActive"] = true;
							$customerData["CostEstimate"] = $costEstimate;
							$customerData["CostEstimateCurrency"] = "USD";
	
							$returnData["CHC_Payload"] = $chcResult["chc_payload"];
							$returnData["CHC_Estimate"] = $chcResult["chc_estimate"];
							$returnData["DDRetry"] = "false";
							$returnData["DDAmount"] = $DDAmount;
							$returnData["COAmount"] = $COAmount;
							$returnData["TestPrice"] = $testprice;
							$returnData["CostEstimate"] = $costEstimate;
	
							$dataForFrontEndEvaluation = $cec->calculateInitialCostObjectValues($cec::CHC, $customerData, $dataForEstimate);
							$frontEndMessage = $frontEndResponse->displayFrontEndMessage($dataForFrontEndEvaluation);
							
						}
					} */
				}
			}
	
			if($frontEndMessage["response"]["create_cost_estimate"]){
					
				//get our customer data object again
				$customerData = $cec->getCustomerData();
	
				//call the trading partner table query.
				//PayerID is Phoenix
				//"PayerId" => (isset($data["TradingPartnerId"]["value"]
				//Trading Partner is CHC
				//"TradingPartnerId" => (isset($data["TradingPartnerId"]["value"]
				//$customerData["TradingPartnerId"]["value"] = $customerData['PayerName'];
	
				if($frontEndMessage["response"]["additional_actions"] == "Not_Covered_By_Medicial_Policy=NO"){
					$customerData["NotCoveredByMedicalPolicy"] = true;
				}else{
					$customerData["NotCoveredByMedicalPolicy"] = false;
				}
	
				if($customerData["DisplayName"] == "I don't have insurance"){
					$customerData["CostEstimationTool"] = "N/A";
	
					$customerData["PayerName"] = "";
					$customerData["TradingPartnerId"] = "";
	
				}else if($customerData["DisplayName"] == "I don't see my insurance"){
					$customerData["CostEstimationTool"] = "N/A";
	
					$customerData["PayerName"] = "";
					$customerData["TradingPartnerId"] = "";
	
				}else{
					//$customerData["CostEstimateCurrency"] = "USD";
					include '../../db.php';
					$partnerQuery = "select * from trading_partners where id = '" . $customerData["TradingPartnerId"]["tp_id"] . "';";
					foreach($dbh->query($partnerQuery) as $row) {
						$customerData["PayerName"] = $row['phoenix_payer_id'];
						$customerData["TradingPartnerId"] = $row['chc_partner_id'];
					}
					$dbh = null;
				}
				$returnData["partnerQuery"] = $partnerQuery;
				$customerData["EstimateId"] = (isset($estimateID) ? $estimateID : "");
				$customerData["ActivityId"] = "";//(isset($customerData["PokitDok_ActivityID"]) ? $customerData["PokitDok_ActivityID"] : "");
				
				if(!$frontEndMessage['response']['contains_cost_estimate']){
					//$customerData["CostEstimate"] = 0;
					//$customerData["CostEstimateCurrency"] = null;
				}
				$cec->setCustomerData($customerData);
							
				$cleanFrontEndResponse = strip_tags($frontEndMessage['response']['response_for_front_end']);
				//add multi-fetal # if applicable
				if($customerData["MultiFetalGestation"]){
					$tempMessage = strip_tags($frontEndMessage['response']['description_of_message']);
					$tempMessage = str_replace("[NUM_FETAL]", "; multi-fetal " . $customerData["NumberOfChildren"], $tempMessage);
					$frontEndMessage['response']['description_of_message'] = $tempMessage;
					$description_of_message = $frontEndMessage['response']['description_of_message'];
				}else{
					$tempMessage = strip_tags($frontEndMessage['response']['description_of_message']);
					$tempMessage = str_replace("[NUM_FETAL]", "", $tempMessage);
					$frontEndMessage['response']['description_of_message'] = $tempMessage;
					$description_of_message = $frontEndMessage['response']['description_of_message'];
				}
					
/*
				$returnData["CostEstimate"] = "";
				$returnData["CostEstimate_2"] = "";
*/
	
				$costEstimateResult = $salesforceController->createCostEstimateRecord($customerData, $description_of_message, $logger);				
				$returnData["FrontEndMessage"] = $frontEndMessage;
	
				$returnData["CostEstimateCurrency"] = "USD";
				
				$returnData["DataGoingtoSalesforce"] = $customerData;
				
				$returnData["CostEstimateRecordResult"] = $costEstimateResult;
				$logger->debug('CostEstimateRecordResult', $returnData["CostEstimateRecordResult"]);
				$activityID = "";//(isset($customerData["PokitDok_ActivityID"]) ? $customerData["PokitDok_ActivityID"] : "");
				$logger->info('---------------- PokitDok Activity ID:' . $activityID . ' ----------------');	
				
				if(isset($costEstimateResult["Id"]) && $costEstimateResult["Id"] != ""){
					
					$returnData["CostEstimateSuccess"] = $costEstimateResult;
					$returnData["CostEstimateNumber"] = $costEstimateResult["CENumber"];
					$salesforceRecordId = $costEstimateResult["Id"];
					$callbackSelection = $customerData["CallbackSelection"];
					
					if($frontEndMessage["response"]["create_salesforce_case"] && $customerData['ConsentToContact']){
						$costEstimateCaseResult = $salesforceController->createCostEstimateCaseRecord($salesforceRecordId, $callbackSelection, $cleanFrontEndResponse, $logger);
						
						$returnData["CostEstimateCase"] = $costEstimateCaseResult;
						$logger->debug('CostEstimateCase', $returnData["CostEstimateCase"]);
					}else{
						$logger->debug('CostEstimateCase', array("status" => "could not create salesforce case"));
					}
					
				}
				$logger->debug('FrontEndMessage', $returnData["FrontEndMessage"]);
			}
		}else if($customerData['action'] == 'updateSalesforceObject'){
			$stmt = $dbh->prepare("UPDATE used_tokens SET usage_count = usage_count + 1 WHERE token = ?");
			$stmt->execute([$token]);
			
			$customerData["EstimateId"] = $estimateID;
			$cec->setCustomerData($customerData);
					
			$costEstimateUpdateResult = $salesforceController->updateCostEstimateRecord($customerData, $logger);
			$returnData["CostEstimateUpdateResult"] = $costEstimateUpdateResult;
		}
		
		$customerData = $cec->getCustomerData();
		$returnData["CustomerDataFinal"] = $customerData;
		$returnData["estimateID"] = $estimateID;
			
		print_r(json_encode($returnData));
	}