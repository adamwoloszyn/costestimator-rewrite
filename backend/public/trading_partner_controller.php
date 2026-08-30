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
	
	include '../db.php';
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
                "phoenix_payer_id" => "",
                "tp_name" => "I don't have insurance",
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
                "phoenix_payer_id" => "",
                "tp_name" => "I don't see my insurance",
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
                    "phoenix_payer_id" => isset($row['payer_name']) ? $row['payer_name'] : "",
                    "tp_name" => isset($row['display_name']) ? $row['display_name'] : "",
                    "phxPayerId" => isset($row['payer_name']) ? $row['payer_name'] : "",
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
            error_log($e->getMessage());
            http_response_code(500);
            die();
        }	

	} catch (Exception $e) {
		print_r($e);
	}
	
?>