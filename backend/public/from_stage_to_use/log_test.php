<?php
	ini_set('display_errors', '1');
	ini_set("log_errors", 1);
	ini_set("error_log", "../../../logs/php_errors.log");
	
	require '../vendor/autoload.php';
	
	$logger = new Katzgrau\KLogger\Logger('/var/www/vhosts/sites/stage/logs-application');
	
	$logger->info('---------------- ADAM TEST2 ----------------');
	$logger->debug('CostEstimateRecordResult', array("something" => "else"));
?>