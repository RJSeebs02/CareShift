<<<<<<< HEAD
<?php
include_once 'class/class.nurse.php';
include_once 'class/class.admin.php';
include_once 'class/class.logs.php';
include_once 'class/class.leave.php';
include_once 'class/class.reports.php';
include_once 'class/class.rooms.php';
include_once 'class/class.departments.php';
include_once 'class/class.useraccess.php';
include_once 'class/class.status.php';
include_once 'class/class.dept_type.php';
include_once 'class/class.schedule.php';
include 'config/config.php';

/*Parameter variables for the navbar*/
$page = (isset($_GET['page']) && $_GET['page'] != '') ? $_GET['page'] : 'reports';
$subpage = (isset($_GET['subpage']) && $_GET['subpage'] != '') ? $_GET['subpage'] : '';
$id = (isset($_GET['id']) && $_GET['id'] != '') ? $_GET['id'] : '';

/*Declaring the objects (OOP Concept)*/
$nurse = new Nurse();
$admin = new Admin();
$log = new Log();
$leave = new Leave(); 
$report = new Report();
$rooms = new Rooms();
$departments = new Departments();
$useraccess = new UserAccess();
$status = new Status();
$dept_type = new Dept_Type();
$schedule = new Schedule();

if(!$admin->get_session()){
    header("location: login.php");
}

$admin_id = $admin->get_id_by_username($_SESSION['adm_username']);
$admin_user_login = $admin_id;

$useraccess_id = $admin->get_access_id($admin_id);

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
    <script src="https://unpkg.com/html5-qrcode/minified/html5-qrcode.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="icon" href="img/logo.png" />
</head>
<body>
    <div class="wrapper">
        <div class="sidebar">
            <img src="img/logo.png" alt="Logo Image" class="logo_image">
            <h2>CareShift</h2>
            <ul>
                <h3>Main</h3>
                <li><a href="index.php?page=reports" class="<?= $page == 'reports' ? 'active' : '' ?>"><i class="fas fa-solid fa-chart-line"></i>Reports</a></li>
                <li><a href="index.php?page=schedule" class="<?= $page == 'schedule' ? 'active' : '' ?>"><i class="fas fa-solid fa-clock"></i>Schedule</a></li>

                <?php if ($useraccess_id != 2 && $useraccess_id != 3): ?>
                <li><a href="index.php?page=logs" class="<?= $page == 'logs' ? 'active' : '' ?>"><i class="fas fa-solid fa-receipt"></i>Logs</a></li>
                <?php endif; ?>

                <li><a href="index.php?page=scan" class="<?= $page == 'scan' ? 'active' : '' ?>"><i class="fas fa-solid fa-qrcode"></i>Scan QR Nurses</a></li>

                <li><a href="index.php?page=leave" class="<?= $page == 'leave' ? 'active' : '' ?>"><i class="fas fa-regular fa-paste"></i>Leave Applicants</a></li>

                <h3>Users</h3>
                <li><a href="index.php?page=nurses" class="<?= $page == 'nurses' ? 'active' : '' ?>"><i class="fas fa-solid fa-user-nurse"></i>Nurses</a></li>

                <?php if ($useraccess_id != 2 && $useraccess_id != 3): ?>
                <li><a href="index.php?page=admins" class="<?= $page == 'admins' ? 'active' : '' ?>"><i class="fas fa-lock" aria-hidden="true"></i></i>Admins</a></li>
                <?php endif; ?>

                <h3>Master List</h3>
                <li><a href="index.php?page=rooms" class="<?= $page == 'rooms' ? 'active' : '' ?>"><i class="fas fa-solid fa-door-closed"></i>Rooms</a></li>
                <li><a href="index.php?page=departments" class="<?= $page == 'departments' ? 'active' : '' ?>"><i class="fas fa-solid fa-users-line"></i>Departments</a></li>

                <?php if ($useraccess_id != 2 && $useraccess_id != 3): ?>
                <h3>Settings</h3>
                <li><a href="index.php?page=useraccess" class="<?= $page == 'useraccess' ? 'active' : '' ?>"><i class="fas fa-solid fa-user-shield"></i>User Access</a></li>
                <?php endif; ?>

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
                    /*Displays Schedule Page*/
                    case 'schedule':
                        require_once 'schedule-module/index.php';
                    break;
                    /*Displays Logs Page*/
                    case 'logs':
                        require_once 'logs-module/index.php';
                    break;
                    /*Displays Scanning Page*/
                    case 'scan':
                        require_once 'scan-module/index.php';
                    break;
                    /*Displays Leave Page*/
                    case 'leave':
                        require_once 'leave-module/index.php';
                    break;
				    /*Displays Nurse Page*/
                    case 'nurses':
                        require_once 'nurse-module/index.php';
                    break;
                    /*Displays Admins Page*/
                    case 'admins':
                        require_once 'admins-module/index.php';
                    break;
				    /*Displays Rooms Page*/
                    case 'rooms':
                        require_once 'rooms-module/index.php';
                    break;
                    /*Displays Departments Page*/
                    case 'departments':
                        require_once 'departments-module/index.php';
                    break;
                    /*Displays User Access Page*/
                    case 'useraccess':
                        require_once 'useraccess-module/index.php';
                    break;
				    /*Displays Default Page (Homepage)*/
                    default:
                        require_once 'reports-module/index.php';
                    break; 
                    }
                ?>
                
            </div>
        </div>
    </div>
   
    <script src="script/script.js"></script>
</body>
=======
<?php
include_once 'class/class.nurse.php';
include_once 'class/class.admin.php';
include_once 'class/class.logs.php';
include_once 'class/class.leave.php';
include_once 'class/class.reports.php';
include_once 'class/class.rooms.php';
include_once 'class/class.departments.php';
include_once 'class/class.useraccess.php';
include_once 'class/class.status.php';
include_once 'class/class.dept_type.php';
include_once 'class/class.schedule.php';
include_once 'class/class.attendance.php';
include 'config/config.php';

/*Parameter variables for the navbar*/
$page = (isset($_GET['page']) && $_GET['page'] != '') ? $_GET['page'] : 'reports';
$subpage = (isset($_GET['subpage']) && $_GET['subpage'] != '') ? $_GET['subpage'] : '';
$id = (isset($_GET['id']) && $_GET['id'] != '') ? $_GET['id'] : '';

/*Declaring the objects (OOP Concept)*/
$nurse = new Nurse();
$admin = new Admin();
$log = new Log();
$leave = new Leave(); 
$report = new Report();
$rooms = new Rooms();
$departments = new Departments();
$useraccess = new UserAccess();
$status = new Status();
$dept_type = new Dept_Type();
$schedule = new Schedule();
$attendance = new Attendance();

if(!$admin->get_session()){
    header("location: login.php");
}

$admin_id = $admin->get_id_by_username($_SESSION['adm_username']);
$admin_user_login = $admin_id;

$useraccess_id = $admin->get_access_id($admin_id);

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
    <script src="https://unpkg.com/html5-qrcode/minified/html5-qrcode.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="icon" href="img/logo.png" />
</head>
<body>
    <div class="wrapper">
        <div class="sidebar">
            <img src="img/logo.png" alt="Logo Image" class="logo_image">
            <h2>CareShift</h2>
            <ul>
                <h3>Main</h3>
                <li><a href="index.php?page=reports" class="<?= $page == 'reports' ? 'active' : '' ?>"><i class="fas fa-solid fa-chart-line"></i>Reports</a></li>
                <li><a href="index.php?page=schedule" class="<?= $page == 'schedule' ? 'active' : '' ?>"><i class="fas fa-solid fa-clock"></i>Schedule</a></li>

                <?php if ($useraccess_id != 2 && $useraccess_id != 3): ?>
                <li><a href="index.php?page=logs" class="<?= $page == 'logs' ? 'active' : '' ?>"><i class="fas fa-solid fa-receipt"></i>Logs</a></li>
                <?php endif; ?>

                <li><a href="index.php?page=scan" class="<?= $page == 'scan' ? 'active' : '' ?>"><i class="fas fa-solid fa-qrcode"></i>Attendance</a></li>

                <li><a href="index.php?page=leave" class="<?= $page == 'leave' ? 'active' : '' ?>"><i class="fas fa-regular fa-paste"></i>Leave Applicants</a></li>

                <h3>Users</h3>
                <li><a href="index.php?page=nurses" class="<?= $page == 'nurses' ? 'active' : '' ?>"><i class="fas fa-solid fa-user-nurse"></i>Nurses</a></li>

                <?php if ($useraccess_id != 2 && $useraccess_id != 3): ?>
                <li><a href="index.php?page=admins" class="<?= $page == 'admins' ? 'active' : '' ?>"><i class="fas fa-lock" aria-hidden="true"></i></i>Admins</a></li>
                <?php endif; ?>

                <h3>Master List</h3>
           <!--     <li><a href="index.php?page=rooms" class="<?= $page == 'rooms' ? 'active' : '' ?>"><i class="fas fa-solid fa-door-closed"></i>Rooms</a></li> -->
                <li><a href="index.php?page=departments" class="<?= $page == 'departments' ? 'active' : '' ?>"><i class="fas fa-solid fa-users-line"></i>Departments</a></li>

                <?php if ($useraccess_id != 2 && $useraccess_id != 3): ?>
                <h3>Settings</h3>
                <li><a href="index.php?page=useraccess" class="<?= $page == 'useraccess' ? 'active' : '' ?>"><i class="fas fa-solid fa-user-shield"></i>User Access</a></li>
                <?php endif; ?>

            </ul>
		<div class="footer">
			<p>SYSTEM BY: ROMLIJGARDS</p>
		</div>
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
                    /*Displays Schedule Page*/
                    case 'schedule':
                        require_once 'schedule-module/index.php';
                    break;
                    /*Displays Logs Page*/
                    case 'logs':
                        require_once 'logs-module/index.php';
                    break;
                    /*Displays Scanning Page*/
                    case 'scan':
                        require_once 'scan-module/index.php';
                    break;
                    /*Displays Leave Page*/
                    case 'leave':
                        require_once 'leave-module/index.php';
                    break;
				    /*Displays Nurse Page*/
                    case 'nurses':
                        require_once 'nurse-module/index.php';
                    break;
                    /*Displays Admins Page*/
                    case 'admins':
                        require_once 'admins-module/index.php';
                    break;
				    /*Displays Rooms Page*/
                    case 'rooms':
                        require_once 'rooms-module/index.php';
                    break;
                    /*Displays Departments Page*/
                    case 'departments':
                        require_once 'departments-module/index.php';
                    break;
                    /*Displays User Access Page*/
                    case 'useraccess':
                        require_once 'useraccess-module/index.php';
                    break;
				    /*Displays Default Page (Homepage)*/
                    default:
                        require_once 'reports-module/index.php';
                    break; 
                    }
                ?>
                
            </div>
        </div>
    </div>

	<footer>
	
	</footer>

    <script src="script/script.js"></script>
</body>
>>>>>>> fa57ef9cad725f4de6f3f7079a21b6419102a7c6
</html>