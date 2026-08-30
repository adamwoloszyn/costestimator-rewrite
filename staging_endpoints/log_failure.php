<?php
	header('Access-Control-Allow-Origin: *');  
	header('Content-Type: application/json');	
	$customerData = (array) json_decode(file_get_contents('php://input'), TRUE);
	session_id($customerData['user']["id"]);
	session_start();
	
	print_r(json_encode($$customerData));
?>