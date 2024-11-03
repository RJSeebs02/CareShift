<?php
/* Include Leave Class File */
include '../class/class.leave.php';

/* Parameters for switch case */
$action = isset($_GET['action']) ? $_GET['action'] : '';

/* Switch case for actions in the process */
switch ($action) {
    case 'new':
        create_new_leave();
        break;
    case 'approve':
        approve_leave();
        break;
    case 'deny':
        deny_leave();
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

/*Main Function Process for approving a leave */
function approve_leave(){  
    $leave = new Leave();
    /*Receives the parameters passed from the profile updating page form */
    $leave_id = $_POST['leave_id'];
    $leave_status = 'Approved';

    /*Passes the parameters to the class function */
    $result = $leave->approve_leave($leave_id,$leave_status);
    if($result){
        header('location: ../index.php?page=leave&subpage=profile&id=' . $leave_id);
    }
}

/*Main Function Process for denying a leave */
function deny_leave(){  
    $leave = new Leave();
    /*Receives the parameters passed from the profile updating page form */
    $leave_id = $_POST['leave_id'];
    $leave_status = 'Denied';

    /*Passes the parameters to the class function */
    $result = $leave->deny_leave($leave_id,$leave_status);
    if($result){
        header('location: ../index.php?page=leave&subpage=profile&id=' . $leave_id);
    }
}
?>
