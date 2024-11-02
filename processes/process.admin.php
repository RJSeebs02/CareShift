<?php
/*Include Admin Class File */
include '../class/class.admin.php';

/*Parameters for switch case*/
$action = isset($_GET['action']) ? $_GET['action'] : '';

/*Switch case for actions in the process */
switch($action){
	case 'new':
        create_new_admin();
	break;
    case 'update':
        update_admin();
	break;
    case 'delete':
        delete_admin();
    break;
}

/*Main Function Process for creating an admin */
function create_new_admin(){
    $admin = new Admin();
    /*Receives the parameters passed from the creation page form */
    $username = $_POST['username'];
    $first_name = ucfirst($_POST['first_name']);
    $middle_name = ucfirst($_POST['middle_name']);
    $last_name = ucfirst($_POST['last_name']);
    $email = $_POST['email'];
    $contact_no = $_POST['contact_no'];
    $department = $_POST['department'];
    $access = $_POST['access'];

    $password = 123;

    /*Passes the parameters to the class function */
    $result = $admin->new_admin($username,$password,$first_name,$middle_name,$last_name,$email,$contact_no,$access,$department);
    if($result){
        $id = $admin->get_id_by_username($username);
        header("location: ../index.php?page=admins");
    }
}

/*Main Function Process for updating an admin */
function update_admin(){  
    $admin = new Admin();
    /*Receives the parameters passed from the profile updating page form */
    $id = $_POST['id'];
    $username = $_POST['username'];
    $first_name = ucfirst($_POST['first_name']);
    $middle_name = ucfirst($_POST['middle_name']);
    $last_name = ucfirst($_POST['last_name']);
    $email = $_POST['email'];
    $contact_no = $_POST['contact_no'];
    $access = $_POST['access'];

    /*Passes the parameters to the class function */
    $result = $admin->update_admin($id,$username,$first_name,$middle_name,$last_name,$email,$contact_no,$access);
    if($result){
        header('location: ../index.php?page=admins&subpage=profile&id=' . $id);
    }
}

/*Main Function Process for deleting a nurse */
function delete_admin()
{
    /*If parameter was passed succesfully */
    if (isset($_POST['id'])) {
        $admin = new Admin();
        /*Receives the parameters passed from the delete button */
        $id = $_POST['id'];

        /*Passes the parameters to the class function */
        $result = $admin->delete_admin($id);

        /*If result was executed */
        if ($result) {
            header("location: ../index.php?page=admins&subpage=records");
        }
        /*If result was interrupted */
        else {
            echo "Error deleting admin.";
        }
    }
    /*If parameter was not passed successfully */
    else {
        echo "Invalid Admin ID.";
    }
}
?>