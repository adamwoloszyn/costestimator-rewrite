<?php
	header('Access-Control-Allow-Origin: *');  
	header('Content-Type: application/json');
	ini_set("error_log", "../../../logs/php_errors.log");
	include '../../db.php';
	class TestsController { 
		
		public function __construct() {
		
        }
	}
	try{
        try {
	        
            
            $tests = array();
            $testQuery = "select * from tests where is_enabled = 0 order by ordernum asc";
            foreach($dbh->query($testQuery) as $row) {
                $tests[] = array(
                    "id" => $row["id"],
                    "displayName" => isset($row['display_name']) ? $row['display_name'] : "",
                    "test_description" => isset($row['test_description']) ? $row['test_description'] : "",
                    "is_enabled" => isset($row['is_enabled']) ? $row['is_enabled'] : ""
                );
            }
            
            $test_descriptions = array();
            $testDescriptionsQuery = "select * from tests where is_enabled = 1 order by ordernum asc";
            foreach($dbh->query($testDescriptionsQuery) as $row) {
                $test_descriptions[] = array(
                    "id" => $row["id"],
                    "displayName" => isset($row['display_name']) ? $row['display_name'] : "",
                    "test_description" => isset($row['test_description']) ? $row['test_description'] : "",
                    "is_enabled" => isset($row['is_enabled']) ? $row['is_enabled'] : ""
                );
            }
            $dbh = null;
            print_r(
            json_encode(
            	array(
	            	"tests" => $tests, 
	            	"test_descriptions" => $test_descriptions
				), JSON_INVALID_UTF8_IGNORE)
			);
            //echo json_last_error_msg(); 
            
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }	

	} catch (Exception $e) {
		print_r($e);
	}
	
?>