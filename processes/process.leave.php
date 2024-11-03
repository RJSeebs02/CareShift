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
    case 'update':
        update_leave_status();
        break;
    case 'delete':
        delete_leave();
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

function update_leave_status() {
    $leave = new Leave();

    $leave_id = $_POST['leave_id'];
    $status = $_POST['leave_status'];

    try {
        $result = $leave->update_leave_status($leave_id, $status);
        if ($result) {
            header("Location: ../index.php?page=leaves&subpage=details&id=" . $leave_id . "&message=Status updated successfully");
            exit();
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
