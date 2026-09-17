<?php
	header('Access-Control-Allow-Origin: *');   
	header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
	header('Access-Control-Allow-Headers: X-CSRF-Token, Accept, Content-Type');
	if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit(); }
	header('Content-Type: application/json');
	ini_set("error_log", "../../../logs/php_errors.log");
	include '../../db.php';
	class TradingPartnerController { 
		
		public function __construct() {
		
        }
	}
	try{
        try {
	        
            $partners = array();
            /*$partnerQuery = "select pp.phoenix_id as 'id', tp.id as 'tp_id', tp.phoenix_payer_id as 'payer_name', tp.display_name, tp.chc_partner_id, tp.coverage_twins, tp.coverage_triplets, tp.coverage_four_or_more, tp.average_risk_coverage
                from trading_partners tp 
                left join phoenix_payers pp on tp.display_name = pp.display_name
                where pp.id != ''
                ORDER BY pp.phoenix_id;";*/
            $partnerQuery = "select pp.phoenix_id as 'id', tp.id as 'tp_id', tp.phoenix_payer_id as 'payer_name', tp.display_name, tp.chc_partner_id, tp.coverage_twins, tp.coverage_triplets, tp.coverage_four_or_more, tp.average_risk_coverage
from trading_partners tp 
left join phoenix_payers pp on tp.display_name = pp.display_name
ORDER BY display_name;";
            
            //$partnerQuery = "SELECT * FROM trading_partners ORDER BY display_name;";
            //i don't have insurance
            $partners[] = array(
                "id" => "1",
                "tp_id" => "480",
                "phxPayerId" => "",
                "payerName" => "",
                "displayName" => "I don't have insurance",
                "tradingParterId" => "",
                "coverage_twins" => "",
                "coverage_triplets" => "",
                "coverage_four_or_more" => "",
                "average_risk_coverage" => ""
            );
            
            //i don't see my insurance
            $partners[] = array(
                "id" => "2",
                "tp_id" => "480",
                "phxPayerId" => "",
                "payerName" => "",
                "displayName" => "I don't see my insurance",
                "tradingParterId" => "",
                "coverage_twins" => "",
                "coverage_triplets" => "",
                "coverage_four_or_more" => "",
                "average_risk_coverage" => ""
            );
            $results = $dbh->query($partnerQuery);
            $partner_result = $results->fetchAll();
            
            $nextIndex = count($partner_result);
            
            foreach($dbh->query($partnerQuery) as $row) {
	            if($row["id"] == ""){
		            $row["id"] = $nextIndex;
		            $nextIndex++;
	            }
	            
                $partners[] = array(
                    "id" => $row["id"],
                    "tp_id" => $row["tp_id"],
                    "phxPayerId" => isset($row['phoenix_payer_id']) ? $row['phoenix_payer_id'] : "",
                    "payerName" => isset($row['payer_name']) ? $row['payer_name'] : "",
                    "displayName" => isset($row['display_name']) ? $row['display_name'] : "",
                    "tradingParterId" => isset($row['chc_partner_id']) ? $row['chc_partner_id'] : "",
                    "coverage_twins" => isset($row['coverage_twins']) ? $row['coverage_twins'] : "",
                    "coverage_triplets" => isset($row['coverage_triplets']) ? $row['coverage_triplets'] : "",
                    "coverage_four_or_more" => isset($row['coverage_four_or_more']) ? $row['coverage_four_or_more'] : "",
                    "average_risk_coverage" => isset($row['average_risk_coverage']) ? $row['average_risk_coverage'] : ""
                );
            }
            
            $dbh = null;
            //print_r($partners);
            print_r(json_encode($partners, JSON_INVALID_UTF8_IGNORE));
            //echo json_last_error_msg(); 
            
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }	

	} catch (Exception $e) {
		print_r($e);
	}
	
?>