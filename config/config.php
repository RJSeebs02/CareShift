<?php
date_default_timezone_set("Asia/Manila");
	session_start();

	define('DB_SERVER','localhost');
	define('DB_USERNAME','russgarde03_careshift');
	define('DB_PASSWORD','romlijgards03');
	define('DB_DATABASE','russgarde03_careshift');

	$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

	if (mysqli_connect_errno()) {
    	die("Failed to connect to MySQL: " . mysqli_connect_error());
}
?>