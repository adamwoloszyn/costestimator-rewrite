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
	
	try {
	    try {
	        // Get today's date and time in the correct format
	        $currentDateTime = date('Y-m-d H:i:s');
	
	        // Define the query to select message, start, and end and filter by today's date and time
	        $messageQuery = "
	            SELECT message, start, end
	            FROM banners
	            WHERE :currentDateTime BETWEEN start AND end;
	        ";
	
	        // Prepare the statement
	        $stmt = $dbh->prepare($messageQuery);
	        $stmt->bindParam(':currentDateTime', $currentDateTime);
	        $stmt->execute();
	
	        // Fetch results
	        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	        // Close the database connection
	        $dbh = null;
	
	        // Output the result as JSON
	        echo json_encode($messages, JSON_INVALID_UTF8_IGNORE);
	
	    } catch (PDOException $e) {
	        print "Error!: " . $e->getMessage() . "<br/>";
	        die();
	    }
	} catch (Exception $e) {
	    print_r($e);
	}
?>
