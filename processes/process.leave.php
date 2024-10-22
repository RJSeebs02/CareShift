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
    $leave_type = $_POST['leave_type'];
    $start_date = $_POST['leave_start_date'];
    $end_date = $_POST['leave_end_date'];
    $description = $_POST['leave_desc'];
    $nurse_id = $_POST['nurse_id'];
    $admin_id = $_POST['adm_id'];

    $leave_status = 'Pending';

    try {
        $result = $leave->new_leave($leave_type, $start_date, $end_date, $description, $leave_status, $nurse_id, $admin_id);
        if ($result) {
            header("Location: ../index.php?page=leaves");
            exit();
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
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
