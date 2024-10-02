<?php
date_default_timezone_set("Asia/Manila");
	session_start();

	define('DB_SERVER','localhost');
	define('DB_USERNAME','root');
	define('DB_PASSWORD','');
	define('DB_DATABASE','db_careshift');

	$con = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

	if (mysqli_connect_errno()) {
    	die("Failed to connect to MySQL: " . mysqli_connect_error());
}
?>