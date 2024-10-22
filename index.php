<?php
include_once 'class/class.nurse.php';
include_once 'class/class.admin.php';
include_once 'class/class.logs.php';
include_once 'class/class.leave.php';
include 'config/config.php';

/*Parameter variables for the navbar*/
$page = (isset($_GET['page']) && $_GET['page'] != '') ? $_GET['page'] : '';
$subpage = (isset($_GET['subpage']) && $_GET['subpage'] != '') ? $_GET['subpage'] : '';
$id = (isset($_GET['id']) && $_GET['id'] != '') ? $_GET['id'] : '';

/*Declaring the objects (OOP Concept)*/
$nurse = new Nurse();
$admin = new Admin();
$log = new Log();
$leave = new Leave; 

/*Login Verifier (Deploys Login Check Method from another file)*/
if(!$admin->get_session()){
	header("location: login.php");
}
$admin_id = $admin->get_id_by_username($_SESSION['adm_username']);
$admin_user_login = $admin_id;
?>


<!DOCTYPE html>
<html>
<head>
    <title>CareShift</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script> 
    <script src="https://kit.fontawesome.com/e697cf1067.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="wrapper">
        <div class="sidebar">
            <h2>CareShift</h2>
            <ul>
                <li><a href="index.php?page=schedule" class="<?= $page == 'schedule' ? 'active' : '' ?>"><i class="fas fa-solid fa-clock"></i>Schedule</a></li>
                <li><a href="index.php?page=reports" class="<?= $page == 'reports' ? 'active' : '' ?>"><i class="fas fa-solid fa-file-waveform"></i>Reports</a></li>
                <li><a href="index.php?page=logs" class="<?= $page == 'logs' ? 'active' : '' ?>"><i class="fas fa-solid fa-receipt"></i>Logs</a></li>
                <li><a href="index.php?page=leave" class="<?= $page == 'leave' ? 'active' : '' ?>"><i class="fas fa-regular fa-paste"></i></i></i>Leave Applicants</a></li>
                <li><a href="index.php?page=nurses" class="<?= $page == 'nurses' ? 'active' : '' ?>"><i class="fas fa-solid fa-user-nurse"></i>Nurses</a></li>
                <li><a href="index.php?page=admins" class="<?= $page == 'admins' ? 'active' : '' ?>"><i class="fas fa-lock" aria-hidden="true"></i></i>Admins</a></li>
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
                    /*Displays Leave Page*/
                    case 'leave':
                        require_once 'leave-module/index.php';
                    break;
				    /*Displays Schedule Page*/
                    case 'schedule':
                        require_once 'schedule-module/index.php';
                    break;
				    /*Displays Nurse Page*/
                    case 'nurses':
                        require_once 'nurse-module/index.php';
                    break;
                    /*Displays Admins Page*/
                    case 'admins':
                        require_once 'admins-module/index.php';
                    break;
				    /*Displays Settings Page*/
                    case 'settings':
                        require_once 'settings-module/index.php';
                    break;
				    /*Displays Default Page (Homepage)*/
                    default:
                        require_once 'schedule-module/index.php';
                    break; 
                    }
                ?>
            </div>
        </div>
    </div>
    <script src="script/script.js"></script>
</body>
</html>