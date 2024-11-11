<?php
include '../config/config.php';
include '../class/class.leave.php';
include '../class/class.logs.php';

/* Parameters for switch case */
$action = isset($_GET['action']) ? $_GET['action'] : '';

/* Switch case for actions in the process */
switch ($action) {
    case 'new':
        create_new_leave();
        break;
    case 'approve':
        approve_leave($con);
        break;
    case 'deny':
        deny_leave($con);
        break;
}

/* Main Function Process for creating a new leave */
function create_new_leave() {
    $leave = new Leave();
    /* Receives the parameters passed from the creation page form */
    $nurse_id = $_POST['nurse_id'];
    $leave_start_date = $_POST['leave_start_date'];
    $leave_end_date = $_POST['leave_end_date'];
    $leave_type = $_POST['leave_type'];
    $leave_desc = $_POST['leave_desc'];

    $leave_status = 'Pending';

    /*Passes the parameters to the class function */
    $result = $leave->new_leave($nurse_id,$leave_start_date,$leave_end_date,$leave_type,$leave_desc,$leave_status);
    if($result){
        header("location: ../index.php?page=leave");
    }
}

/* Main Function Process for approving a leave */
function approve_leave($con) {
    $leave = new Leave();
    $log = new Log(); 

    $leave_id = $_POST['leave_id'];
    $leave_status = 'Approved';

    $result = $leave->approve_leave($leave_id, $leave_status);

    if ($result) {
        $log_action = "Approved Leave";
        $log_description = "Approved leave request with Leave ID: $leave_id"; 
        $adm_id = $_SESSION['adm_id'];

        // Call the addLog method; $con is not needed here
        $log->addLog($log_action, $log_description, $adm_id);

        header('location: ../index.php?page=leave&subpage=profile&id=' . $leave_id);
    } else {
        echo "<script>alert('Error approving leave.'); window.history.back();</script>";
    }
}

/* Main Function Process for denying a leave */
function deny_leave($con) {
    $leave = new Leave();
    $log = new Log(); 

    $leave_id = $_POST['leave_id'];
    $leave_status = 'Denied';

    $result = $leave->deny_leave($leave_id, $leave_status);

    if ($result) {
        $log_action = "Denied Leave";
        $log_description = "Denied leave request with Leave ID: $leave_id"; 
        $adm_id = $_SESSION['adm_id'];

        // Call the addLog method; $con is not needed here
        $log->addLog($log_action, $log_description, $adm_id);

        header('location: ../index.php?page=leave&subpage=profile&id=' . $leave_id);
    } else {
        echo "<script>alert('Error denying leave.'); window.history.back();</script>";
    }
}

?>