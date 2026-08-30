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

            $this->phoenixController    = new PhoenixController;
            $this->chcLookupController  = new CHCLookUpController;
            $this->salesforceController = new SalesforceController;
            $this->frontEndResponse     = new FrontEndResponse;
            $this->setLogger('/var/www/vhosts/sites/stage/logs-application');
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
            if (function_exists('com_create_guid') === true) {
                return trim(com_create_guid(), '{}');
            }
            return sprintf(
                '%04X%04X-%04X-%04X-%04X-%04X%04X%04X',
                mt_rand(0, 65535),
                mt_rand(0, 65535),
                mt_rand(0, 65535),
                mt_rand(16384, 20479),
                mt_rand(32768, 49151),
                mt_rand(0, 65535),
                mt_rand(0, 65535),
                mt_rand(0, 65535)
            );
        }
        private function roundUpToAny($n, $x=5){
            return (round($n)%$x === 0) ? round($n) : round(($n+$x/2)/$x)*$x;
        }
        private function calculateFutureAge($birth_date, $future_date){

            list($yr,$mo,$day) = explode('-',$birth_date);
            list($future_y,$future_m,$future_day) = explode('-',$future_date);

            $now = ($future_y*10000+$future_m*100+$future_day);
            $past = ($yr*10000+$mo*100+$day);
            $diff = ($past-$now);

            if ($diff > 0) {
                $age = 0 ;
            } else {
                $age = (($future_y-$yr)-1);
                if (($future_m>$mo) || (($future_m>=$mo) && ($future_day>=$day))) {
                    $age++;
                }
            }
            return $age;
        }
        
        public function preprocessCostEstimateData($userData)
		{
		    $customerData = $userData;
		    
		    // Handle MultiFetalGestation
		    if($customerData["MultiFetalGestation"] == "No"){
                $MultiFetalGestation = false;
            } else if($customerData["MultiFetalGestation"] == "Twins"){
                $MultiFetalGestation = true;
            } else if($customerData["MultiFetalGestation"] == "Triplets"){
                $MultiFetalGestation = true;
            } else if($customerData["MultiFetalGestation"] == "More than three"){
                $MultiFetalGestation = true;
            }
            if($MultiFetalGestation){
                $customerData["MultiFetalGestation"] = true;
            } else {
                $customerData["MultiFetalGestation"] = false;
            }
            $customerData["NumberOfChildren"] = 0;
		    
		    // Calculate Estimated Cost
		    $costEstimate = $userData["CostEstimate"] ?? null;
		    
		    if ($costEstimate === 0) {
		        $finalCostEstimate = 0;
		    } elseif ($costEstimate <= 275) {
		        $finalCostEstimate = $this->roundUpToAny($costEstimate, 25);
		    } elseif ($costEstimate > 275) {
		        $finalCostEstimate = number_format((float) $costEstimate, 2, '.', '');
		    } else {
		        $finalCostEstimate = "";
		    }
		    
		    $customerData["CostEstimate"] = $finalCostEstimate;
		    $customerData["CostEstimateCurrency"] = "USD";
		
		    // Calculate Member Age at Delivery
		    $birthDate = date("Y-m-d", strtotime($userData["Member_BirthDate"]));
		    if (!empty($userData["DueDate"])) {
		        $dueDate = substr($userData["DueDate"], 0, 10);
		    } else {
		        $dueDate = date("Y-m-d"); // Default to today if not provided
		    }
		    $customerData["DueDate"] = $dueDate;
		    $customerData["Member_BirthDate"] = $birthDate;
		    $customerData["Member_AgeAtDelivery"] = $this->calculateFutureAge($birthDate, $dueDate);
		
		    // Determine HighRisk
		    if($data["HighRisk"] == "Yes"){
                $isHighRisk = true;
            } else {
                $isHighRisk = false;
            }
		    if ($customerData["Member_AgeAtDelivery"] > 35 && $userData["ActualTestCategory"] == "NIPT") {
		        $isHighRisk = true;
		    }
		    $customerData["HighRisk"] = $isHighRisk;
		
		    // Consent Flags
		    $customerData["ConsentToLeaveMessage"] = $userData["ConsentToLeaveMessage"] === "Yes";
		    $customerData["ConsentToContact"] = $userData["ConsentToContact"] === "Yes";
		
		    // Coverage Active Processing
		    foreach (["coverage_twins", "coverage_triplets", "coverage_four_or_more"] as $coverageType) {
		        if (!empty($userData[$coverageType])) {
		            $coverageDate = date("Y-m-d H:i:s", strtotime($userData[$coverageType]));
		            $customerData[$coverageType] = $coverageDate < date("Y-m-d H:i:s");
		        } else {
		            $customerData[$coverageType] = false;
		        }
		    }
		
		    // Finalize Data Structure
		    $customerData["EstimateId"] = $userData["EstimateId"] ?? "";
		    $customerData["AverageRiskPartner"] = $userData["isAverageRiskTradingPartner"] ?? "";
		    
		    if($userData["DisplayName"] == "I don't have insurance"){
                $customerData["CostEstimationTool"] = "N/A";
                $customerData["PayerName"] = "";
                $customerData["TradingPartnerId"] = "";
            } else if($userData["DisplayName"] == "I don't see my insurance"){
                $customerData["CostEstimationTool"] = "N/A";
                $customerData["PayerName"] = "";
                $customerData["TradingPartnerId"] = "";
            } else {
                // Look up Payer/TradingPartner in DB
                include '../../db.php';
                $partnerQuery = "select * from trading_partners where id = '" . $userData["TradingPartnerId"]["tp_id"] . "';";
                foreach($dbh->query($partnerQuery) as $row) {
                    $customerData["PayerName"]        = $row['phoenix_payer_id'];
                    $customerData["TradingPartnerId"] = $row['chc_partner_id'];
                }
                $dbh = null;
                $returnData["partnerQuery"] = $partnerQuery;
            }
            if($userData["ActualTestCategory"] == "NIPT" && $MultiFetalGestation){
                $customerData["NotCoveredByMedicalPolicy"] = true;
            }else{
	            $customerData["NotCoveredByMedicalPolicy"] = false;
            }
		    
		    return $customerData;
		}


        public function calculateInitialCostObjectValues($fromService, $data, $estimateData){
            $dataToEvaluate = array();
            $customerData = $data;

            if($data["MultiFetalGestation"] == "No"){
                $MultiFetalGestation = false;
            } else if($data["MultiFetalGestation"] == "Twins"){
                $MultiFetalGestation = true;
            } else if($data["MultiFetalGestation"] == "Triplets"){
                $MultiFetalGestation = true;
            } else if($data["MultiFetalGestation"] == "More than three"){
                $MultiFetalGestation = true;
            }
            if($MultiFetalGestation){
                $customerData["MultiFetalGestation"] = true;
            } else {
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

            } else if($fromService == $this::CHC){
                $estimateData = json_decode($estimateData, true);

                $customerData["CostEstimationTool"] = $this::CHC;

                if(!empty($estimateData["planStatus"])){
                    if(count($estimateData["planStatus"]) == 1){
                        if($estimateData["planStatus"][0]["status"] == "Active Coverage"){
                            $customerData["CoverageActive"] = true;
                            $serviceStatus = "Active";
                        }
                    } else if(count($estimateData["planStatus"]) > 1){
                        $activeCoverage = false;
                        //loop through all
                        foreach($estimateData["planStatus"] as $plan){
                            if($plan["status"] == "Active Coverage"){
                                $customerData["CoverageActive"] = true;
                                $serviceStatus = "Active";
                            }
                        }
                    } else {
                        $customerData["CoverageActive"] = false;
                        $serviceStatus = "No active coverage. Estimate cannot be calculated.";
                        $customerData["CostEstimate"] = null;
                    }
                }

                if(!empty($estimateData["planInformation"])){
                    $customerData["GroupNumber"] = (isset($estimateData["planInformation"]["groupNumber"]) ? $estimateData["planInformation"]["groupNumber"] : "");
                    $customerData["PlanBeginDate"] = null;
                }

                // we are going to check if they have coinsurance.
                if($estimateData["COAmount"] >= 0){
                    $customerData["A_InsideCoInsurance"] = 'Top';
                    $customerData["Coinsurance"] = $estimateData["COAmount"];
                    $customerData["CostEstimate"] = $estimateData["CostEstimate"];
                    $data["CostEstimate"] = $estimateData["CostEstimate"];
                } else {
                    $customerData["MadeItInConinsurance"] = 'Bottom';
                    $customerData["Coinsurance"] = "no";
                    $customerData["CostEstimateOriginal"] = $estimateData["CostEstimate"];
                }

                if($data["ActualTestCategory"] == "NIPT" && $MultiFetalGestation){
                    $customerData["NotCoveredByMedicalPolicy"] = true;
                }

                $customerData["Eligibility"] = null;
                $customerData["DeductibleBenefitAmount"] = (isset($estimateData["DDAmount"]) ? $estimateData["DDAmount"] : null);
                $customerData["CHC_Meta"] = (isset($estimateData["meta"]) ? $estimateData["meta"] : "");
                $customerData["CHC_ActivityID"] = (isset($estimateData["meta"]["traceId"]) ? $estimateData["meta"]["traceId"] : "");

                $costEstimate = (isset($estimateData["CostEstimate"]) ? $estimateData["CostEstimate"] : null);

                $chcLookupController = $this->getCHCLookupController();
                //check what to query by
                if($data["TradingPartnerId"]["value"] != ""){
                    $isAverageRiskTradingPartner = $chcLookupController->isAverageRiskPartner($data["TradingPartnerId"]["value"], "chc_partner_id");
                } else {
                    $isAverageRiskTradingPartner = $chcLookupController->isAverageRiskPartner($data["PayerName"], "phoenix_payer_id");
                }

                $customerData["isAverageRiskTradingPartner"] = $isAverageRiskTradingPartner["result"];
                $customerData["isAverageRiskTradingPartner_Date"] = $isAverageRiskTradingPartner["date"];
                $customerData["isAverageRiskTradingPartner_Query"] = $isAverageRiskTradingPartner["query"];

            } else {
                $costEstimate = (isset($data["CostEstimate"]) ? $data["CostEstimate"] : null);
                $customerData["Coinsurance"] = "no";
                $customerData["NotCoveredByMedicalPolicy"] = true;
                $customerData["CoverageActive"] = false;
                $customerData["CostEstimate"] = null;
                $costEstimate = null;
            }

            /*
                Round COST_ESTIMATE:
                  - If Estimate = 0, do not round up
                  - If Estimate < $275, round up to nearest $25
                  - If Estimate >= $275, do not round
            */
            if($costEstimate == 0){
                $finalCostEstimate = $costEstimate;
            } else if($costEstimate <= 275){
                $finalCostEstimate = $this->roundUpToAny($costEstimate, 25);
            } else if($costEstimate > 275){
                $finalCostEstimate = number_format((float)$costEstimate, 2, '.', '');
            } else {
                $finalCostEstimate = "";
            }

            /*
                Calculate MEMBER_ESTIMATED_AGE_AT_DELIVERY
            */
            if($data["DueDate"] != ""){
                $formattedBirthday = date("Y-m-d", strtotime($data["Member_BirthDate"]));
                $formattedDueDate = substr($data["DueDate"], 0, 10);
                $customerData["DueDate"] = $formattedDueDate;
                $age = $this->calculateFutureAge($formattedBirthday, $formattedDueDate);
                $customerData["Member_BirthDate"] = $formattedBirthday;
            } else {
                $customerData["DueDate"] = date("Y-m-d");
                $formattedBirthday = date("Y-m-d", strtotime($data["Member_BirthDate"]));
                $age = $this->calculateFutureAge($formattedBirthday, date("Y-m-d"));
                $customerData["Member_BirthDate"] = $formattedBirthday;
            }

            if($data["HighRisk"] == "Yes"){
                $isHighRisk = true;
            } else {
                $isHighRisk = false;
            }

            // If Age > 35 and test is NIPT, change HIGH_RISK to "Yes"
            if($age > 35 && $data["ActualTestCategory"] == "NIPT"){
                $isHighRisk = true;
            }

            if($data["ConsentToLeaveMessage"] == "Yes"){
                $customerData["ConsentToLeaveMessage"] = true;
            } else {
                $customerData["ConsentToLeaveMessage"] = false;
            }
            if($data["ConsentToContact"] == "Yes"){
                $customerData["ConsentToContact"] = true;
            } else {
                $customerData["ConsentToContact"] = false;
            }

            if($data["coverage_twins"] != ""){
                $today = date("Y-m-d H:i:s");
                $date = date("Y-m-d H:i:s", strtotime($data["coverage_twins"]));
                if($date < $today){
                    $customerData["coverage_twins"] = true;
                } else {
                    $customerData["coverage_twins"] = false;
                }
            } else {
                $customerData["coverage_twins"] = false;
            }
            if($data["coverage_triplets"] != ""){
                $today = date("Y-m-d H:i:s");
                $date = date("Y-m-d H:i:s", strtotime($data["coverage_triplets"]));
                if($date < $today){
                    $customerData["coverage_triplets"] = true;
                } else {
                    $customerData["coverage_triplets"] = false;
                }
            } else {
                $customerData["coverage_triplets"] = false;
            }
            if($data["coverage_four_or_more"] != ""){
                $today = date("Y-m-d H:i:s");
                $date = date("Y-m-d H:i:s", strtotime($data["coverage_four_or_more"]));
                if($date < $today){
                    $customerData["coverage_four_or_more"] = true;
                } else {
                    $customerData["coverage_four_or_more"] = false;
                }
            } else {
                $customerData["coverage_four_or_more"] = false;
            }

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
                "service_status" => (isset($serviceStatus) ? $serviceStatus : "")
            );
            $customerData["dataToEvaluate"] = (isset($dataToEvaluate) ? $dataToEvaluate : "");
            $this->setCustomerData($customerData);
            return $dataToEvaluate;
        }

    }

    $cec = new CostEstimateController;
    $phoenixController    = $cec->getPhoenixController();
    $chcLookupController  = $cec->getCHCLookupController();
    $salesforceController = $cec->getSalesforceController();
    $frontEndResponse     = $cec->getFrontEndResponse();
    $returnData           = array();

    // If they've selected top 2 insurance options, a Cost Estimate + Case are created, user gets specified feedback
    $customerData = (array) json_decode(file_get_contents('php://input'), TRUE);

    $cec->setCustomerData($customerData['user']);
    $customerData = $cec->getCustomerData();

    $estimateID = $cec->generateEstimateID();
    $logger = $cec->getLogger();
    $logger->info('---------------- New Cost Estimate ----------------');
    $logger->info('---------------- Cost Estimate ID:' . $estimateID . ' ----------------');

    $token = $customerData['token'];

    // Check for token
    if (!$token || $token !== $_SESSION['token']) {
        // Return 405 if tokens do not match
        header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
        exit;
    } else {
        try {
            $stmt = $dbh->prepare("SELECT * FROM used_tokens WHERE token = ? AND usage_count >= 2");
            $stmt->execute([$token]);

            if ($stmt->fetch()) {
                // Token has been used before, return 405
                header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
                exit;
            }
        } catch (Exception $e) {
            print($e);
            exit;
        }

        if($customerData['action'] == 'create_salesforce_record'){
            $processedCustomerData = $cec->preprocessCostEstimateData($customerData);
            
			$costEstimateResult = $salesforceController->createCostEstimateRecord($processedCustomerData, $description_of_message, $logger);
			$returnData["ProcessedCustomerData"] = $processedCustomerData;
			$returnData["CostEstimateRecordResult"] = $costEstimateResult;
			print_r(json_encode($returnData));
			exit;
        }
        else if($customerData['action'] == 'newEstimateRequest'){
            $stmt = $dbh->prepare("INSERT INTO used_tokens (token, usage_count) VALUES (?, 1)");
            $stmt->execute([$token]);

            // check for R2MSC, or "I don't have insurance" or "I don't see my insurance"
            if(
                ($customerData["insuranceProvider"] == "I don't have insurance" || $customerData["insuranceProvider"] == "1")
                || ($customerData["insuranceProvider"] == "I don't see my insurance" || $customerData["insuranceProvider"] == "2")
            ) {
                $estimate = array("skipCHCAndPhoenix"=>"true");

                $returnData["CostEstimationTool"] = "N/A";
                $customerData["CostEstimationTool"] = "N/A";
                $dataForFrontEndEvaluation["CostEstimationTool"] = "N/A";
                $dataForFrontEndEvaluation["insurance_selection"] = $customerData["insuranceProvider"];

                $dataForFrontEndEvaluation = $cec->calculateInitialCostObjectValues($cec::NONE, $customerData, null);
                $dataForFrontEndEvaluation["skipCHCAndPhoenix"] = true;
                $dataForFrontEndEvaluation["CostEstimate"] = null;

                $frontEndMessage = $frontEndResponse->displayFrontEndMessage($dataForFrontEndEvaluation);
                //$returnData["ADAM_TEST"] = "we skipped calling";

            } else {
                // Attempt Phoenix
                $phoenixResult = $phoenixController->returnEstimate($customerData, false, true, $logger);
                $returnData["Phoenix_Estimate"] = $phoenixResult["phoenix_estimate"];
                $returnData["Phoenix_Payload"]  = $phoenixResult["phoenix_payload"];

                // If Phoenix was successful and not R2MSC
                if($phoenixResult['phoenix_estimate']['results']['successfulEstimation'] && $customerData['PayerId'] != 'R2MSC'){
                    $dataForFrontEndEvaluation = $cec->calculateInitialCostObjectValues(
                        $cec::PHOENIX,
                        $customerData,
                        $phoenixResult['phoenix_estimate']['results']
                    );
                    $dataForFrontEndEvaluation["skipPokitDok"] = false;
                    $dataForFrontEndEvaluation["CoverageActive"] = true;
                    $frontEndMessage = $frontEndResponse->displayFrontEndMessage($dataForFrontEndEvaluation);

                    //$returnData["NEWCOST"]  = $phoenixResult['phoenix_estimate']['results']['estimatedAmount'];
                    //$returnData["NEWCOST2"] = "hi";

                } else {
                    // If Phoenix fails or R2MSC, we basically fall into the fallback message
                    $dataForEstimate["DDAmount"] = null;
                    $dataForEstimate["COAmount"] = null;
                    $dataForEstimate["TestPrice"] = null;
                    $dataForEstimate["CostEstimate"] = null;
                    $customerData["CoverageActive"] = false;
                    $customerData["CostEstimateCurrency"] = null;
                    $customerData["CostEstimate"] = null;

                    $returnData["DDRetry"] = "false";
                    $returnData["DDAmount"] = null;
                    $returnData["COAmount"] = null;
                    $returnData["TestPrice"] = 0;
                    $returnData["CostEstimate"] = null;

                    $dataForFrontEndEvaluation = $cec->calculateInitialCostObjectValues($cec::CHC, $customerData, $dataForEstimate);

                    // Overriding with fallback frontEndMessage
                    $frontEndMessage = array(
                        "rule" => 9,
                        "response_index" => 9,
                        "response" => array(
							"response_for_front_end" => "<p><b>Based on the information you provided, we would like to discuss the best options to fit your financial needs.</b></p><p>We will call or email you in 30 minutes or less or at your preferred contact time at your provided contact number/email address during our business hours.</p><p>Our business hours are M-F 8am-7pm EST. Outside of business hours we will call you the next business day based on your indicated preference.</p><p>If you would like to call one of our team members directly, contact us at 844.799.3243.</p>",
							"include_legal_disclaimer" => false,
							"description_of_message" => "* No active coverage. * Error getting eligibility from trading partner * User error, selecting incorrect health plan or entering incorrect data *Insurance may have other information on file (common discrepancies: Last name, DOB)[NUM_FETAL]",
							"likely_to_proceed" => false,
							"create_cost_estimate" => true,
							"create_salesforce_case" => true,
							"additional_actions" => "",
							"additional_actions_message" => "Thank you! We have an estimate for [TEST]. Details below.",
							"legal_disclaimer" => "",
							"language_disclaimer" => "If you speak Spanish please contact a member from our team at 844.799.3243 (we would translate this to Spanish)",
							"cost_estimate_id_message" => "Your cost estimate number is <b>[COST_ESTIMATE_ID]</b>. Please have your cost estimator ID number available when you speak to one of our team members. ",
							"contains_cost_estimate" => false
                        ),
                        "data_to_evaluate" => array()
                    );
                }
            }

            // ===================== Create Cost Estimate in Salesforce if needed =====================
            if($frontEndMessage["response"]["create_cost_estimate"]){

                // Refresh $customerData from the controller
                $customerData = $cec->getCustomerData();

                // Possibly override NotCoveredByMedicalPolicy if needed
                if($frontEndMessage["response"]["additional_actions"] == "Not_Covered_By_Medicial_Policy=NO"){
                    $customerData["NotCoveredByMedicalPolicy"] = true;
                } else {
                    $customerData["NotCoveredByMedicalPolicy"] = false;
                }

                // If "I don't have insurance" or "I don't see my insurance"
                if($customerData["DisplayName"] == "I don't have insurance"){
                    $customerData["CostEstimationTool"] = "N/A";
                    $customerData["PayerName"] = "";
                    $customerData["TradingPartnerId"] = "";
                } else if($customerData["DisplayName"] == "I don't see my insurance"){
                    $customerData["CostEstimationTool"] = "N/A";
                    $customerData["PayerName"] = "";
                    $customerData["TradingPartnerId"] = "";
                } else {
                    // Look up Payer/TradingPartner in DB
                    include '../../db.php';
                    $partnerQuery = "select * from trading_partners where id = '" . $customerData["TradingPartnerId"]["tp_id"] . "';";
                    foreach($dbh->query($partnerQuery) as $row) {
                        $customerData["PayerName"]        = $row['phoenix_payer_id'];
                        $customerData["TradingPartnerId"] = $row['chc_partner_id'];
                    }
                    $dbh = null;
                    //$returnData["partnerQuery"] = $partnerQuery;
                }

                $customerData["EstimateId"] = (isset($estimateID) ? $estimateID : "");
                $customerData["ActivityId"] = "";

                $cec->setCustomerData($customerData);

                $cleanFrontEndResponse = strip_tags($frontEndMessage['response']['response_for_front_end']);

                // Add multi-fetal # if applicable
                if($customerData["MultiFetalGestation"]){
                    $tempMessage = strip_tags($frontEndMessage['response']['description_of_message']);
                    $tempMessage = str_replace("[NUM_FETAL]", "; multi-fetal " . $customerData["NumberOfChildren"], $tempMessage);
                    $frontEndMessage['response']['description_of_message'] = $tempMessage;
                    $description_of_message = $frontEndMessage['response']['description_of_message'];
                } else {
                    $tempMessage = strip_tags($frontEndMessage['response']['description_of_message']);
                    $tempMessage = str_replace("[NUM_FETAL]", "", $tempMessage);
                    $frontEndMessage['response']['description_of_message'] = $tempMessage;
                    $description_of_message = $frontEndMessage['response']['description_of_message'];
                }

                // ===================== FIRST ATTEMPT to CREATE RECORD =====================
                $costEstimateResult = $salesforceController->createCostEstimateRecord($customerData, $description_of_message, $logger);

                // Put results in $returnData
                $returnData["FrontEndMessage"]        = $frontEndMessage;
                $returnData["CostEstimateCurrency"]   = "USD";
                $returnData["DataGoingtoSalesforce"]  = $customerData;
                $returnData["CostEstimateRecordResult"] = $costEstimateResult;

                $logger->debug('CostEstimateRecordResult', $returnData["CostEstimateRecordResult"]);
                $activityID = "";
                $logger->info('---------------- PokitDok Activity ID:' . $activityID . ' ----------------');

                // Check if success on first attempt
                if (empty($costEstimateResult["success"]) || !$costEstimateResult["success"] || empty($costEstimateResult["Id"])) {
                    // ============ FIRST ATTEMPT FAILED, do second attempt ============
                    $logger->error("First attempt to create cost estimate record failed: " . json_encode($costEstimateResult));
                    sleep(2); // optional short delay

                    // SECOND ATTEMPT
                    $costEstimateResult = $salesforceController->createCostEstimateRecord($customerData, $description_of_message, $logger);
                    $returnData["CostEstimateRecordResult"] = $costEstimateResult;
                    $logger->error("Second attempt result: " . json_encode($costEstimateResult));

                    // If second attempt also fails, override frontEndMessage
                    if (empty($costEstimateResult["success"]) || !$costEstimateResult["success"] || empty($costEstimateResult["Id"])) {
                        $logger->error("Second attempt to create cost estimate record also failed: " . json_encode($costEstimateResult));

                        // Override frontEndMessage with fallback
                        $frontEndMessage = array(
                            "rule" => 9,
                            "response_index" => 9,
                            "response" => array(
                                "response_for_front_end" => "<p><b>Based on the information you provided, we would like to discuss the best options to fit your financial needs.</b></p><p>We will call or email you in 30 minutes or less or at your preferred contact time at your provided contact number/email address during our business hours.</p><p>Our business hours are M-F 8am-7pm EST. Outside of business hours we will call you the next business day based on your indicated preference.</p><p>If you would like to call one of our team members directly, contact us at 844.799.3243.</p>",
				"include_legal_disclaimer" => false,
				"description_of_message" => "Defaulted as no existing rules applied to estimate.[NUM_FETAL]",
				"likely_to_proceed" => false,
				"create_cost_estimate" => true,
				"create_salesforce_case" => true,
				"additional_actions" => "",
				"additional_actions_message" => "Thank you! We have an estimate for [TEST]. Details below.",
				"legal_disclaimer" => "",
				"language_disclaimer" => "If you speak Spanish please contact a member from our team at 844.799.3243 (we would translate this to Spanish)",
				"cost_estimate_id_message" => "Your cost estimate number is <b>[COST_ESTIMATE_ID]</b>. Please have your cost estimator ID number available when you speak to one of our team members. ",
				"contains_cost_estimate" => false
                            ),
                            "data_to_evaluate" => array()
                        );

                        // Re‐apply multi‐fetal logic for fallback description_of_message
                        if($customerData["MultiFetalGestation"]){
                            $tempMessage = strip_tags($frontEndMessage['response']['description_of_message']);
                            $tempMessage = str_replace("[NUM_FETAL]", "; multi-fetal " . $customerData["NumberOfChildren"], $tempMessage);
                            $frontEndMessage['response']['description_of_message'] = $tempMessage;
                            $description_of_message = $frontEndMessage['response']['description_of_message'];
                        } else {
                            $tempMessage = strip_tags($frontEndMessage['response']['description_of_message']);
                            $tempMessage = str_replace("[NUM_FETAL]", "", $tempMessage);
                            $frontEndMessage['response']['description_of_message'] = $tempMessage;
                            $description_of_message = $frontEndMessage['response']['description_of_message'];
                        }

                        // Update $returnData so the UI sees the fallback
                        $returnData["FrontEndMessage"] = $frontEndMessage;
                    }
                }

                // ============ If either attempt succeeded, we'll have an Id =============
                if(isset($costEstimateResult["Id"]) && $costEstimateResult["Id"] != ""){
                    $returnData["CostEstimateSuccess"] = $costEstimateResult;
                    $returnData["CostEstimateNumber"]  = $costEstimateResult["CENumber"];
                    $salesforceRecordId = $costEstimateResult["Id"];
                    $callbackSelection  = $customerData["CallbackSelection"];

                    if($frontEndMessage["response"]["create_salesforce_case"] && $customerData['ConsentToContact']){
                        $costEstimateCaseResult = $salesforceController->createCostEstimateCaseRecord(
                            $salesforceRecordId,
                            $callbackSelection,
                            strip_tags($frontEndMessage['response']['response_for_front_end']),
                            $logger
                        );
                        $returnData["CostEstimateCase"] = $costEstimateCaseResult;
                        $logger->debug('CostEstimateCase', $returnData["CostEstimateCase"]);
                    } else {
                        $logger->debug('CostEstimateCase', array("status" => "could not create salesforce case"));
                    }
                }

                $logger->debug('FrontEndMessage', $returnData["FrontEndMessage"]);
            }
        }
        else if($customerData['action'] == 'updateSalesforceObject'){
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
?>
