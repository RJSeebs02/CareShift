<?php
include '../config/config.php';
include '../class/class.departments.php';
include '../class/class.logs.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';

switch($action){
    case 'new':
        create_new_department($con);
    break;
    case 'update':
        update_department();
    break;
    case 'delete':
        delete_department();
    break;
}

function create_new_department($con) {
    $department = new Departments();
    $log = new Log();

    $department_name = ucfirst($_POST['department_name']);
    $department_desc = ucfirst($_POST['department_desc']);
    $dept_type_id = ucfirst($_POST['dept_type_id']);

    $result = $department->new_department($department_name, $department_desc, $dept_type_id);

    if ($result) {
        $id = $department->get_id_by_department_name($department_name);

        $log_action = "Create New Department";
        $log_description = "Created Department: $department_name (Dept. ID: $id)";
        $adm_id = $_SESSION['adm_id'];

        $log->addLog($log_action, $log_description, $adm_id);

        header("location: ../index.php?page=departments");
    } else {
        echo "<script>alert('Error adding department.'); window.history.back();</script>";
    }
}

function update_department(){  
    $department = new Departments();
    $log = new Log();

    $id = $_POST['id'];
    $department_name = ucfirst($_POST['department_name']);
    $department_desc = ucfirst($_POST['department_desc']);
    $dept_type_id = ucfirst($_POST['dept_type_id']);

    $result = $department->update_department($id, $department_name, $department_desc, $dept_type_id);

    if ($result) {
        $log_action = "Update Department";
        $log_description = "Updated Details for $department_name (Dept. ID: $id)";
        $adm_id = $_SESSION['adm_id'];

        $log->addLog($log_action, $log_description, $adm_id, $id);
        header('location: ../index.php?page=departments&subpage=profile&id=' . $id);
    }
}

function delete_department() {
    if (isset($_POST['id'])) {
        $department = new Departments();
        $log = new Log();
        $id = $_POST['id'];

        $department_name = $department->get_department_name($id);

        $result = $department->delete_department($id);

        if ($result) {
            $log_action = "Delete Department";
            $log_description = "Deleted Department: $department_name (Dept. ID: $id)";
            $adm_id = $_SESSION['adm_id'];
            $log->addLog($log_action, $log_description, $adm_id, $id);

            header("location: ../index.php?page=departments&subpage=records");
        } else {
            echo "Error deleting department.";
        }
    } else {
        echo "Department not found.";
    }
}
?>
