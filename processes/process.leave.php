<?php
include '../config/config.php';
include '../class/class.leave.php';
include '../class/class.logs.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';

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

function create_new_leave() {
    $leave = new Leave();
    $nurse_id = $_POST['nurse_id'];
    $leave_start_date = $_POST['leave_start_date'];
    $leave_end_date = $_POST['leave_end_date'];
    $leave_type = $_POST['leave_type'];
    $leave_desc = $_POST['leave_desc'];

    $leave_status = 'Pending';

    $result = $leave->new_leave($nurse_id,$leave_start_date,$leave_end_date,$leave_type,$leave_desc,$leave_status);
    if($result){
        header("location: ../index.php?page=leave");
    }
}

function approve_leave($con) {
    $leave = new Leave();
    $log = new Log(); 

    $leave_id = $_POST['leave_id'];
    $leave_status = 'Approved';

    $nurse_id = $leave->get_leave_nurse_id($leave_id);
    if ($nurse_id) {
        $query = "SELECT nurse_fname, nurse_lname FROM nurse WHERE nurse_id = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param('i', $nurse_id);
        $stmt->execute();
        
        $nurse = $stmt->get_result()->fetch_assoc();

        $nurse_fname = $nurse['nurse_fname'];
        $nurse_lname = $nurse['nurse_lname'];
    } else {
        $nurse_fname = $nurse_lname = 'Unknown';
    }

    $result = $leave->approve_leave($leave_id, $leave_status);

    if ($result) {
        $log_action = "Approved Leave";
        $log_description = "Approved leave request for $nurse_lname, $nurse_fname (ID: $leave_id)"; 
        $adm_id = $_SESSION['adm_id'];

        $log->addLog($log_action, $log_description, $adm_id, $nurse_id);

        header('location: ../index.php?page=leave&subpage=profile&id=' . $leave_id);
    } else {
        echo "<script>alert('Error approving leave.'); window.history.back();</script>";
    }
}

function deny_leave($con) {
    $leave = new Leave();
    $log = new Log(); 

    $leave_id = $_POST['leave_id'];
    $leave_status = 'Denied';

    $nurse_id = $leave->get_leave_nurse_id($leave_id);
    if ($nurse_id) {
        $query = "SELECT nurse_fname, nurse_lname FROM nurse WHERE nurse_id = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param('i', $nurse_id);
        $stmt->execute();
        
        $nurse = $stmt->get_result()->fetch_assoc();

        $nurse_fname = $nurse['nurse_fname'];
        $nurse_lname = $nurse['nurse_lname'];
    } else {
        $nurse_fname = $nurse_lname = 'Unknown';
    }

    $result = $leave->deny_leave($leave_id, $leave_status);

    if ($result) {
        $log_action = "Denied Leave";
        $log_description = "Denied leave request for $nurse_lname, $nurse_fname (ID: $leave_id)"; 
        $adm_id = $_SESSION['adm_id'];

        $log->addLog($log_action, $log_description, $adm_id, $nurse_id);

        header('location: ../index.php?page=leave&subpage=profile&id=' . $leave_id);
    } else {
        echo "<script>alert('Error approving leave.'); window.history.back();</script>";
    }
}

?>