<?php
include '../config/config.php';
include '../class/class.departments.php';

/*Parameters for switch case*/
$action = isset($_GET['action']) ? $_GET['action'] : '';

/*Switch case for actions in the process */
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

/*Main Function Process for creating a department */
function create_new_department($con) {
    $department = new Departments();
    /* Receives the parameters passed from the creation page form */
    $department_name = ucfirst($_POST['department_name']);
    $department_desc = ucfirst($_POST['department_desc']);
    $dept_type_id = ucfirst($_POST['dept_type_id']);

    /* Passes the parameters to the class function */
    $result = $department->new_department($department_name, $department_desc, $dept_type_id);
    
    if ($result) {
        $id = $department->get_id_by_department_name($department_name);

        header("location: ../index.php?page=departments");
    } else {
        echo "<script>alert('Error adding department.'); window.history.back();</script>";
    }
}

/*Main Function Process for updating an admin */
function update_department(){  
    $department = new Departments();
    /*Receives the parameters passed from the profile updating page form */
    $id = $_POST['id'];
    $department_name = ucfirst($_POST['department_name']);
    $department_desc = ucfirst($_POST['department_desc']);
    $dept_type_id = ucfirst($_POST['dept_type_id']);

    /*Passes the parameters to the class function */
    $result = $department->update_department($id,$department_name,$department_desc,$dept_type_id);
    if($result){
        header('location: ../index.php?page=departments&subpage=profile&id=' . $id);
    }
}

/*Main Function Process for deleting a nurse */
function delete_department()
{
    /*If parameter was passed succesfully */
    if (isset($_POST['id'])) {
        $department = new Departments();
        /*Receives the parameters passed from the delete button */
        $id = $_POST['id'];

        /*Passes the parameters to the class function */
        $result = $department->delete_department($id);

        /*If result was executed */
        if ($result) {
            header("location: ../index.php?page=departments&subpage=records");
        }
        /*If result was interrupted */
        else {
            echo "Error deleting department.";
        }
    }
    /*If parameter was not passed successfully */
    else {
        echo "Invalid Department ID.";
    }
}
?>