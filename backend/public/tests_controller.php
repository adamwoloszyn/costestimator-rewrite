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
	class TestsController { 
		
		public function __construct() {
		
        }
	}
	try{
        try {
	        
            $test_categories = array();
            $testCategoryQuery = "SELECT * FROM pep5_test_categories order by ordernum DESC";
            foreach($dbh->query($testCategoryQuery) as $row) {
                $test_categories[$row["id"]] = array(
	                "id" => $row["id"],
	                "displayName" => isset($row['category_name']) ? $row['category_name'] : "",
	                "category_code" => isset($row['category_code']) ? $row['category_code'] : "",
	                "description" => isset($row['description']) ? $row['description'] : "",
	                "imageUrl" => isset($row['imageUrl']) ? $row['imageUrl'] : "",
	                "color" => isset($row['color']) ? $row['color'] : ""
                );
            }
            
            //get the pregnancy tests
            $pregnancyTests = "select * from pep5_tests where is_enabled = 1 and test_category = 1 order by ordernum asc";
            foreach($dbh->query($pregnancyTests) as $row) {
                 $test_categories[2]["tests"][] = array(
                    "id" => $row["id"],
                    "parentIndex" => isset($row['parent_index_phoenix']) ? $row['parent_index_phoenix'] : "",
                    "displayName" => isset($row['display_name']) ? $row['display_name'] : "",
                    "test_description" => isset($row['test_description']) ? $row['test_description'] : "",
                    "isActive" => isset($row['is_enabled']) ? $row['is_enabled'] : "",
                    "testCode" => isset($row['cpt_bundle']) ? $row['cpt_bundle'] : "",
                    "categoryName" => "NIPT",
	                "testName" => isset($row['test_name']) ? $row['test_name'] : "",
	                "headerDisplay" =>  isset($row['header_display']) ? $row['header_display'] : "",
	                "indexInDynamicData" =>  isset($row['index_in_phoenix']) ? $row['index_in_phoenix'] : ""
                );
            }
            
            //get the pre-pregnancy tests
            $prePregnancyTests = "select * from pep5_tests where is_enabled = 1 and test_category = 2 order by ordernum asc";
            foreach($dbh->query($prePregnancyTests) as $row) {
                 $test_categories[1]["tests"][] = array(
                    "id" => $row["id"],
                    "parentIndex" => isset($row['parent_index_phoenix']) ? $row['parent_index_phoenix'] : "",
                    "displayName" => isset($row['display_name']) ? $row['display_name'] : "",
                    "test_description" => isset($row['test_description']) ? $row['test_description'] : "",
                    "isActive" => isset($row['is_enabled']) ? $row['is_enabled'] : "",
                    "testCode" => isset($row['cpt_bundle']) ? $row['cpt_bundle'] : "",
                    "categoryName" => "Inheritest",
	                "testName" => isset($row['test_name']) ? $row['test_name'] : "",
	                "headerDisplay" =>  isset($row['header_display']) ? $row['header_display'] : "",
	                "indexInDynamicData" =>  isset($row['index_in_phoenix']) ? $row['index_in_phoenix'] : ""
                );
                
                //add the pre-pregnancy tests to the pregnancy list for carrier screening area
                $test_categories[2]["tests"][] = array(
                    "id" => $row["id"],
                    "parentIndex" => isset($row['parent_index_phoenix']) ? $row['parent_index_phoenix'] : "",
                    "displayName" => isset($row['display_name']) ? $row['display_name'] : "",
                    "test_description" => isset($row['test_description']) ? $row['test_description'] : "",
                    "isActive" => isset($row['is_enabled']) ? $row['is_enabled'] : "",
                    "testCode" => isset($row['cpt_bundle']) ? $row['cpt_bundle'] : "",
                    "categoryName" => "Inheritest",
	                "testName" => isset($row['test_name']) ? $row['test_name'] : "",
	                "headerDisplay" =>  isset($row['header_display']) ? $row['header_display'] : "",
	                "indexInDynamicData" =>  isset($row['index_in_phoenix']) ? $row['index_in_phoenix'] : ""
                );
            }
            
            //get the Prenatal Diagnostics and Chromosome Analysis tests (PEP-016 through PEP-020)
            //Fetal RhD (test_category=1) already flows into $test_categories[2]["tests"] above, tagged "NIPT"
            //These tests fold into the same Pregnancy bucket, tagged "PrenatalDx" so TestList.jsx can render
            //them as their own section between NIPT tests and Carrier Screening (per PEP-016 item 1)
            $prenatalDxTests = "select * from pep5_tests where is_enabled = 1 and test_category = 3 order by ordernum asc";
            foreach($dbh->query($prenatalDxTests) as $row) {
                 $test_categories[2]["tests"][] = array(
                    "id" => $row["id"],
                    "parentIndex" => isset($row['parent_index_phoenix']) ? $row['parent_index_phoenix'] : "",
                    "displayName" => isset($row['display_name']) ? $row['display_name'] : "",
                    "test_description" => isset($row['test_description']) ? $row['test_description'] : "",
                    "isActive" => isset($row['is_enabled']) ? $row['is_enabled'] : "",
                    "testCode" => isset($row['cpt_bundle']) ? $row['cpt_bundle'] : "",
                    "categoryName" => "PrenatalDx",
	                "testName" => isset($row['test_name']) ? $row['test_name'] : "",
	                "headerDisplay" =>  isset($row['header_display']) ? $row['header_display'] : "",
	                "indexInDynamicData" =>  isset($row['index_in_phoenix']) ? $row['index_in_phoenix'] : ""
                );
            }
            
            
            $dbh = null;
            //echo json_last_error_msg(); 
            print_r(
            json_encode(
            	array(
	            	"test_categories" => $test_categories
				), JSON_INVALID_UTF8_IGNORE)
			);
            
        } catch (PDOException $e) {
            error_log($e->getMessage());
            http_response_code(500);
            die();
        }	

	} catch (Exception $e) {
		print_r($e);
	}
	
?>