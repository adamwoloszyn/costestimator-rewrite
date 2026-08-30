<?php
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
	header('Access-Control-Allow-Headers: X-CSRF-Token, Accept, Content-Type');
	if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
		http_response_code(204);
		exit();
	}
	header('Content-Type: application/json');
	ini_set("error_log", "../../../logs/php_errors.log");
	include '../../db.php';
	class TestsController { 
		
		public function __construct() {
		
        }
	}
	try{
        try {
	        
            $test_categories = array();
            $testCategoryQuery = "SELECT * FROM test_categories order by ordernum DESC";
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
            $pregnancyTests = "select * from tests where is_enabled = 1 and test_category = 1 order by ordernum asc";
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
            $prePregnancyTests = "select * from tests where is_enabled = 1 and test_category = 2 order by ordernum asc";
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
            
            
            
            $dbh = null;
            //echo json_last_error_msg(); 
            print_r(
            json_encode(
            	array(
	            	"test_categories" => $test_categories
				), JSON_INVALID_UTF8_IGNORE)
			);
            
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }	

	} catch (Exception $e) {
		print_r($e);
	}
	
?>