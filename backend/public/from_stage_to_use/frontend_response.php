<?php
	class FrontEndResponse { 
		
		//private $legalDisclaimer = "Please note that this is only an estimate and assumes your health plan will cover the testing and approve payment.  Some health plans require prior authorization or limit coverage based on a specific diagnosis provided by your physician. Additionally, your physician may request laboratory services that will trigger additional testing procedures based on certain clinical indications or your physician may determine it necessary to order additional testing. Your actual out-of-pocket expense may be higher than the amount provided even if this estimate indicates zero. Labcorp invoices patients based on the response received from their health plan.";
		
		private $responses = array(
			"1" => array(
				"response_for_front_end" => "Your estimated out-of-pocket is",
				"response_for_front_end_two" => "If you have any additional questions, please call us at 844.799.3243 so we can help. A member from our team will not be contacting you.</b>",
				"include_legal_disclaimer" => true,
				"description_of_message" => "Estimate is $0.00[NUM_FETAL]",
				"likely_to_proceed" => true,
				"create_cost_estimate" => true,
				"create_salesforce_case" => false,
				"additional_actions" => "",
				"additional_actions_message" => "Based on the information you provided, this is an estimate for [TEST]",
				"legal_disclaimer" => "<b>Please note that this is only an estimate and assumes your health plan will cover the testing and approve payment.</b>  Some health plans require prior authorization or limit coverage based on a specific diagnosis provided by your physician. Additionally, your physician may request laboratory services that will trigger additional testing procedures based on certain clinical indications or your physician may determine it necessary to order additional testing. Your actual out-of-pocket expense may be higher than the amount provided even if this estimate indicates zero. Labcorp invoices patients based on the response received from their health plan.",
				"language_disclaimer" => "If you speak Spanish please contact a member from our team at 844.799.3243 (we would translate this to Spanish)",
				"cost_estimate_id_message" => "Your cost estimate number is <b>[COST_ESTIMATE_ID]</b>. Please have your cost estimator ID number available when you speak to one of our team members. ",
				"contains_cost_estimate" => true
			),
			"2" => array("response_for_front_end" => "Your estimated out-of-pocket is",
				"response_for_front_end_two" => "If you have any additional questions, please call us at 844.799.3243 so we can help. A member from our team will not be contacting you.",
				"include_legal_disclaimer" => true,
				"description_of_message" => "Estimate is < $300[NUM_FETAL]",
				"likely_to_proceed" => true,
				"create_cost_estimate" => true,
				"create_salesforce_case" => false,
				"additional_actions" => "",
				"additional_actions_message" => "Based on the information you provided, this is an estimate for [TEST]",
				"legal_disclaimer" => "<b>Please note that this is only an estimate and assumes your health plan will cover the testing and approve payment.</b>  Some health plans require prior authorization or limit coverage based on a specific diagnosis provided by your physician. Additionally, your physician may request laboratory services that will trigger additional testing procedures based on certain clinical indications or your physician may determine it necessary to order additional testing. Your actual out-of-pocket expense may be higher than the amount provided even if this estimate indicates zero. Labcorp invoices patients based on the response received from their health plan.",
				"language_disclaimer" => "If you speak Spanish please contact a member from our team at 844.799.3243 (we would translate this to Spanish)",
				"cost_estimate_id_message" => "Your cost estimate number is <b>[COST_ESTIMATE_ID]</b>. Please have your cost estimator ID number available when you speak to one of our team members. ",
				"contains_cost_estimate" => true
			),
			"3" => array(
				"response_for_front_end" => "<p><b>Based on the information you provided, we would like to discuss the best options to fit your financial needs.</b></p>  <p>We will call or email you in 30 minutes or less or at your preferred contact time at your provided contact number/email address during our business hours.</p><p>Our business hours are M-F 8am-7pm EST. Outside of business hours we will call you the next business day based on your indicated preference.</p><p>If you would like to call one of our team members directly, contact us at 844.799.3243.</p>",
				"include_legal_disclaimer" => false,
				"description_of_message" => "Estimate is calculated but eligibility response did not capture in network[NUM_FETAL]",
				"likely_to_proceed" => false,
				"create_cost_estimate" => true,
				"create_salesforce_case" => true,
				"additional_actions" => "",
				"additional_actions_message" => "",
				"legal_disclaimer" => "",
				"language_disclaimer" => "If you speak Spanish please contact a member from our team at 844.799.3243 (we would translate this to Spanish)",
				"cost_estimate_id_message" => "Your cost estimate number is <b>[COST_ESTIMATE_ID]</b>. Please have your cost estimator ID number available when you speak to one of our team members. ",
				"contains_cost_estimate" => false
			),
			"4" => array(
				"response_for_front_end" => "<p><b>Based on the information you provided, we would like to discuss the best options to fit your financial needs.</b></p>  <p>We will call or email you in 30 minutes or less or at your preferred contact time at your provided contact number/email address during our business hours.</p><p>Our business hours are M-F 8am-7pm EST. Outside of business hours we will call you the next business day based on your indicated preference.</p><p>If you would like to call one of our team members directly, contact us at 844.799.3243.</p>",
				"include_legal_disclaimer" => false,
				"description_of_message" => "The OOP is > $300[NUM_FETAL]",
				"likely_to_proceed" => false,
				"create_cost_estimate" => true,
				"create_salesforce_case" => true,
				"additional_actions" => "",
				"additional_actions_message" => "Thank you! We have an estimate for [TEST]. Details below.",
				"legal_disclaimer" => "",
				"language_disclaimer" => "If you speak Spanish please contact a member from our team at 844.799.3243 (we would translate this to Spanish)",
				"cost_estimate_id_message" => "Your cost estimate number is <b>[COST_ESTIMATE_ID]</b>. Please have your cost estimator ID number available when you speak to one of our team members. ",
				"contains_cost_estimate" => true
			),
			"5" => array(
				"response_for_front_end" => "<p><b>Based on the information you provided, we would like to discuss the best options to fit your financial needs.</b></p>  <p>We will call or email you in 30 minutes or less or at your preferred contact time at your provided contact number/email address during our business hours.</p><p>Our business hours are M-F 8am-7pm EST. Outside of business hours we will call you the next business day based on your indicated preference.</p><p>If you would like to call one of our team members directly, contact us at 844.799.3243.</p>",
				"include_legal_disclaimer" => false,
				"description_of_message" => "Multiples or Average Risk (Health plan does not have average risk coverage)[NUM_FETAL]",
				"likely_to_proceed" => false,
				"create_cost_estimate" => true,
				"create_salesforce_case" => true,
				"additional_actions" => "Not_Covered_By_Medicial_Policy=NO",
				"additional_actions_message" => "Thank you! We have an estimate for [TEST]. Details below.",
				"legal_disclaimer" => "",
				"language_disclaimer" => "If you speak Spanish please contact a member from our team at 844.799.3243 (we would translate this to Spanish)",
				"cost_estimate_id_message" => "Your cost estimate number is <b>[COST_ESTIMATE_ID]</b>. Please have your cost estimator ID number available when you speak to one of our team members. ",
				"contains_cost_estimate" => false
			),
			"6" => array(
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
			"7" => array(
				"response_for_front_end" => "We would like to help you explore which of our many financial options might fit your needs. One of our experts will contact you in 30 minutes or less or at your preferred contact time during business hours, Monday-Friday 8am-7pm EST. If you would like to call one of our team members directly, contact us at 844.799.3243. There is no obligation implied by understanding your options.",
				"include_legal_disclaimer" => false,
				"description_of_message" => "Customer does not have insurance[NUM_FETAL]",
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
			"8" => array(
				"response_for_front_end" => "We have contracts with over 400 payers across the United States and we are here to help you. It would be most efficient if we spoke with you live. One of our experts will contact you in 30 minutes or less or at your preferred contact time, but if you would like to call one of our team members directly, contact us at 844.799.3243. Our business hours are M-F 8am-7pm EST.",
				"include_legal_disclaimer" => false,
				"description_of_message" => "Customer does not have insurance from our list[NUM_FETAL]",
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
			"9" => array(
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
			"10" => array(
				"response_for_front_end" => "<p><b>Based on the information you provided, we would like to discuss the best options to fit your financial needs.</b></p><p>We will call or email you in 30 minutes or less or at your preferred contact time at your provided contact number/email address during our business hours.</p><p>Our business hours are M-F 8am-7pm EST. Outside of business hours we will call you the next business day based on your indicated preference.</p><p>If you would like to call one of our team members directly, contact us at 844.799.3243.</p>",
				"include_legal_disclaimer" => false,
				"description_of_message" => "No Pokitdok estimate information[NUM_FETAL]",
				"likely_to_proceed" => false,
				"create_cost_estimate" => true,
				"create_salesforce_case" => true,
				"additional_actions" => "",
				"additional_actions_message" => "Thank you! We have an estimate for [TEST]. Details below.",
				"legal_disclaimer" => "",
				"language_disclaimer" => "If you speak Spanish please contact a member from our team at 844.799.3243 (we would translate this to Spanish)",
				"cost_estimate_id_message" => "Your cost estimate number is <b>[COST_ESTIMATE_ID]</b>. Please have your cost estimator ID number available when you speak to one of our team members. ",
				"contains_cost_estimate" => false
			)
		);
		
		public function __construct() {
			
	    }
		public function getDisclaimer()
	    {
	        return $this->legalDisclaimer;
	    }
		public function getFrontEndResponses(){
			return $this->responses;
		}
		
	    public function displayFrontEndMessage($dataToEvaluate){
			$responses = $this->getFrontEndResponses();
			$multiFetalLevelCovered = false;
			
			if (is_null($dataToEvaluate["OOP_Cost"])){
				$dataToEvaluate["error_text"] = "OOP Cost is null";
			}
			

			//need to figure out "does the insurance provider cover the # of children they are having".
			if($dataToEvaluate["number_of_children"] == "No"){
				$multiFetalLevelCovered = true;
			}else if($dataToEvaluate["number_of_children"] == "Twins"){
				if($dataToEvaluate["coverage_twins"] == true){
					$multiFetalLevelCovered = true;
				}
			}else if($dataToEvaluate["number_of_children"] == "Triplets"){
				if($dataToEvaluate["coverage_triplets"] == true){
					$multiFetalLevelCovered = true;
				}
			}else if($dataToEvaluate["number_of_children"] == "More than three"){
				if($dataToEvaluate["coverage_four_or_more"] == true){
					$multiFetalLevelCovered = true;
				}
			}
			
			if($dataToEvaluate["insurance_selection"] == "1"){//"I don't have insurance"
				
				//1. Insurance = 'I do not have insurance' - message 7
				return array("rule" => 1, "response_index" => 7, "response" => $responses[7], "data_to_evaluate" => $dataToEvaluate);
				
			}else if($dataToEvaluate["insurance_selection"] == "2"){//"I don't see my insurance"
				
				//3. Insurance = 'My insurance is not listed' - message 8
				return array("rule" => 3, "response_index" => 8, "response" => $responses[8], "data_to_evaluate" => $dataToEvaluate);
			
			}else if($dataToEvaluate["skipCHCAndPhoenix"] == "true"){
				
				return array("rule" => 8, "response_index" => 3, "response" => $responses[3], "data_to_evaluate" => $dataToEvaluate);
				
			}else if($dataToEvaluate["error_text"] == "Coinsurance not found in response from trading partner coverage. Cannot calculate Out of Pocket estimate."){
				
				//4. Error Text Returned = 'Coinsurance not found in response from trading partner coverage. Cannot calculate Out of Pocket estimate.' - message 6
				return array("rule" => 4, "response_index" => 6, "response" => $responses[6], "data_to_evaluate" => $dataToEvaluate);
				
			//}else if($service_status != "Active" || $dataToEvaluate["error_text"] == "No active coverage. Estimate cannot be calculated." || $dataToEvaluate["service_status"] == "No active coverage. Estimate cannot be calculated."){
			}else if($dataToEvaluate["service_status"] != "Active"){
				
				//5. Error Text Returned = '' - message 6
				return array("rule" => 5, "response_index" => 6, "response" => $responses[6], "service_status" => $dataToEvaluate["service_status"], "data_to_evaluate" => $dataToEvaluate);
				
			}else if($dataToEvaluate["error_text"] == "Error getting eligibility from Trading Partner."){
				
				//6. Error Text Returned = 'Error getting eligibility from Trading Partner.' - message 6
				return array("rule" => 6, "response_index" => 9, "response" => $responses[9], "data_to_evaluate" => $dataToEvaluate);
				
			}else if($dataToEvaluate["error_text"] != ""){
				
				//7. Any other error text returned - message 6
				return array("rule" => 7, "response_index" => 6, "response" => $responses[6], "data_to_evaluate" => $dataToEvaluate);
			}else if(($dataToEvaluate["test_category"] == "NIPT") && ($dataToEvaluate["pregnant_with_multiples"] == true) && !$multiFetalLevelCovered){
			
				//2. Test = NIPT && Multifetal = Yes && Multifetal level covered by payer = No
				//- message 5
				return array("rule" => 2, "response_index" => 5, "response" => $responses[5], "data_to_evaluate" => $dataToEvaluate);
					
			// OLD }else if ($dataToEvaluate["test_category"] == "NIPT" && $dataToEvaluate["OOP_Cost"] == 0 && ($dataToEvaluate["member_estimate_age_at_delivery"] >= 35 || $dataToEvaluate["high_risk"] == true || $dataToEvaluate["average_risk_partner"] == true)) {
			}else if ($multiFetalLevelCovered && $dataToEvaluate["test_category"] == "NIPT" && $dataToEvaluate["OOP_Cost"] === 0 && ($dataToEvaluate["member_estimate_age_at_delivery"] >= 35 || $dataToEvaluate["high_risk"] == true || $dataToEvaluate["average_risk_partner"]["result"] == true)) {
				/*
				9.  
					TEST = NIPT
					OOP  = 0
					MultiFetal covered by payer = Yes
					( Member_Estimate_Age_At_Delivery >= 35
					  OR
					  High_Risk = Yes
					  OR
					  Average_Risk_Partner = Yes
					 )
					 - message 1
				*/
				return array("rule" => 9, "response_index" => 1, "response" => $responses[1], "data_to_evaluate" => $dataToEvaluate);
				
			// OLD }else if ($dataToEvaluate["test_category"] == "NIPT" && $dataToEvaluate["OOP_Cost"] <= 300 && ($dataToEvaluate["member_estimate_age_at_delivery"] >= 35 || $dataToEvaluate["high_risk"] == true || $dataToEvaluate["average_risk_partner"] == true)) {
			}else if ($multiFetalLevelCovered && $dataToEvaluate["test_category"] == "NIPT" && $dataToEvaluate["OOP_Cost"] <= 300 && ($dataToEvaluate["member_estimate_age_at_delivery"] >= 35 || $dataToEvaluate["high_risk"] == true || $dataToEvaluate["average_risk_partner"]["result"] == true)) {
				
				/*
				10. 
					TEST = NIPT
					OOP <= $300
					MultiFetal covered by payer = Yes
					
					( Member_Estimate_Age_At_Delivery >= 35
					  OR
					  High_Risk = Yes
					  OR
					  Average_Risk_Partner = Yes
					 )
					 - message 2
				*/
				return array("rule" => 10, "response_index" => 2, "response" => $responses[2], "data_to_evaluate" => $dataToEvaluate);
				
			}else if ($dataToEvaluate["test_category"] == "Inheritest" && $dataToEvaluate["OOP_Cost"] == 0 && ($dataToEvaluate["high_risk"] == true)) {
			
				/*
				11. 
					TEST = Inheritest Screen
					OOP  =  0
					
					( High_Risk = Yes )
					- message 1
				*/
				return array("rule" => 11, "response_index" => 1, "response" => $responses[1], "data_to_evaluate" => $dataToEvaluate);
				
			}else if (($dataToEvaluate["test_name"] == "Ashkenazi Jewish Panel" || $dataToEvaluate["test_name"] == "CF/SMA Panel") && $dataToEvaluate["OOP_Cost"] == 0){
				/*
				12.
					TEST = AJP or CF/SMA
					OOP = 0
				*/
				return array("rule" => 12, "response_index" => 1, "response" => $responses[1], "data_to_evaluate" => $dataToEvaluate);
			}else if ($dataToEvaluate["test_category"] == "Inheritest" && $dataToEvaluate["OOP_Cost"] <= 300 && ($dataToEvaluate["high_risk"] == true)) {
				
				/*
				13.
					TEST = Inheritest
					OOP  =  <$300
					
					( High_Risk = Yes )
					- message 2
				*/
				return array("rule" => 13, "response_index" => 2, "response" => $responses[2], "data_to_evaluate" => $dataToEvaluate);
				
			}else if (($dataToEvaluate["test_name"] == "Ashkenazi Jewish Panel" || $dataToEvaluate["test_name"] == "CF/SMA Panel") && $dataToEvaluate["OOP_Cost"] <= 300){
				/*
				14.
					TEST = AJP or CF/SMA
					OOP < $300
				*/
				return array("rule" => 14, "response_index" => 2, "response" => $responses[2], "data_to_evaluate" => $dataToEvaluate);

			}else if ($dataToEvaluate["test_category"] == "NIPT" && $dataToEvaluate["OOP_Cost"] > 300 && ($dataToEvaluate["member_estimate_age_at_delivery"] >= 35 || $dataToEvaluate["high_risk"] == true || $dataToEvaluate["average_risk_partner"]["result"] == true)) {
				
				/*
				15.	
					TEST = NIPT
					OOP > $300
					( Member_Estimate_Age_At_Delivery >= 35
					  OR
					  High_Risk = Yes
					  OR
					  Average_Risk_Partner = Yes
					 )
					 - message 4
				*/
				return array("rule" => 15, "response_index" => 4, "response" => $responses[4], "data_to_evaluate" => $dataToEvaluate);
			
			}else if (($dataToEvaluate["test_category"] == "NIPT") && ($dataToEvaluate["average_risk_partner"]["result"] == false)) {
				
				/*
				16.
					TEST = NIPT
					Average_Risk_Partner = NO
					- message 5
				*/
				return array("rule" => 16, "response_index" => 5, "response" => $responses[5], "data_to_evaluate" => $dataToEvaluate);
				
			}else if ($dataToEvaluate["test_category"] == "Inheritest" && ($dataToEvaluate["test_name"] != "Ashkenazi Jewish Panel" && $dataToEvaluate["test_name"] != "CF/SMA Panel") && $dataToEvaluate["high_risk"] == false) {
			
				/*
				17.
					TEST - Inheritest
					High_Risk = No
					- message 5
				*/
				return array("rule" => 17, "response_index" => 5, "response" => $responses[5], "data_to_evaluate" => $dataToEvaluate);
			
			}else if ($dataToEvaluate["OOP_Cost"] > 300) {
				
				/*
				18.
					OOP > $300
					- message 4
				*/
				return array("rule" => 18, "response_index" => 4, "response" => $responses[4], "data_to_evaluate" => $dataToEvaluate);
				
			}else{
				
				/*	
					19. Everything else - message 9
				*/
				return array("rule" => 19, "response_index" => 9, "response" => $responses[9], "data_to_evaluate" => $dataToEvaluate);//, "data_used" => $dataToEvaluate
			}
		}
	    
	    
	}

?>