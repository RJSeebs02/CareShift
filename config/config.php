<<<<<<< HEAD
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
=======
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
>>>>>>> fa57ef9cad725f4de6f3f7079a21b6419102a7c6
?>