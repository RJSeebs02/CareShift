<?php
include_once 'class/class.employee.php';
include_once 'class/class.admin.php';
include 'config/config.php';

/*Parameter variables for the navbar*/
$page = (isset($_GET['page']) && $_GET['page'] != '') ? $_GET['page'] : '';

/*Declaring the objects (OOP Concept)*/
$employee = new Employee();
$admin = new Admin();

/*Login Verifier (Deploys Login Check Method from another file)*/
if(!$admin->get_session()){
	header("location: login.php");
}
$admin_id = $admin->get_id($_SESSION['adm_username']);
$admin_user_login = $admin_id;
?>


<!DOCTYPE html>
<html>
<head>
    <title>CareShift</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://kit.fontawesome.com/e697cf1067.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="wrapper">
        <div class="sidebar">
            <h2>CareShift</h2>
            <ul>
                <li><a href="index.php" class="<?= $page == '' ? 'active' : '' ?>"><i class="fas fa-solid fa-chart-pie"></i>Dashboard</a></li>
                <li><a href="index.php?page=reports" class="<?= $page == 'reports' ? 'active' : '' ?>"><i class="fas fa-solid fa-file-waveform"></i>Reports</a></li>
                <li><a href="index.php?page=logs" class="<?= $page == 'logs' ? 'active' : '' ?>"><i class="fas fa-solid fa-receipt"></i>Logs</a></li>
                <li><a href="index.php?page=schedule" class="<?= $page == 'schedule' ? 'active' : '' ?>"><i class="fas fa-solid fa-clock"></i>Schedule</a></li>
                <li><a href="index.php?page=employees" class="<?= $page == 'employees' ? 'active' : '' ?>"><i class="fas fa-solid fa-user-nurse"></i>Employees</a></li>
                <li><a href="index.php?page=settings" class="<?= $page == 'settings' ? 'active' : '' ?>"><i class="fas fa-solid fa-gear"></i>Settings</a></li>
            </ul>
        </div>
        <div class="body">
            <div class="header">
                <h3>Hello <?php echo $admin->get_fname($admin_id).' '.$admin->get_lname($admin_id);?>!</h3>
                <a href="logout.php" class="right">Logout</a>
            </div>
            <div class="main_content">
                <?php
                    switch($page){
				    /*Displays Reports Page*/
                    case 'reports':
                        require_once 'reports-module/index.php';
                    break;
                    /*Displays Logs Page*/
                    case 'logs':
                        require_once 'logs-module/index.php';
                    break;
				    /*Displays Schedule Page*/
                    case 'schedule':
                        require_once 'schedule-module/index.php';
                    break;
				    /*Displays Employees Page*/
                    case 'employees':
                        require_once 'employees-module/index.php';
                    break;
				    /*Displays Settings Page*/
                    case 'settings':
                        require_once 'settings-module/index.php';
                    break;
				    /*Displays Default Page (Homepage)*/
                    default:
                        require_once 'dashboard-module/index.php';
                    break; 
                    }
                ?>
            </div>
        </div>
    </div>
</body>
</html>