<?php
include '../config/config.php';
include '../class/class.nurse.php';
include '../class/class.logs.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';

switch($action){
	case 'new':
        create_new_nurse($con);
	break;
    case 'update':
        update_nurse();
	break;
    case 'delete':
        delete_nurse();
    break;
    case 'fetch_by_department':
        fetch_nurses_by_department();
    break;
}

function create_new_nurse($con) {
    $nurse = new Nurse();
    $log = new Log();

    $first_name = ucfirst($_POST['first_name']);
    $middle_name = ucfirst($_POST['middle_name']);
    $last_name = ucfirst($_POST['last_name']);
    $email = $_POST['email'];
    $sex = $_POST['sex'];
    $contact_no = $_POST['contact_no'];
    $position = $_POST['position'];
    $department = $_POST['department'];

    $password = 123;

    $result = $nurse->new_nurse($password, $first_name, $middle_name, $last_name, $email, $sex, $contact_no, $position, $department);

    if ($result) {
        $id = $nurse->get_id_by_name($first_name);
        
        $log_action = "Created New Nurse";
        $log_description = "Created Nurse: $last_name, $first_name (Nurse ID: $id)";
        $adm_id = $_SESSION['adm_id'];

        $log->addLog($log_action, $log_description, $adm_id);

        header("location: process.schedule.php?action=add_nurse&nurse_id=" . $id);
    } else {
        echo "<script>alert('Error adding nurse.'); window.history.back();</script>";
    }
}

function update_nurse(){  
    $nurse = new Nurse();
    $log = new Log();

    $id = $_POST['id'];
    $first_name = ucfirst($_POST['first_name']);
    $middle_name = ucfirst($_POST['middle_name']);
    $last_name = ucfirst($_POST['last_name']);
    $email = $_POST['email'];
    $sex = $_POST['sex'];
    $contact_no = $_POST['contact_no'];
    $position = $_POST['position'];
    $department = $_POST['department'];

    $result = $nurse->update_nurse($id, $first_name, $middle_name, $last_name, $email, $sex, $contact_no, $position, $department);

    if($result){
        
        $log_action = "Updated Nurse";
        $log_description = "Updated Details for $last_name, $first_name (Nurse ID: $id)";
        $adm_id = $_SESSION['adm_id'];

        $log->addLog($log_action, $log_description, $adm_id, $id);
        header('location: ../index.php?page=nurses&subpage=profile&id=' . $id);
    }
}

function delete_nurse(){
    if (isset($_POST['id'])) {
        $nurse = new Nurse();
        $log = new Log();
        $id = $_POST['id'];

        $first_name = $nurse->get_fname($id);
        $last_name = $nurse->get_lname($id);

        $result = $nurse->delete_nurse($id);

        if ($result) {
            $log_action = "Deleted Nurse";
            $log_description = "Deleted Nurse: $last_name, $first_name (Nurse ID: $id)";
            $adm_id = $_SESSION['adm_id'];

            $log->addLog($log_action, $log_description, $adm_id, $id);
            header("location: ../index.php?page=nurses&subpage=records");
        } else {
            echo "Error deleting nurse.";
        }
    } else {
        echo "Nurse not found.";
    }
}

function fetch_nurses_by_department() {
    $nurse = new Nurse();
    $department_id = isset($_GET['department_id']) ? $_GET['department_id'] : 'all';
    
    try {
        if ($department_id === 'all') {
            // Get total and available nurse counts
            $totalNurses = $nurse->countTotalNurses();
            $availableNurses = $nurse->countAvailableNurses();  // This is assumed to exist
            echo json_encode(['total_nurses' => $totalNurses, 'available_nurses' => $availableNurses]);
        } else {
            // Get the department-specific counts
            $totalNurses = $nurse->countNursesByDepartment($department_id);
            $availableNurses = $nurse->countAvailableNursesByDepartment($department_id);  // This is assumed to exist
            echo json_encode(['total_nurses' => $totalNurses, 'available_nurses' => $availableNurses]);
        }
    } catch (Exception $e) {
        echo json_encode(['error' => 'Error fetching nurse data: ' . $e->getMessage()]);
    }
}

?>
