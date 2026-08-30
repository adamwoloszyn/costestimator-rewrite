<?php
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
	
	error_reporting(E_ALL & ~E_DEPRECATED);
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
        // Return empty array if table doesn't exist or other DB error
        echo json_encode([]);
    }
} catch (Exception $e) {
    // Return empty array for any other errors
    echo json_encode([]);
}
?>
