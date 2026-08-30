<?php
	header('Access-Control-Allow-Origin: *');  
	header('Content-Type: application/json');
	error_reporting(0);
    ini_set("error_log", "../../../logs/php_errors.log");
    
	class CHCLookUpController { 
		
		private $logger; 
		public $environment = 'qa';
		private $credentials = array(
		    'qa' => array(
			    'grant_type' => 'client_credentials',
		        'client_id' => 'gEmzNNGYu5ybwpShuzcqVlx87Hx4MLG9',
		        'client_secret' => '34pqT2zInJWfiQzw',
			    'auth_endpoint' => 'https://apis.changehealthcare.com/apip/auth/v2/token',
                'lookup_endpoint' => 'https://apis.changehealthcare.com/medicalnetwork/eligibility/v3'
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
        private function sortByIndex($a, $b) {
            return $a['_index'] > $b['_index'];
        }
        private function groupBy($arrayOfObjects, $field)
        {
            $keys = array();
            foreach ($arrayOfObjects as $item)
            {
                $keys[$item[$field]][] = $item;
            }
            return $keys;
        }
        public function GetEstimatedOOP($Remaining_Deductible, $Coinsurance_Percent, $Test_Price){
			
	    	$Estimated_Out_Of_Pocket = null;
			 
	    	//$Remaining_Dedutible = 0 :  if no remaining deductible - meaning, they do not have anything more to pay on their plan
	    	if($Remaining_Deductible == 0){

		    	if($Coinsurance_Percent == 0){

			    	$Estimated_Out_Of_Pocket = 0;
			    	
		    	}else if($Coinsurance_Percent > 0){

			    	//$Estimated_Out_Of_Pocket = .3 * 2483 = $744.9
			    	$Estimated_Out_Of_Pocket = $Coinsurance_Percent * $Test_Price;
		    	}	
		    	
	    	}else if($Remaining_Deductible > 0){
		    	//$Remaining_Dedutible >  0 : if there is a remaining deductible - meaning, they have more to pay before reaching their cut off of what they should pay.

		    	if($Coinsurance_Percent == 0){
					
			    	if($Remaining_Deductible > $Test_Price){
						$Estimated_Out_Of_Pocket = $Test_Price;
						
					}else if($Remaining_Deductible < $Test_Price){

						$Estimated_Out_Of_Pocket = $Remaining_Deductible;	

					}else{

						$Estimated_Out_Of_Pocket = $Test_Price;

					}
			    	
		    	}else if($Coinsurance_Percent > 0){

			    	if($Test_Price > $Remaining_Deductible){
						$Estimated_Out_Of_Pocket = $Remaining_Deductible + ($Coinsurance_Percent * ( $Test_Price - $Remaining_Deductible ) );
					} else if ($Test_Price <= $Remaining_Deductible){ 
						$Estimated_Out_Of_Pocket = $Test_Price;
					}

		    	}					
	    	}
	    	return $Estimated_Out_Of_Pocket;
    	}
        public function startDDCostEstimate($data, $retryDD = false){
            $data = json_decode($data);
            $debug = false;
            $allDeductibles = array(
                'Individual' => null,
                'Employee_Only' => null,
                'Employee_And_Spouse' => null,
                'Employee_And_Children' => null,
                'Family' => null,
                'NoCoverageLevel' => null
            );
            $extraDeductibles = array(
                'Individual' => null,
                'Employee_Only' => null,
                'Employee_And_Spouse' => null,
                'Employee_And_Children' => null,
                'Family' => null,
                'NoCoverageLevel' => null
            );
            $allPreferredDeductiblesUngrouped = array();
            $unpreferredDeductibleseUngrouped = array();
            $serviceTypesAccepted = array(
                "5","30", "73","CL","10","82","1"
            );//,'73','CL','10','82','1', '30'
            $benefitsInfo = $data->benefitsInformation;
            foreach ($benefitsInfo as $benefits){ 
                //Look for benefit entries with the code of "C" for deductible
                if($benefits->code == "C"){
                    $containsSearch = (count(array_intersect($benefits->serviceTypeCodes, $serviceTypesAccepted)) > 0);
                    if($containsSearch){
                        if ($benefits->timeQualifier == 'Remaining' && $benefits->inPlanNetworkIndicator != 'No'){
                            if($debug){
                                echo("Code: " . $benefits->code . "&#13;&#10;");
                                echo("timeQualifier: " . $benefits->timeQualifier . "&#13;&#10;");
                                echo("coverageLevel: " . $benefits->coverageLevel . "&#13;&#10;");
                                echo("in_plan_network: " . $benefits->in_plan_network . "&#13;&#10;");
                                echo("dd: " . $benefits->benefitAmount . "&#13;&#10;");
                                echo("&#13;&#10;&#13;&#10;");
                            }
                            // $deductible = array();
                            // $deductible['coverage_level'] = $benefits->coverageLevel;
                            // $deductible['in_plan_network'] = $benefits->inPlanNetworkIndicator;
                            // $deductible['service_type_codes'] = $benefits->serviceTypeCodes;
                            // $deductible['dd'] = $benefits->benefitAmount;
                            // $allDeductibles[$benefits->coverageLevel][] = $deductible;
                            if(isset($benefits->coverageLevel)){
                                if($benefits->coverageLevel == "Employee Only"){
                                    $allDeductibles["Employee_Only"][] = $benefits;
                                    array_push($allPreferredDeductiblesUngrouped, $benefits);
                                }else if($benefits->coverageLevel == "Employee And Spouse"){
                                    $allDeductibles["Employee_And_Spouse"][] = $benefits;
                                    array_push($allPreferredDeductiblesUngrouped, $benefits);
                                }else if($benefits->coverageLevel == "Employee And Children"){
                                    $allDeductibles["Employee_And_Children"][] = $benefits;
                                    array_push($allPreferredDeductiblesUngrouped, $benefits);
                                }else{
                                    $allDeductibles[$benefits->coverageLevel][] = $benefits;
                                    array_push($allPreferredDeductiblesUngrouped, $benefits);
                                }
                            }else{
                                $allDeductibles['NoCoverageLevel'][] = $benefits;
                                array_push($allPreferredDeductiblesUngrouped, $benefits);
                            }
                        }else if (($benefits->timeQualifier == 'Calendar Year' || $benefits->timeQualifier == 'Service Year') && $benefits->inPlanNetworkIndicator != 'No'){
                            if($benefits->coverageLevel == "Employee Only"){
                                $extraDeductibles["Employee_Only"][] = $benefits;
                                array_push($unpreferredDeductibleseUngrouped, $benefits);
                            }else if($benefits->coverageLevel == "Employee And Spouse"){
                                $extraDeductibles["Employee_And_Spouse"][] = $benefits;
                                array_push($unpreferredDeductibleseUngrouped, $benefits);
                            }else if($benefits->coverageLevel == "Employee And Children"){
                                $extraDeductibles["Employee_And_Children"][] = $benefits;
                                array_push($unpreferredDeductibleseUngrouped, $benefits);
                            }else{
                                $extraDeductibles[$benefits->coverageLevel][] = $benefits;
                                array_push($unpreferredDeductibleseUngrouped, $benefits);
                            }                        
                            // $extraDeductibles[$benefits->coverageLevel][] = $benefits;
                            // array_push($unpreferredDeductibleseUngrouped, $benefits);
                        }else if ($benefits->timeQualifier == 'Contract' && $benefits->inPlanNetworkIndicator == 'Yes'){
                            if($benefits->coverageLevel == "Employee Only"){
                                $extraDeductibles["Employee_Only"][] = $benefits;
                                array_push($unpreferredDeductibleseUngrouped, $benefits);
                            }else if($benefits->coverageLevel == "Employee And Spouse"){
                                $extraDeductibles["Employee_And_Spouse"][] = $benefits;
                                array_push($unpreferredDeductibleseUngrouped, $benefits);
                            }else if($benefits->coverageLevel == "Employee And Children"){
                                $extraDeductibles["Employee_And_Children"][] = $benefits;
                                array_push($unpreferredDeductibleseUngrouped, $benefits);
                            }else{
                                $extraDeductibles[$benefits->coverageLevel][] = $benefits;
                                array_push($unpreferredDeductibleseUngrouped, $benefits);
                            }                        
                            // $extraDeductibles[$benefits->coverageLevel][] = $benefits;
                            // array_push($unpreferredDeductibleseUngrouped, $benefits);
                        }
                    }else{
                        if($debug){
                            echo("not in service types &#13;&#10;");
                            print_r($benefits->serviceTypeCodes);
                            print_r($serviceTypesAccepted);
                            echo("&#13;&#10;&#13;&#10;");
                        }
                    }
                }else{
                    if($debug){
                        echo("is not a deductible &#13;&#10;");
                        print_r($benefits->code);
                        echo("&#13;&#10;&#13;&#10;");
                    }
                }
                //"timeQualifier": "Service Year", total deductible
            }
            foreach ($allDeductibles as $key => $value){
                if(empty($value) || $value == null){
                    unset($allDeductibles[$key]);
                }
            }
    
            //if there are no normal deductibles, transfer all the extras over to the "all" variable so we can process.
            if(empty($allDeductibles)){
                foreach ($extraDeductibles as $key => $value){
                    if(empty($value) || $value == null){
                        unset($extraDeductibles[$key]);
                    }
                }
                $allDeductibles = $extraDeductibles;
            }
            
            $preferredDeductibleResponsesArray = json_decode(json_encode($allPreferredDeductiblesUngrouped), true);
            $groupOfPreferredDeductibles = $this->groupBy($preferredDeductibleResponsesArray, 'coverageLevel');
    
            $unpreferredDeductibleResponsesArray = json_decode(json_encode($unpreferredDeductibleseUngrouped), true);
            $groupOfUnPreferredDeductibles = $this->groupBy($unpreferredDeductibleResponsesArray, 'coverageLevel');
    
            
    
            $allPreferredDeductibles = array(
                'Individual'             => $groupOfPreferredDeductibles['Individual'] ?? [],
                'Employee_Only'          => $groupOfPreferredDeductibles['Employee_Only'] ?? [],
                'Employee_And_Spouse'    => $groupOfPreferredDeductibles['Employee_And_Spouse'] ?? [],
                'Employee_And_Children'  => $groupOfPreferredDeductibles['Employee_And_Children'] ?? [],
                'Family'                 => $groupOfPreferredDeductibles['Family'] ?? [],
                'NoCoverageLevel'        => $groupOfPreferredDeductibles['NoCoverageLevel'] ?? [],
            );
    
            foreach ($allPreferredDeductibles as $deductible => &$quote)
            {
                $quote = $this->groupBy($quote, 'inPlanNetworkIndicator');
                
                foreach ($quote as $inPlanNetworkIndicator => &$groupedNodes)
                {
                    $index = 0;
                    foreach ($serviceTypesAccepted as $serviceType)
                    {
                        foreach($groupedNodes as $key => &$node)
                        {
                            if (in_array($serviceType, $node['serviceTypeCodes']))
                            {
                                
                                if (empty($node["_index"]) || $node["_index"] == null)
                                {
                                    if($node["_index"] == "" && $node["_index"] != "0"){
                                        $node["_index"] = $index++;
                                    }
                                }
                            }
                        }
                    }
                    //after setting indexes, loop through again and unset anything without an index.
                    foreach($groupedNodes as $key => &$node)
                    {
                        if ((empty($node["_index"]) || $node["_index"] == null) && $node["_index"] != "0")
                        {
                            //array_push($allUnPreferredInsurances, $groupedNodes[$key]);
                            unset($groupedNodes[$key]);
                        }
                    }
                    //after removing anything without an index, sort them by index
                    usort($groupedNodes, array( $this, 'sortByIndex' ));
                }
            }
            
            //unset any insurance without values
            foreach ($allPreferredDeductibles as $coverageLevel => $value){
                if(empty($value) || $value == null){
                    unset($allPreferredDeductibles[$coverageLevel]);
                }
            }
    
            $flattenedDeductibleArray = array();
            
            //perform the final flatten and grab anything with an index.
            foreach ($allPreferredDeductibles as $coverageLevelKey => $coverageLevelValue){
                if(!empty($coverageLevelValue) || $coverageLevelValue != null){
                    //echo("coverageLevelKey: $coverageLevelKey &#13;&#10;");
                    foreach ($coverageLevelValue as $networkLevelKey => $networkLevelValue){
    
                        if(!empty($networkLevelValue) || $networkLevelValue != null){
    
                            //echo("  networkLevelKey: $networkLevelKey &#13;&#10;");
                            foreach ($networkLevelValue as $providerInfoKey => $providerInfoValue){
                                //echo("      providerInfoValue &#13;&#10;&#13;&#10;");
                                array_push($flattenedDeductibleArray, $providerInfoValue);
                            }
                        }
    
                    }
    
                }
            }

            usort($flattenedDeductibleArray, function ($item1, $item2) {
                return $item2['inPlanNetworkIndicator'] <=> $item1['inPlanNetworkIndicator'];
            });
    
    
            $finalDeductibleNode = array();
			$finalDeductible = "None";
            //if we have more than one, we should grab the highest remaining co-insurance amount
            // lets say we have all 82 in the same "yes" "individual", grab the highest coinsurance.
            $firstDeductibleGroup = reset($flattenedDeductibleArray);
            
            if(empty($flattenedDeductibleArray) || $flattenedDeductibleArray == null){
                
                if(!empty($groupOfUnPreferredDeductibles) && $groupOfUnPreferredDeductibles != null){
    
    
                    $allUnPreferredDeductibles = array(
                        'Individual'             => $groupOfUnPreferredDeductibles['Individual'] ?? [],
                        'Employee_Only'          => $groupOfUnPreferredDeductibles['Employee_Only'] ?? [],
                        'Employee_And_Spouse'    => $groupOfUnPreferredDeductibles['Employee_And_Spouse'] ?? [],
                        'Employee_And_Children'  => $groupOfUnPreferredDeductibles['Employee_And_Children'] ?? [],
                        'Family'                 => $groupOfUnPreferredDeductibles['Family'] ?? [],
                        'NoCoverageLevel'        => $groupOfUnPreferredDeductibles['NoCoverageLevel'] ?? [],
                    );
    
                    
                    // ----- NOW FOR THE UNPreferred ------ 
                    foreach ($allUnPreferredDeductibles as $insurance => &$quote)
                    {
                        $quote = $this->groupBy($quote, 'inPlanNetworkIndicator');
                        
                        foreach ($quote as $inPlanNetworkIndicator => &$groupedNodes)
                        {
                            $index = 0;
                            foreach($groupedNodes as $key => &$node)
                            {
                                if (empty($node["_index"]) || $node["_index"] == null)
                                {
                                    if($node["_index"] == "" && $node["_index"] != "0"){
                                        $node["_index"] = $index++;
                                    }
                                }
                            }
                            //after removing anything without an index, sort them by index
                            usort($groupedNodes, array( $this, 'sortByIndex' ));
                        }
                    }
    
                    //unset any deductible without values
                    foreach ($allUnPreferredDeductibles as $coverageLevel => $value){
                        if(empty($value) || $value == null){
                            unset($allUnPreferredDeductibles[$coverageLevel]);
                        }
                    }
    
                    $flattenedUnPreferredDeductibleArray = array();
                    //perform the final flatten and grab anything with an index.
                    foreach ($allUnPreferredDeductibles as $coverageLevelKey => $coverageLevelValue){
                        if(!empty($coverageLevelValue) || $coverageLevelValue != null){
                            foreach ($coverageLevelValue as $networkLevelKey => $networkLevelValue){
    
                                if(!empty($networkLevelValue) || $networkLevelValue != null){
                                    foreach ($networkLevelValue as $providerInfoKey => $providerInfoValue){
                                        array_push($flattenedUnPreferredDeductibleArray, $providerInfoValue);
                                    }
                                }
    
                            }
    
                        }
                    }
    
                    $firstDeductibleGroup = reset($flattenedUnPreferredDeductibleArray);
                    if(count($flattenedUnPreferredDeductibleArray) > 1){
                        $first_benefit_amount = floatval($firstDeductibleGroup['benefitAmount']);
                        $finalDeductibleNode = $firstDeductibleGroup;
                        foreach($flattenedUnPreferredDeductibleArray as $key => $deductible) {
                            if($firstDeductibleGroup['serviceTypeCodes'] == $deductible['serviceTypeCodes']){
                                //if current percent is > than first_benefit, set that value with current percent.
                                if(floatval($deductible['benefitAmount']) > floatval($firstDeductibleGroup['benefitAmount'])){
                                    //echo("here 1 " . $finalCoInsuranceAmount."&#13;&#10;");
                                    $first_benefit_amount = $deductible['benefitAmount'];
                                    $finalDeductibleNode = $deductible;
                                }
                            }
                        }
                        $finalDeductible = $first_benefit_amount;
                    }else{
                        $finalDeductible = $firstDeductibleGroup['benefitAmount'];
                        $finalDeductibleNode = $firstDeductibleGroup;
                    }
                }else{
                    $finalDeductible = 0;
                    $finalDeductibleNode = array('None');
                }
            }else{
                if(count($flattenedDeductibleArray) > 1){
                    $first_benefit_amount = floatval($firstDeductibleGroup['benefitAmount']);
                    $finalDeductibleNode = $firstDeductibleGroup;
                    foreach($flattenedDeductibleArray as $key => $deductible) {
                        if($firstDeductibleGroup['serviceTypeCodes'] == $insurance['serviceTypeCodes']){
                            //if current percent is > than first_benefit, set that value with current percent.
                            if(floatval($deductible['benefitAmount']) > floatval($firstDeductibleGroup['benefitAmount'])){
                                //echo("here 1 " . $finalCoInsuranceAmount."&#13;&#10;");
                                $first_benefit_amount = $deductible['benefitAmount'];
                                $finalDeductibleNode = $deductible;
                            }else{
                                //echo("here 2 " . $finalCoInsuranceAmount."&#13;&#10;");
                            }
                        }
                    }
                    $finalDeductible = $first_benefit_amount;
                }else{
                    $finalDeductible = $firstDeductibleGroup['benefitAmount'];
                    $finalDeductibleNode = $firstDeductibleGroup;
                }
            }
    
            
            if($finalDeductibleNode[0] != "None"){

            }else{
                $finalDeductible = "retry";
            }
            
            return array(
	            "finalDeductibleNode" => $finalDeductibleNode,
	            "finalDeductible" =>$finalDeductible
            );
        }
        public function startCOCostEstimate($data = null){
            $data = json_decode($data);
            $debug = false;
             $serviceTypesAccepted = array(
                '5','30','73','CL','10','82','1'
            );
            $benefitsInfo = $data->benefitsInformation;
            $coInsuranceResponseUngrouped = array();
    
            $unpreferredCoInsuranceResponseUngrouped = array();
    
            foreach ($benefitsInfo as $benefits){ 
                //Look for benefit entries with the code of "A" for coinsurance
                if($benefits->code == "A"){
                    if(!empty($benefits->serviceTypeCodes)){
                        $containsSearch = (count(array_intersect($serviceTypesAccepted, $benefits->serviceTypeCodes)) > 0);
                    }else{
                        $containsSearch = false;
                    }                
                    if($containsSearch){
                        if ($benefits->inPlanNetworkIndicator != 'No'){
                            if($debug){
                                echo("Code: " . $benefits->code . "&#13;&#10;");
                                echo("coverageLevel: " . $benefits->coverageLevel . "&#13;&#10;");
                                echo("in_plan_network: " . $benefits->in_plan_network . "&#13;&#10;");
                                echo("percent: " . $benefits->benefitPercent . "&#13;&#10;");
                                echo("&#13;&#10;&#13;&#10;");
                            }
                            
                                //here is where I need to create a single layer array and this is my actual one to use.
                                array_push($coInsuranceResponseUngrouped, $benefits);
    
    
                        }
                    }else{
                        if ($benefits->inPlanNetworkIndicator != 'No'){
                            //if(isset($benefits->coverageLevel)){
                                array_push($unpreferredCoInsuranceResponseUngrouped, $benefits);
                            //}
                        }
                    }
                }else{
                    if($debug){
                        echo("is not a co-insurance &#13;&#10;");
                        print_r($benefits->code);
                        echo("&#13;&#10;&#13;&#10;");
                    }
                }
            }
            // print_r(json_encode($ungrouped));
            // exit;
            
    
            $coinsuranceResponsesArray = json_decode(json_encode($coInsuranceResponseUngrouped), true);
            $groupOfPreferredInsurances = $this->groupBy($coinsuranceResponsesArray, 'coverageLevel');
    
            $coinsuranceUnPreferredResponsesArray = json_decode(json_encode($unpreferredCoInsuranceResponseUngrouped), true);
            $groupOfUnPreferredInsurances = $this->groupBy($coinsuranceUnPreferredResponsesArray, 'coverageLevel');
    
            $allPreferredInsurances = array(
                'Individual'             => $groupOfPreferredInsurances['Individual'] ?? [],
                'Employee Only'          => $groupOfPreferredInsurances['Employee Only'] ?? [],
                'Employee and Spouse'    => $groupOfPreferredInsurances['Employee and Spouse'] ?? [],
                'Employee and Children'  => $groupOfPreferredInsurances['Employee and Children'] ?? [],
                'Family'                 => $groupOfPreferredInsurances['Family'] ?? [],
                'NoCoverageLevel'        => $groupOfPreferredInsurances[''] ?? [],
            );
    
            foreach ($allPreferredInsurances as $insurance => &$quote)
            {
                $quote = $this->groupBy($quote, 'inPlanNetworkIndicator');
                
                // gives me 2 groups: "Yes": NotApplicable
                $quote = array_reverse($quote);
                
                foreach ($quote as $inPlanNetworkIndicator => &$groupedNodes)
                {
                    //echo ("inPlanNetworkIndicator = ". $inPlanNetworkIndicator . "&#13;&#10;");
                    //$groupedNodes = (object) $groupedNodes; 
                    $index = 0;
                    foreach ($serviceTypesAccepted as $serviceType)
                    {
                        // here is my array inside "Yes", and reorder it
                        //echo ("     &#13;&#10;&#13;&#10;serviceChecked = ". json_encode($serviceType) . "&#13;&#10;");
                        foreach($groupedNodes as $key => &$node)
                        {
                            //echo ("         checking node: = ".json_encode($key)."&#13;&#10;");
                            if (in_array($serviceType, $node['serviceTypeCodes']))
                            {
                                //echo("      index was " . $index."&#13;&#10;");
                                //echo("      index set was " . $node["_index"]."&#13;&#10;&#13;&#10;&#13;&#10;");
                                
                                if (empty($node["_index"]) || $node["_index"] == null)
                                {
                                    //echo("      index is: ".$node["_index"]."&#13;&#10;");
                                    if($node["_index"] == "" && $node["_index"] != "0"){
                                        $node["_index"] = $index++;
                                        //echo("      im not empty though: ".$index."&#13;&#10;&#13;&#10;&#13;&#10;");
                                        //echo("      applied new index: ".$index."&#13;&#10;&#13;&#10;&#13;&#10;");
                                    }
                                    //remove this is we want to go by the longer list
                                    //$node["_index"] = $index++;
                                    //break;
                                }
                            }
                        }
                    }
                    //after setting indexes, loop through again and unset anything without an index.
                    foreach($groupedNodes as $key => &$node)
                    {
                        if ((empty($node["_index"]) || $node["_index"] == null) && $node["_index"] != "0")
                        {
                            //echo(json_encode($node['serviceTypeCodes'], JSON_PRETTY_PRINT));
                            //array_push($allUnPreferredInsurances, $groupedNodes[$key]);
                            unset($groupedNodes[$key]);
                        }
                    }
                    //after removing anything without an index, sort them by index
                    usort($groupedNodes, array( $this, 'sortByIndex' ));
                }
            }
            //unset any insurance without values
            foreach ($allPreferredInsurances as $coverageLevel => $value){
                if(empty($value) || $value == null){
                    unset($allPreferredInsurances[$coverageLevel]);
                }
            }
    
            $flattenedCoInsuranceArray = array();
            
            //perform the final flatten and grab anything with an index.
    
            //$allPreferredInsurances = array_reverse($allPreferredInsurances);
    
            $newArray = array();
            $newArray["Individual"]["Yes"] = $allPreferredInsurances["Individual"]["Yes"];
            $newArray["Individual"]["Not Applicable"] = $allPreferredInsurances["Individual"]["Not Applicable"];
            
            $newArray["NoCoverageLevel"]["Yes"] = $allPreferredInsurances["NoCoverageLevel"]["Yes"];
            $newArray["NoCoverageLevel"]["Not Applicable"] = $allPreferredInsurances["NoCoverageLevel"]["Not Applicable"];
            
            $newArray["Family"]["Yes"] = $allPreferredInsurances["Family"]["Yes"];
            $newArray["Family"]["Not Applicable"] = $allPreferredInsurances["Family"]["Not Applicable"];
    
            $newArray["Employee and Spouse"]["Yes"] = $allPreferredInsurances["Employee and Spouse"]["Yes"];
            $newArray["Employee and Spouse"]["Not Applicable"] = $allPreferredInsurances["Employee and Spouse"]["Not Applicable"];
    
            $newArray["Employee Only"]["Yes"] = $allPreferredInsurances["Employee Only"]["Yes"];
            $newArray["Employee Only"]["Not Applicable"] = $allPreferredInsurances["Employee Only"]["Not Applicable"];
    
            $newArray["Employee and Children"]["Yes"] = $allPreferredInsurances["Employee and Children"]["Yes"];
            $newArray["Employee and Children"]["Not Applicable"] = $allPreferredInsurances["Employee and Children"]["Not Applicable"];
    
    
            //TODO: need to figure something out here for coinsurance properties.
            
    
            foreach ($newArray as $coverageLevelKey => $coverageLevelValue){
                if(!empty($coverageLevelValue) || $coverageLevelValue != null){
                    //echo("coverageLevelKey: $coverageLevelKey &#13;&#10;");
                    foreach ($coverageLevelValue as $networkLevelKey => $networkLevelValue){
    
                        if(!empty($networkLevelValue) || $networkLevelValue != null){
    
                            //echo("  networkLevelKey: $networkLevelKey &#13;&#10;");
                            foreach ($networkLevelValue as $providerInfoKey => $providerInfoValue){
                                //echo("      providerInfoValue &#13;&#10;&#13;&#10;");
                                array_push($flattenedCoInsuranceArray, $providerInfoValue);
                            }
                        }
    
                    }
    
                }
            }
            // echo("</textarea></div>");
            // echo("</div>");
            
            usort($flattenedCoInsuranceArray, function ($item1, $item2) {
                return $item2['inPlanNetworkIndicator'] <=> $item1['inPlanNetworkIndicator'];
            });
            
            $finalCoInsuranceNode = array();
            $finalCoInsuranceAmount = "None";
            //if we have more than one, we should grab the highest remaining co-insurance amount
            // lets say we have all 82 in the same "yes" "individual", grab the highest coinsurance.
            $firstInsuranceGroup = reset($flattenedCoInsuranceArray);
            
            // ----- NOW FOR THE UNPreferred ------ 
            if(empty($flattenedCoInsuranceArray) || $flattenedCoInsuranceArray == null){
    
                if(!empty($groupOfUnPreferredInsurances) && $groupOfUnPreferredInsurances != null){               
    
                    $allUnPreferredInsurances = array(
                        'Individual'             => $groupOfUnPreferredInsurances['Individual'] ?? [],
                        'Employee Only'          => $groupOfUnPreferredInsurances['Employee Only'] ?? [],
                        'Employee and Spouse'    => $groupOfUnPreferredInsurances['Employee and Spouse'] ?? [],
                        'Employee and Children'  => $groupOfUnPreferredInsurances['Employee and Children'] ?? [],
                        'Family'                 => $groupOfUnPreferredInsurances['Family'] ?? [],
                        'NoCoverageLevel'        => $groupOfUnPreferredInsurances[''] ?? [],
                    );
                    
                    foreach ($allUnPreferredInsurances as $insurance => &$quote)
                    {
                        $quote = $this->groupBy($quote, 'inPlanNetworkIndicator');
    
                        // gives me 2 groups: "Yes": NotApplicable
                        $quote = array_reverse($quote);                    
                        foreach ($quote as $inPlanNetworkIndicator => &$groupedNodes)
                        {
                            $index = 0;
                            foreach($groupedNodes as $key => &$node)
                            {
                                if (empty($node["_index"]) || $node["_index"] == null)
                                {
                                    //echo("      index is: ".$node["_index"]."&#13;&#10;");
                                    if($node["_index"] == "" && $node["_index"] != "0"){
                                        $node["_index"] = $index++;
                                    }
                                }
                            }
                            //after removing anything without an index, sort them by index
                            usort($groupedNodes, array( $this, 'sortByIndex' ));                        
                        }
                    }
                    //unset any insurance without values
                    foreach ($allUnPreferredInsurances as $coverageLevel => $value){
                        if(empty($value) || $value == null){
                            unset($allUnPreferredInsurances[$coverageLevel]);
                        }
                    }
                    
    
                    $flattenedUnPreferredCoInsuranceArray = array();
                    //perform the final flatten and grab anything with an index.
                    foreach ($allUnPreferredInsurances as $coverageLevelKey => $coverageLevelValue){
                        if(!empty($coverageLevelValue) || $coverageLevelValue != null){
                            //echo("coverageLevelKey: $coverageLevelKey &#13;&#10;");
                            foreach ($coverageLevelValue as $networkLevelKey => $networkLevelValue){
    
                                if(!empty($networkLevelValue) || $networkLevelValue != null){
    
                                    //echo("  networkLevelKey: $networkLevelKey &#13;&#10;");
                                    foreach ($networkLevelValue as $providerInfoKey => $providerInfoValue){
                                        //echo("      providerInfoValue &#13;&#10;&#13;&#10;");
                                        array_push($flattenedUnPreferredCoInsuranceArray, $providerInfoValue);
                                    }
                                }
    
                            }
    
                        }
                    }
    
                    usort($flattenedUnPreferredCoInsuranceArray, function ($item1, $item2) {
                        return $item2['inPlanNetworkIndicator'] <=> $item1['inPlanNetworkIndicator'];
                    });
                    
                    $firstInsuranceGroup = reset($flattenedUnPreferredCoInsuranceArray);
    
                    //echo("count of array: " . count($flattenedUnPreferredCoInsuranceArray)."&#13;&#10;&#13;&#10;");
                    if(count($flattenedUnPreferredCoInsuranceArray) > 1){
                        $first_benefit_percent = floatval($firstInsuranceGroup['benefitPercent']);
                        $finalCoInsuranceNode = $firstInsuranceGroup;
                        foreach($flattenedUnPreferredCoInsuranceArray as $key => $insurance) {
                            //if($firstInsuranceGroup['serviceTypeCodes'] == $insurance['serviceTypeCodes']){
                                //if current percent is > than first_benefit, set that value with current percent.
                                //echo("insurance->benefitPercent: " . $insurance['benefitPercent']."&#13;&#10;");
                                //echo("firstInsuranceGroup->benefitPercent: " . $firstInsuranceGroup['benefitPercent']."&#13;&#10;&#13;&#10;");
                                if(floatval($insurance['benefitPercent']) > floatval($firstInsuranceGroup['benefitPercent'])){
                                    //echo("here 1 " . $finalCoInsuranceAmount."&#13;&#10;");
                                    $first_benefit_percent = $insurance['benefitPercent'];
                                    $finalCoInsuranceNode = $insurance;
                                }else{
                                    //echo("here 2 " . $finalCoInsuranceAmount."&#13;&#10;");
                                }
                            //}
                        }
                        $finalCoInsuranceAmount = $first_benefit_percent;
                    }else{
                        $finalCoInsuranceAmount = $firstInsuranceGroup['benefitPercent'];
                        $finalCoInsuranceNode = $firstInsuranceGroup;
                    }
                }else{
                    $finalCoInsuranceAmount = 0;
                    $finalCoInsuranceNode = array('None');
                }
            }else{
                if(count($flattenedCoInsuranceArray) > 1){
                    $first_benefit_percent = floatval($firstInsuranceGroup['benefitPercent']);
                    $finalCoInsuranceNode = $firstInsuranceGroup;
                    foreach($flattenedCoInsuranceArray as $key => $insurance) {
                        if($firstInsuranceGroup['serviceTypeCodes'] == $insurance['serviceTypeCodes']){
                            //if current percent is > than first_benefit, set that value with current percent.
                            //echo("insurance->benefitPercent: " . $insurance['benefitPercent']."&#13;&#10;");
                            //echo("firstInsuranceGroup->benefitPercent: " . $firstInsuranceGroup['benefitPercent']."&#13;&#10;&#13;&#10;");
                            if(floatval($insurance['benefitPercent']) > floatval($firstInsuranceGroup['benefitPercent'])){
                                //echo("here 1 " . $finalCoInsuranceAmount."&#13;&#10;");
                                $first_benefit_percent = $insurance['benefitPercent'];
                                $finalCoInsuranceNode = $insurance;
                            }else{
                                //echo("here 2 " . $finalCoInsuranceAmount."&#13;&#10;");
                            }
                        }
                    }
                    $finalCoInsuranceAmount = $first_benefit_percent;
                }else{
                    $finalCoInsuranceAmount = $firstInsuranceGroup['benefitPercent'];
                    $finalCoInsuranceNode = $firstInsuranceGroup;
                }
            }
            return $finalCoInsuranceAmount;
        }
        public function returnEstimate($data, $successfulEstimationForTesting, $testLive, $logger, $serviceTypeCodes){
            $auth_array = $this->getAuthenticationToken($this->environment, $logger);
            
            if($auth_array['http_code'] == 200){
                
                $credentials = $this->getCredentials();
                $instance_url = '';
                $access_token = $auth_array['results']['access_token'];
                $endpoint = $credentials[$this->environment]['lookup_endpoint'];
                $contentType = 'application/json';
                if($data['Member_Gender'] == "Female"){
                    $formattedGender = "F";
                }else{
                    $formattedGender = "M";
                }
                $formattedBirthday = date("Ymd", strtotime($data["Member_BirthDate"])); //19910702

                $formattedNow = date("Ymd"); //19910702
                
                // $serviceTypeCode = explode (",", $data['ServiceType']);
                // $stringForServiceCodes = '';
                // foreach($serviceTypeCode as $code){
                //     $stringForServiceCodes .= '"'.$code.'",';
                // }
                // $stringForServiceCodes = rtrim($stringForServiceCodes, ",");
                //print("these are the fields");
                $fields = '{
                    "controlNumber":"000000001",
                    "tradingPartnerServiceId": "'. $data['TradingPartnerId']['value'] .'", 
                    "ProviderType": "payer",
                    "provider":
                    {
                    "organizationName": "Sequenom, Inc.",
                    "npi": "1730481078"
                    },
                    "subscriber": {
                    "memberId": "'. $data['Member_Id'] .'",
                    "firstName": "'. $data['Member_FirstName'] .'",
                    "lastName": "'. $data['Member_LastName'] .'",
                    "gender": "'. $formattedGender .'",
                    "dateOfBirth": "'. $formattedBirthday .'"
                    },
                    "encounter": {
                    "beginningDateOfService": "'.$formattedNow.'",
                    "endDateOfService": "'.$formattedNow.'",
                    "serviceTypeCodes": [
                        '.$serviceTypeCodes.'
                    ]
                    }
                }';
                //print($fields);
                $chc_estimate = $this->getCurlResult($instance_url, $endpoint, $fields, true, $access_token, $contentType, 'POST', $logger);
                return array("success" => true, "chc_estimate" => $chc_estimate, "chc_payload" => $fields);
            }else{
                return "";
                //return array("success" => false, "message" => "We could not gain an access token...", "Error" => $auth_array);
            }
	    }
	    private function getAuthenticationToken($env, $logger){
		    //get initial token
		    $curl = curl_init();
		    $credentials = $this->getCredentials();

            curl_setopt_array($curl, array(CURLOPT_URL => $credentials['qa']['auth_endpoint'],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS =>'{
                    "client_id": "'.$credentials['qa']['client_id'].'",
                    "client_secret": "'.$credentials['qa']['client_secret'].'",
                    "grant_type": "client_credentials"
                }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json'
                ),
            ));
            $result = curl_exec($curl);
			$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);

            $json = json_decode($result, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                // JSON is valid
                $json = json_decode($result, true);
            }else{
                $json = $result;
            }
            $logger->info('Salesforce Get Curl: Success');
            return array("http_code" => $httpcode, "results" => $json);
	    }
	    private function getCurlResult($url, $endpoint, $fields, $needsAccessToken, $accessToken, $contenType, $method, $logger){
		    
		    // $postFields = '{
            //     "controlNumber":"000000001",
            //     "tradingPartnerServiceId": "AETNA",
            //     "ProviderType": "payer",
            //     "provider":
            //     {
            //       "organizationName": "Sequenom, Inc.",
            //       "npi": "1730481078"
            //     },
            //     "subscriber": {
            //       "memberId": "W237200861",
            //       "firstName": "Brooke",
            //       "lastName": "Martin",
            //       "gender": "F",
            //       "dateOfBirth": "19930728"
            //     },
            //     "encounter": {
            //       "beginningDateOfService": "20210102",
            //       "endDateOfService": "20210902",
            //       "serviceTypeCodes": [
            //         "30"
            //       ]
            //     }
            //   }';
            $credentials = $this->getCredentials();
            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => $credentials['qa']['lookup_endpoint'] ,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $fields,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Accept: application/json',
                'Authorization: Bearer '.$accessToken ),
            ));
			$result = curl_exec($curl);
			$err = curl_error($curl);
			$httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			
			//close connection
			curl_close($curl);
			if($httpcode == 200){ //success
				$json = json_decode($result, true);
				if (json_last_error() === JSON_ERROR_NONE) {
				    // JSON is valid
				    $json = json_decode($result, true);
				}else{
					$json = $result;
				}
				$logger->info('API Call Success: Phoenix Estimate Lookup');
				return array("http_code" => $httpcode, "results" => $json, "instance_url" => $url . $endpoint);
			}else{
				//$ch = json_decode($ch, true);
				$logger->info('API Call Failure: Phoenix Estimate Lookup - ' . $httpcode);
				return array("http_code" => $httpcode, "results" => null, "full_result" => $result, "instance_url" => $url . $endpoint);
			}
	    }
        public function isAverageRiskPartner($payerId, $field){
            try {
                include '../../db.php';
                $query = $dbh->query('SELECT * FROM trading_partners WHERE ' . $field . ' = "' . $payerId . '" LIMIT 1');
                $result = $query->fetch(PDO::FETCH_ASSOC);
                $dbh = null;                
                $today = date("Y-m-d H:i:s");
                $date = date("Y-m-d H:i:s", strtotime($result['average_risk_coverage']));
                
                if($result['average_risk_coverage'] != ''){
                    if($date < $today){
                        return array(
	                        "result" => true,
	                        "date" => $date,
	                        "query" => $query
                        );
                    }else{
                        return array(
	                        "result" => false,
	                        "date" => $date,
	                        "query" => $query
                        );
                    }
                }else{
                    return array(
	                        "result" => false,
	                        "date" => $date,
	                        "query" => $query
                        );
                }
                
            } catch (PDOException $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
        }
        public function getTestPrice($payerId, $bundle){
            //echo("payerId: " . $payerId);
            //echo("bundle: " . $bundle);
            try {
                
                include '../../db.php';
                $query = $dbh->query('select * from trading_partner_fees where chc_id = "' . $payerId . '" AND cpt_bundle = "' . $bundle . '" LIMIT 1');
                //echo('select * from trading_partner_fees where chc_id = "' . $payerId . '" AND cpt_bundle = "' . $bundle . '" LIMIT 1');
                $result = $query->fetch(PDO::FETCH_ASSOC);
                //print_r($result);
                $dbh = null;                
                if($result['price'] != ''){
                    return $result['price'];
                }else{
                    return 0;
                }
                
            } catch (PDOException $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
        }
	}
?>