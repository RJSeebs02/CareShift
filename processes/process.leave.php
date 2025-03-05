<?php
include_once '../config/connect.php';
include_once '../class/class.leave.php';
include_once '../class/class.logs.php';
include_once '../class/class.notifications.php';

$conn = Database::getInstance();

$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'new':
        create_new_leave();
        break;
    case 'approve':
        approve_leave($conn);  // Pass $conn (PDO object) instead of $con
        break;
    case 'deny':
        deny_leave($conn);  // Pass $conn (PDO object) instead of $con
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

    $result = $leave->new_leave($nurse_id, $leave_start_date, $leave_end_date, $leave_type, $leave_desc, $leave_status);
    if ($result) {
        header("location: ../index.php?page=leave");
    }
}

function approve_leave($conn) {
    $leave = new Leave();
    $log = new Log();
    $notifications = new Notifications();

    $leave_id = $_POST['leave_id'];
    $leave_status = 'Approved';

    $nurse_id = $leave->get_leave_nurse_id($leave_id);
    if ($nurse_id) {
        $query = "SELECT nurse_fname, nurse_lname FROM nurse WHERE nurse_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bindValue(1, $nurse_id, PDO::PARAM_INT);
        $stmt->execute();
        
        $nurse = $stmt->fetch(PDO::FETCH_ASSOC);
        $nurse_fname = $nurse['nurse_fname'];
        $nurse_lname = $nurse['nurse_lname'];
    } else {
        $nurse_fname = $nurse_lname = 'Unknown';
    }

    $result = $leave->approve_leave($leave_id, $leave_status);

    $leave_start_date = $leave->get_leave_start_date($leave_id);
    $leave_end_date = $leave->get_leave_end_date($leave_id);
    $leave_date_filed = $leave->get_leave_date_filed($leave_id);
    
    // Add leave schedule after approval
    $result2 = $leave->add_leave_sched($nurse_id, $leave_start_date, $leave_end_date);

    if ($result) {
        // Add a log entry
        $log_action = "Approved Leave";
        $log_description = "Approved leave request for $nurse_lname, $nurse_fname (Nurse ID: $nurse_id)"; 
        $adm_id = $_SESSION['adm_id'];

        $log->addLog($log_action, $log_description, $adm_id, $nurse_id);

        // Create notification message
        $notif_type = "Leave";
		$notif_title = "Leave Approved";
		$notif_msg = "Your leave request from $leave_start_date to $leave_end_date filed on $leave_date_filed was approved.";

        // Add notification
        $notifications->addNotifications($notif_type, $notif_title, $notif_msg, $nurse_id);

        header('location: ../index.php?page=leave&subpage=profile&id=' . $leave_id);
    } else {
        echo "<script>alert('Error approving leave.'); window.history.back();</script>";
    }
}


function deny_leave($conn) {
    $leave = new Leave();
    $log = new Log();
	$notifications = new Notifications();

    $leave_id = $_POST['leave_id'];
    $leave_status = 'Denied';

    $nurse_id = $leave->get_leave_nurse_id($leave_id);
    if ($nurse_id) {
        $query = "SELECT nurse_fname, nurse_lname FROM nurse WHERE nurse_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bindValue(1, $nurse_id, PDO::PARAM_INT);  // Using PDO's bindValue
        $stmt->execute();
        
        $nurse = $stmt->fetch(PDO::FETCH_ASSOC);
        $nurse_fname = $nurse['nurse_fname'];
        $nurse_lname = $nurse['nurse_lname'];
    } else {
        $nurse_fname = $nurse_lname = 'Unknown';
    }

    $result = $leave->deny_leave($leave_id, $leave_status);
	
	$leave_start_date = $leave->get_leave_start_date($leave_id);
    $leave_end_date = $leave->get_leave_end_date($leave_id);
    $leave_date_filed = $leave->get_leave_date_filed($leave_id);

    if ($result) {
        $log_action = "Denied Leave";
        $log_description = "Denied leave request for $nurse_lname, $nurse_fname (Nurse ID: $nurse_id)"; 
        $adm_id = $_SESSION['adm_id'];

        $log->addLog($log_action, $log_description, $adm_id, $nurse_id);
		
		// Create notification message
        $notif_type = "Leave";
		$notif_title = "Leave Denied";
		$notif_msg = "Your leave request from $leave_start_date to $leave_end_date filed on $leave_date_filed was denied.";

        // Add notification
        $notifications->addNotifications($notif_type, $notif_title, $notif_msg, $nurse_id);

        header('location: ../index.php?page=leave&subpage=profile&id=' . $leave_id);
    } else {
        echo "<script>alert('Error denying leave.'); window.history.back();</script>";
    }
}


?>
