<?php
/*Include Admin Class File */
include '../class/class.employee.php';

/*Parameters for switch case*/
$action = isset($_GET['action']) ? $_GET['action'] : '';

/*Switch case for actions in the process */
switch($action){
	case 'new':
        create_new_employee();
	break;
    case 'update':
        update_employee();
	break;
    case 'delete':
        delete_employee();
    break;
}

/*Main Function Process for creating an employee */
function create_new_employee(){
    $employee = new Employee();
    /*Receives the parameters passed from the creation page form */
    $first_name = ucfirst($_POST['first_name']);
    $middle_name = ucfirst($_POST['middle_name']);
    $last_name = ucfirst($_POST['last_name']);
    $email = $_POST['email'];
    $contact_no = $_POST['contact_no'];
    $department = $_POST['department'];

    $password = 123;

    /*Passes the parameters to the class function */
    $result = $employee->new_employee($password,$first_name,$middle_name,$last_name,$email,$contact_no,$department);
    if($result){
        $id = $employee->get_id($first_name);
        header("location: ../index.php?page=employees");
    }
}

/*Main Function Process for updating an admin */
function update_employee(){  
    $employee = new Employee();
    /*Receives the parameters passed from the profile updating page form */
    $id = $_POST['id'];
    $first_name = ucfirst($_POST['first_name']);
    $middle_name = ucfirst($_POST['middle_name']);
    $last_name = ucfirst($_POST['last_name']);
    $email = $_POST['email'];
    $contact_no = $_POST['contact_no'];
    $department = $_POST['department'];

    

    /*Passes the parameters to the class function */
    $result = $employee->update_employee($id,$first_name,$middle_name,$last_name,$email,$contact_no,$department);
    if($result){
        header('location: ../index.php?page=employees&subpage=profile&id=' . $id);
    }
}

/*Main Function Process for deleting an admin */
function delete_admin(){
    if (isset($_POST['adm_username'])) {
        $admin = new Admin();
        $id = $_POST['adm_username'];
        $result = $admin->delete_admin($id);
        if ($result) {
            header("location: ../index.php?page=admins");
        } 
    }
}


?>