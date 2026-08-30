<?php
	$host = "127.0.0.1";
    $user = "root";
    $pass = "root";
    $dbname = "costestimator_local";
    $port = "";

    $host = "127.0.0.1";
    $port = "3306"; // Default MySQL port
    $dbh = new PDO("mysql:host=$host;dbname=$dbname;port=$port", $user, $pass);
    
    // $socket = "/Applications/MAMP/tmp/mysql/mysql.sock";
    // $dbh = new PDO('mysql:unix_socket=' . $socket . ';dbname=' . $dbname . ';port=' . $port, $user, $pass);
?>