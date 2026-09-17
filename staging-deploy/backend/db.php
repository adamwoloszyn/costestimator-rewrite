<?php
	$host = "127.0.0.1";
    $user = "root";
    $pass = "root";
    $dbname = "costestimator_local";
    $port = "";

    $host = "127.0.0.1";
    $port = "3306"; // Default MySQL port
    $dbh = new PDO("mysql:host=$host;dbname=$dbname;port=$port", $user, $pass, [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"]);
    
    // $socket = "/Applications/MAMP/tmp/mysql/mysql.sock";
    // $dbh = new PDO('mysql:unix_socket=' . $socket . ';dbname=' . $dbname . ';port=' . $port, $user, $pass);
?>