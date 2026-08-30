<?php
	session_start();
	$_SESSION['test session'] = "hello from adam";
	echo("I set the session: " . $_SESSION['test session']);
?>