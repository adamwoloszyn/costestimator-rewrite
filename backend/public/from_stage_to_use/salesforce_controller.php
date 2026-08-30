<?php 
	header('Access-Control-Allow-Origin: *');  
	header('Content-Type: application/json');
	ini_set("log_errors", 1);
	ini_set("error_log", "../../../logs/php_errors.log");
	
	class SalesforceController { 
		
		private $logger; 
		public $environment = 'qa';
	    private $action; 
	    private $id;
	    private $fields; 
	    private $credentials = array(
		    'dev' => array(
			    'grant_type' => 'password',
		        'client_id' => 'SALESFORCE_CLIENT_ID_PLACEHOLDER',
		        'client_secret' => 'SALESFORCE_CLIENT_SECRET_PLACEHOLDER',
		        'username' => 'SALESFORCE_USERNAME_PLACEHOLDER',
		        'password' => 'SALESFORCE_PASSWORD_PLACEHOLDER',
			    'endpoint' => 'https://test.salesforce.com/services/oauth2/token'
		    ),
		    'staging' => array(
			    'grant_type' => 'password',
		        'client_id' => 'SALESFORCE_CLIENT_ID_PLACEHOLDER',
		        'client_secret' => 'SALESFORCE_CLIENT_SECRET_PLACEHOLDER',
		        'username' => 'SALESFORCE_USERNAME_PLACEHOLDER',
		        //'password' => 'SALESFORCE_PASSWORD_PLACEHOLDER',
		        'password' => 'SALESFORCE_PASSWORD_PLACEHOLDER',//kh95RlDT4EA8fo3taqW0K42PmRp441l7YZwyM
			    'endpoint' => 'https://test.salesforce.com/services/oauth2/token'
		    ),
		    'qa' => array(
			    'grant_type' => 'password',
		        'client_id' => 'SALESFORCE_CLIENT_ID_PLACEHOLDER',
		        'client_secret' => 'SALESFORCE_CLIENT_SECRET_PLACEHOLDER',
		        'username' => 'SALESFORCE_USERNAME_PLACEHOLDER',
		        //'password' => 'SALESFORCE_PASSWORD_PLACEHOLDER',Drt2wOT5KXE2Pdv4lLaAZk6N
		        'password' =>   'kh95RlDT4EA89c7CIxikCKEwL7ILgE4Q9Z4q',
			    'endpoint' => 'https://test.salesforce.com/services/oauth2/token'
		    ),
		    'prod' => array(
			    'grant_type' => 'password',
		        'client_id' => 'SALESFORCE_CLIENT_ID_PLACEHOLDER',
		        'client_secret' => 'SALESFORCE_CLIENT_SECRET_PLACEHOLDER',
		        'username' => 'SALESFORCE_USERNAME_PLACEHOLDER',
		        'password' => 'SALESFORCE_PASSWORD_PLACEHOLDER',
			    'endpoint' => 'https://login.salesforce.com/services/oauth2/token'
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
	    public function setAction($action)
	    {
	        $this->action = $action;
	    }
	    public function getAction()
	    {
	        return $this->action;
	    }
	    public function setID($id)
	    {
	        $this->id = $id;
	    }
	    public function getID()
	    {
	        return $this->id;
	    }
	    public function setFields($fields)
	    {
	        $this->fields = fields;
	    }
	    public function getFields()
	    {
	        return $this->fields;
	    }
	    private function getCredentials(){
			return $this->credentials;
		}
	    
	    public function createCostEstimateRecord($data, $frontEndResponse, $logger) { 
	        $auth_array = $this->getAuthenticationToken($this->environment, $logger);
			if($auth_array['http_code'] == 200){
				$access_token = $auth_array['results']['access_token'];
				$instance_url = $auth_array['results']['instance_url'];
				$endpoint = '/services/apexrest/v2/CostEstimate/';
				$contentType = 'application/json';
				
				//pull this out when I fix date picker
				if($data["DueDate"] != ""){
					$formattedDueDate = substr($data["DueDate"], 0, 10);
					
				}else{
					$formattedDueDate = date("Y-m-d");
				}
				//print_r($data);
				
				//if we have the access token, let's create our cost estimate record
				$fields_pre_json = array(
					"ce" => 
						array(
							"SubmittedBy" => (isset($data["Member_Identifier"]) ? $data["Member_Identifier"] : ""),						    
							"Member_FirstName" => (isset($data["Member_FirstName"]) ? $data["Member_FirstName"] : ""),						    
							"Member_LastName" => (isset($data["Member_LastName"]) ? $data["Member_LastName"] : ""),						    
							"Member_Email" => (isset($data["Member_Email"]) ? $data["Member_Email"] : ""),						    
							"Member_PrimaryPhone" => (isset($data["Member_PrimaryPhone"]) ? $data["Member_PrimaryPhone"] : ""),						    
							"Member_PrimaryPhoneType" => (isset($data["Member_PrimaryPhoneType"]) ? $data["Member_PrimaryPhoneType"] : ""),						    
							//"Member_SecondaryPhone" => (isset($data["Member_SecondaryPhone"]) ? $data["Member_SecondaryPhone"] : ""),							    
							//"Member_SecondaryPhoneType" => (isset($data["Member_SecondaryPhoneType"]) ? $data["Member_SecondaryPhoneType"] : ""),					    
							"Member_MobilePhone" => (isset($data["Member_MobilePhone"]) ? $data["Member_MobilePhone"] : ""),							    
							"Member_BirthDate" => (isset($data["Member_BirthDate"]) ? date("Y-m-d", strtotime($data["Member_BirthDate"])) : null),					    
							"Member_Gender" => (isset($data["Member_Gender"]) ? $data["Member_Gender"] : ""),							    
							"Member_State" => (isset($data["Member_State"]) ? $data["Member_State"] : ""),							    
							"Member_AgeAtDelivery" => (isset($data["Member_AgeAtDelivery"]) ? $data["Member_AgeAtDelivery"] : null),							    
							"Member_Id" => (isset($data["Member_Id"]) ? $data["Member_Id"] : ""),							    
							"Provider_Name" => (isset($data["Provider_Name"]) ? $data["Provider_Name"] : ""),						    
							"CoverageActive" => (isset($data["CoverageActive"]) ? $data["CoverageActive"] : ""),								    
							"Coinsurance" => (isset($data["Coinsurance"]) ? $data["Coinsurance"] : ""),							    
							"DueDate" => ($formattedDueDate ? $formattedDueDate : ""),							    
							"MultiFetalGestation" => (isset($data["MultiFetalGestation"]) ? $data["MultiFetalGestation"] : ""),							    
							"DeductibleBenefitAmount" => (isset($data["DeductibleBenefitAmount"]) ? $data["DeductibleBenefitAmount"] : null),					    
							"PlanBeginDate" => (isset($data["PlanBeginDate"]) && $data["PlanBeginDate"] != '' ? $data["PlanBeginDate"] : null),							    
							"HighRisk" => (isset($data["HighRisk"]) ? $data["HighRisk"] : ""),							    
							"CostEstimate" => (isset($data["CostEstimate"]) ? $data["CostEstimate"] : null),						    
							"CostEstimateCurrency" => (isset($data["CostEstimateCurrency"]) ? $data["CostEstimateCurrency"] : ""),							    
							"TestName" => (isset($data["TestName"]) ? $data["TestName"] : ""),							    
							"ResponseForFrontEnd" => ($frontEndResponse ? $frontEndResponse : ""),	
							"PayerId" => (isset($data["PayerName"]) ? $data["PayerName"] : ""),	
							"TradingPartnerId" => (isset($data["TradingPartnerId"]) ? $data["TradingPartnerId"] : ""),
							//"TradingPartnerId" => (isset($data["TradingPartnerId"]["value"]) ? $data["TradingPartnerId"]["value"] : ""),
							//"PayerId" => (isset($data["TradingPartnerId"]["value"]) ? $data["TradingPartnerId"]["value"] : ""),							    
							"PayerName" => (isset($data["DisplayName"]) ? $data["DisplayName"] : ""),						    
							"GroupNumber" => (isset($data["GroupNumber"]) ? $data["GroupNumber"] : ""),							    
							"ServiceType" => (isset($data['ServiceType']['key']) ? $data['ServiceType']['key'] : ""),							    
							"ConsentToContact" => (isset($data["ConsentToContact"]) ? $data["ConsentToContact"] : ""),							    
							"ConsentToLeaveMessage" => (isset($data["ConsentToLeaveMessage"]) ? $data["ConsentToLeaveMessage"] : ""),							    
							//"GeneticCounselingReceived" => (isset($data["GeneticCounselingReceived"]) ? $data["GeneticCounselingReceived"] : ""),							    
							"MoveForwardAnswer" => (isset($data["MoveForwardAnswer"]) ? $data["MoveForwardAnswer"] : ""),							    
							"NotCoveredByMedicalPolicy" => (isset($data["NotCoveredByMedicalPolicy"]) ? $data["NotCoveredByMedicalPolicy"] : ""),				    
							"CostEstimationTool" => (isset($data["CostEstimationTool"]) ? $data["CostEstimationTool"] : ""),							    
							"EstimateId" => (isset($data["EstimateId"]) ? $data["EstimateId"] . "" : ""),							    
							"ActivityId" => (isset($data["CHC_ActivityID"]) ? $data["CHC_ActivityID"] : null)
						)
				);
				if(empty($fields_pre_json["ce"]["CostEstimate"]) && $data["CostEstimateCurrency"] == ""){
					unset($fields_pre_json["ce"]["CostEstimate"]);
				}
				$fields = json_encode(
					$fields_pre_json
				);
				$cost_estimate_record = $this->getCurlResult($instance_url, $endpoint, $fields, true, $access_token, $contentType, 'POST', $logger);
				//echo(json_encode($cost_estimate_record, JSON_PRETTY_PRINT));
                if($cost_estimate_record['http_code'] == 200){		
					if($cost_estimate_record['results']['Id'] != ''){
						$logger->info('API Call Success: Cost Estimate Creation');
						return array(
							"success" => true, 
							"message" => "Successfully added a Cost Estimate Salesforce Record.10", 
							"Id" => $cost_estimate_record['results']['Id'], 
							"DataUsed" => $fields, 
							"endpoint" => $instance_url . " - " . $endpoint, 
							"CENumber" => $cost_estimate_record['results']['Name']
						);
					}
				}else{
					$logger->debug('API Call Failure: Cost Estimate Creation', $cost_estimate_record);
					return array(
						"success" => false, 
						"message" => "We could not create the Cost Estimate Record...20", 
						"Error" => $cost_estimate_record, 
						"DataUsed" => $fields, 
						"frontend" => $frontEndResponse, 
						"endpoint" => $instance_url . " - " . $endpoint,
						"access_token" => $access_token
					);
				}
			}else{
				$logger->debug('API Call Failure: Cost Estimate Creation', $auth_array);
				return array(
					"success" => false, 
					"message" => "We could not gain an access token...", 
					"Error" => $auth_array
				);
			}
	    } 
	    public function updateCostEstimateRecord($data, $logger) { 
    $auth_array = $this->getAuthenticationToken($this->environment, $logger);
    if($auth_array['http_code'] == 200){
        $access_token = $auth_array['results']['access_token'];
        $instance_url = $auth_array['results']['instance_url'];
        $endpoint = '/services/apexrest/v2/CostEstimate/';
        $contentType = 'application/json';

        if($data["DueDate"] != ""){
            $formattedDueDate = substr($data["DueDate"], 0, 10);
        }else{
            $formattedDueDate = date("Y-m-d");
        }

        $toBoolOrNull = function($value, $multiFetalMode = false) {
            if (is_bool($value)) {
                return $value;
            }

            if ($value === null) {
                return null;
            }

            $normalized = strtolower(trim((string)$value));

            if ($normalized === '') {
                return null;
            }

            if (in_array($normalized, array('yes', 'true', '1'), true)) {
                return true;
            }

            if (in_array($normalized, array('no', 'false', '0'), true)) {
                return false;
            }

            if ($multiFetalMode && in_array($normalized, array('twins', 'triplets', 'more than three'), true)) {
                return true;
            }

            return null;
        };

        $consentToLeaveMessage = (isset($data["ConsentToLeaveMessage"]) && $data["ConsentToLeaveMessage"] == "Yes") ? true : false;
        $consentToContact = (isset($data["ConsentToContact"]) && $data["ConsentToContact"] == "Yes") ? true : false;

        $highRiskSource = null;
        if (isset($data["HighRisk"])) {
            $highRiskSource = $data["HighRisk"];
        } else if (isset($data["HighRisk_3"])) {
            $highRiskSource = ($data["HighRisk_3"] == "2") ? "Yes" : (($data["HighRisk_3"] == "1") ? "No" : $data["HighRisk_3"]);
        }

        $multiFetalSource = null;
        if (isset($data["MultiFetalGestation"])) {
            $multiFetalSource = $data["MultiFetalGestation"];
        } else if (isset($data["MultiFetalGestation_8"])) {
            $multiFetalSource = ($data["MultiFetalGestation_8"] == "1") ? "Yes" : (($data["MultiFetalGestation_8"] == "2") ? "No" : $data["MultiFetalGestation_8"]);
        }

        $highRisk = $toBoolOrNull($highRiskSource, false);
        if ($highRisk === null) {
            $highRisk = false;
        }

        $multiFetalGestation = $toBoolOrNull($multiFetalSource, true);
        if ($multiFetalGestation === null) {
            $multiFetalGestation = false;
        }

        $coverageActive = isset($data["CoverageActive"]) ? $toBoolOrNull($data["CoverageActive"], false) : null;
        if ($coverageActive === null) {
            $coverageActive = false;
        }

        $notCoveredByMedicalPolicy = isset($data["NotCoveredByMedicalPolicy"]) ? $toBoolOrNull($data["NotCoveredByMedicalPolicy"], false) : null;
        if ($notCoveredByMedicalPolicy === null) {
            $notCoveredByMedicalPolicy = false;
        }

        if(isset($data["MoveForwardAnswer"]) && $data["MoveForwardAnswer"] != ''){
            if (is_array($data["MoveForwardAnswer"])){
                $MoveForwardAnswer = implode(";", $data["MoveForwardAnswer"]);
            }else{
                $MoveForwardAnswer = $data["MoveForwardAnswer"];
            }
        }

        $fields = json_encode(
            array(
                "ce" => 
                    array(
                        "Member_FirstName" => (isset($data["Member_FirstName"]) ? $data["Member_FirstName"] : ""),
                        "Member_LastName" => (isset($data["Member_LastName"]) ? $data["Member_LastName"] : ""),
                        "Member_Email" => (isset($data["Member_Email"]) ? $data["Member_Email"] : ""),
                        "Member_PrimaryPhone" => (isset($data["Member_PrimaryPhone"]) ? $data["Member_PrimaryPhone"] : ""),
                        "Member_PrimaryPhoneType" => (isset($data["Member_PrimaryPhoneType"]) ? $data["Member_PrimaryPhoneType"] : ""),
/*
                        "Member_SecondaryPhone" => (isset($data["Member_SecondaryPhone"]) ? $data["Member_SecondaryPhone"] : ""),
                        "Member_SecondaryPhoneType" => (isset($data["Member_SecondaryPhoneType"]) ? $data["Member_SecondaryPhoneType"] : ""),
*/
                        "Member_MobilePhone" => (isset($data["Member_MobilePhone"]) ? $data["Member_MobilePhone"] : ""),
                        "Member_BirthDate" => (isset($data["Member_BirthDate"]) ? date("Y-m-d", strtotime($data["Member_BirthDate"])) : null),
                        "Member_Gender" => (isset($data["Member_Gender"]) ? $data["Member_Gender"] : ""),
                        "Member_State" => (isset($data["Member_State"]) ? $data["Member_State"] : ""),
                        "Member_AgeAtDelivery" => (isset($data["Member_AgeAtDelivery"]) ? $data["Member_AgeAtDelivery"] : null),
                        "Member_Id" => (isset($data["Member_Id"]) ? $data["Member_Id"] : ""),
                        "Provider_Name" => (isset($data["Provider_Name"]) ? $data["Provider_Name"] : ""),
                        "TradingPartnerId" => (isset($data["TradingPartnerId"]["value"]) ? $data["TradingPartnerId"]["value"] : ""),
                        "CoverageActive" => $coverageActive,
                        "Coinsurance" => (isset($data["Coinsurance"]) ? $data["Coinsurance"] : ""),
                        "DueDate" => ($formattedDueDate ? $formattedDueDate : ""),
                        "MultiFetalGestation" => $multiFetalGestation,
                        "DeductibleBenefitAmount" => (isset($data["DeductibleBenefitAmount"]) ? $data["DeductibleBenefitAmount"] : null),
                        "PlanBeginDate" => (isset($data["PlanBeginDate"]) ? $data["PlanBeginDate"] : null),
                        "HighRisk" => $highRisk,
                        "CostEstimate" => (isset($data["CostEstimate"]) ? $data["CostEstimate"] : null),
                        "CostEstimateCurrency" => (isset($data["CostEstimateCurrency"]) ? $data["CostEstimateCurrency"] : ""),
                        "TestName" => (isset($data["TestName"]) ? $data["TestName"] : ""),
                        "ResponseForFrontEnd" => (isset($data["description_of_message"]) ? $data["description_of_message"] : ""),
                        "PayerId" => (isset($data["TradingPartnerId"]["value"]) ? $data["TradingPartnerId"]["value"] : ""),
                        "PayerName" => (isset($data["DisplayName"]) ? $data["DisplayName"] : ""),
                        "GroupNumber" => (isset($data["GroupNumber"]) ? $data["GroupNumber"] : ""),
                        "ServiceType" => (isset($data['ServiceType']['key']) ? $data['ServiceType']['key'] : ""),
                        "ConsentToContact" => $consentToContact,
                        "ConsentToLeaveMessage" => $consentToLeaveMessage,
                        "MoveForwardAnswer" => (isset($data["MoveForwardAnswer"]) ? $MoveForwardAnswer : ""),
                        "NotCoveredByMedicalPolicy" => $notCoveredByMedicalPolicy,
                        "CostEstimationTool" => (isset($data["CostEstimationTool"]) ? $data["CostEstimationTool"] : ""),
                        "EstimateId" => (isset($data["EstimateId"]) ? $data["EstimateId"] . "" : ""),
                        "ActivityId" => (isset($data["CHC_ActivityID"]) ? $data["CHC_ActivityID"] : null),
                        "Id" => $data["salesforceId"]
                    )
            )
        );

        $cost_estimate_record = $this->getCurlResult($instance_url, $endpoint, $fields, true, $access_token, $contentType, 'PUT', $logger);

        if($cost_estimate_record['http_code'] == 200){
            if($cost_estimate_record['results']['Id'] != ''){
                $logger->info('API Call Success: Cost Estimate Update');
                return array("success" => true, "message" => "Successfully updated a Cost Estimate Record.", "Id" => $cost_estimate_record['results']['Id'], "DataUsed" => $data, "success" => true);
            }else{
                $logger->debug('API Call Failure: Cost Estimate Update', $cost_estimate_record);
                return array("success" => false, "message" => "We could not update the Cost Estimate Record...", "Error" => $cost_estimate_record, "DataUsed" => $data);
            }
        }else{
            $logger->debug('API Call Failure: Cost Estimate Update', $cost_estimate_record);
            return array("success" => false, "message" => "We could not update the Cost Estimate Record...", "Error" => $cost_estimate_record, "DataUsed" => $data);
        }
    }else{
        $logger->debug('API Call Failure: Cost Estimate Update', $auth_array);
        return array("success" => false, "message" => "We could not gain an access token...", "Error" => $auth_array);
    }
}
	    public function createCostEstimateCaseRecord($id, $callbackSelection, $frontEndResponse, $logger) { 
	        $auth_array = $this->getAuthenticationToken($this->environment, $logger);
			if($auth_array['http_code'] == 200){
				$access_token = $auth_array['results']['access_token'];
				$instance_url = $auth_array['results']['instance_url'];
				$endpoint = '/services/apexrest/v2/CostEstimate/Case/';
				$contentType = 'application/json';
				
				/* 	----- POSSIBLE VALUES -----
					
					AM | 8am – 10am EST
					AM | 10am – 12pm EST
					PM | 12pm - 2pm EST
					PM | 2pm – 4pm EST
					PM | 4pm-7pm EST
					ASAP | Call me now (I am at the physician’s office or lab)
	
				*/
				//if we have the access token, let's create our cost estimate case record
				$fields = json_encode(
					array(
						"cec" => 
							array(
								"CostEstimateId" => $id,
							    "Subject" => "Member requests callback",
							    "Description" => $frontEndResponse,
							    "CaseOrigin" => "Cost estimator", 
							    "Priority" => "Normal",
							    "PreferredContactTime" => $callbackSelection
							)
					)
				);
				$cost_estimate_case_record = $this->getCurlResult($instance_url, $endpoint, $fields,  true, $access_token, $contentType, 'POST', $logger);
				
				if($cost_estimate_case_record['http_code'] == 200){		
					if($cost_estimate_case_record['results'] != ''){
						$logger->info('API Call Success: Case Creation');
						return array("success" => true, "message" => "Successfully added a Cost Estimate Case Record.", "Id" => $cost_estimate_case_record['results']);
					}else{
						$logger->debug('API Call Failure: Case Creation', $cost_estimate_case_record);
						return array("success" => false, "message" => "We could not create the Cost Estimate Case Record.", "Error" => $cost_estimate_case_record, "Payload" => $fields);
					}
				}else{
					$logger->debug('API Call Failure: Case Creation', $cost_estimate_case_record);
					return array("success" => false, "message" => "We could not create the Cost Estimate Case Record.", "Error" => $cost_estimate_case_record, "Payload" => $fields);
				}
			}else{
				$logger->debug('API Call Failure: Case Creation', $auth_array);
				return array("success" => false, "message" => "We could not gain an access token...", "Error" => $auth_array);
			}
	    } 
	    
	    public function getSiteStatus($logger) { 
	        $auth_array = $this->getAuthenticationToken($this->environment, $logger);
			if($auth_array['http_code'] == 200){
				$access_token = $auth_array['results']['access_token'];
				$instance_url = $auth_array['results']['instance_url'];
				$endpoint = '/services/apexrest/v2/CostEstimate/Status/';
				$contentType = 'application/json';
				
				$site_status = $this->getCurlResult($instance_url, $endpoint, null,  true, $access_token, $contentType, 'GET', $logger);
				
				if($site_status['http_code'] == 200){		
					if($site_status['results'] != ''){
						$logger->info('API Call Success: Get Site Status');
						return array("success" => true, "message" => "Successfully received site status.", "status" => $site_status);
					}
				}else{
					$logger->debug('API Call Failure: Get Site Status', $site_status);
					return array("success" => false, "message" => "We could not get site status.", "Error" => $site_status);
				}
			}else{
				$logger->debug('API Call Failure: Get Site Status', $auth_array);
				return array("success" => false, "message" => "We could not gain an access token...", "Error" => $auth_array);
			}
	    } 
	    
	    private function getAuthenticationToken($env, $logger){
		    //get initial token
		    $credentials = $this->getCredentials();
		    $fields = array(
		        'grant_type' => $credentials[$env]['grant_type'],
		        'client_id' => $credentials[$env]['client_id'],
		        'client_secret' => $credentials[$env]['client_secret'],
		        'username' => $credentials[$env]['username'],
		        'password' => $credentials[$env]['password']
			);
		    $result = $this->getCurlResult($credentials[$env]['endpoint'], '', $fields, false, '', 'string', 'POST', $logger);
			    
			//we received a 200 result. so we should have an access token.
			if($result['http_code'] == 200){		
				if($result['results']['access_token'] != ''){
					return $result;
				}
			}
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
			    curl_setopt($ch, CURLOPT_POST, 1);
		    }else if($method == 'PUT'){
			    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
		    }else{
			    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		    }
		    curl_setopt($ch, CURLOPT_SSLVERSION, 6);
		    		
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);		
			$result = curl_exec($ch);
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
				$logger->info('Salesforce Get Curl: Success');
				return array("http_code" => $httpcode, "results" => $json, "url 1" => $url);
			}else{
				$logger->info('Salesforce Get Curl result: Call failed.');
				$json = json_decode($result, true);
				return array("http_code" => $httpcode, "results" => $json, "url 2" => $url);
			}
	    }
	} 
?> 