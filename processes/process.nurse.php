<?php
/*Include Admin Class File */
include '../class/class.nurse.php';

/*Parameters for switch case*/
$action = isset($_GET['action']) ? $_GET['action'] : '';

/*Switch case for actions in the process */
switch($action){
	case 'new':
        create_new_nurse();
	break;
    case 'update':
        update_nurse();
	break;
    case 'delete':
        delete_nurse();
    break;
}

/*Main Function Process for creating an nurse */
function create_new_nurse(){
    $nurse = new Nurse();
    /*Receives the parameters passed from the creation page form */
    $first_name = ucfirst($_POST['first_name']);
    $middle_name = ucfirst($_POST['middle_name']);
    $last_name = ucfirst($_POST['last_name']);
    $email = $_POST['email'];
    $contact_no = $_POST['contact_no'];
    $position = $_POST['position'];
    $department = $_POST['department'];

    $password = 123;

    /*Passes the parameters to the class function */
    $result = $nurse->new_nurse($password,$first_name,$middle_name,$last_name,$email,$contact_no,$position,$department);
    if($result){
        $id = $nurse->get_id($first_name);
        header("location: ../index.php?page=nurses");
    }
}

/*Main Function Process for updating an admin */
function update_nurse(){  
    $nurse = new Nurse();
    /*Receives the parameters passed from the profile updating page form */
    $id = $_POST['id'];
    $first_name = ucfirst($_POST['first_name']);
    $middle_name = ucfirst($_POST['middle_name']);
    $last_name = ucfirst($_POST['last_name']);
    $email = $_POST['email'];
    $contact_no = $_POST['contact_no'];
    $position = $_POST['position'];
    $department = $_POST['department'];

    /*Passes the parameters to the class function */
    $result = $nurse->update_nurse($id,$first_name,$middle_name,$last_name,$email,$contact_no,$position,$department);
    if($result){
        header('location: ../index.php?page=nurses&subpage=profile&id=' . $id);
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