<?php
/* Include class files */
include_once 'config/config.php';
include_once 'class/class.admin.php';

/* Define Object */
$admin = new Admin();

/* Check if the user session already exists */
if ($admin->get_session()) {
    header("location: index.php");
    exit();
}

/* Handle login form submission */
if (isset($_REQUEST['submit'])) {
    extract($_REQUEST);
    $login = $admin->check_login($username, $password);

    if ($login) {
        $adm_id = $admin->get_id_by_username($username);
        $_SESSION['adm_id'] = $adm_id;

        $useraccess_id = $admin->get_access_id($adm_id);
        $_SESSION['useraccess_id'] = $useraccess_id;

        if ($useraccess_id == 1) { /*Super Admin*/
            header("location: index.php");
        } elseif ($useraccess_id == 2) { /*Head Nurse*/
            header("location: index.php");
        } elseif ($useraccess_id == 3) { /*Scheduler*/
            header("location: index.php");
        } else {
            header("location: index.php"); 
        }
        exit();
    } else {
        ?>
        <div id='error_box'>
            <div id='error_notif'>Wrong username or password.</div>
        </div>
        <?php
    }
}
?>

<!--Display Login Page HTML-->
<!DOCTYPE html>
<html>
	<head>
		<title>CareShift</title>
		<link rel="stylesheet" href="css/styles.css?<?php echo time();?>">
	</head>
<body>
	<div id="login-wrapper">
		<div id="login-box">
			<div id="login-title">
				<h3>CareShift</h3>
				<h1>LOGIN</h1>
			</div>
			<div id="login-main">
				<form method="post">
					<div class="text_field">
						<input type="text" name="username" class="text" placeholder="Username" autocomplete="off" required>
					</div>
					<div class="text_field">
						<input type="password" name="password" class="text" placeholder="Password" autocomplete="off" required>
					</div>
						<input type="Submit" name="submit" id="loginbutton" value="Login">
				</form>
			</div>
		</div>
	</div>
</body>
</html>