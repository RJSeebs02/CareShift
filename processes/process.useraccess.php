<?php
include '../config/config.php';
include '../class/class.useraccess.php';

/*Parameters for switch case*/
$action = isset($_GET['action']) ? $_GET['action'] : '';

/*Switch case for actions in the process */
switch($action){
	case 'new':
        create_new_useraccess($con);
	break;
    case 'update':
        update_useraccess();
	break;
    case 'delete':
        delete_useraccess();
    break;
}

/*Main Function Process for creating an nurse */
function create_new_useraccess($con) {
    $useraccess = new UserAccess();
    /* Receives the parameters passed from the creation page form */
    $useraccess_name = ucfirst($_POST['useraccess_name']);
    $useraccess_desc = ucfirst($_POST['useraccess_desc']);

    /* Passes the parameters to the class function */
    $result = $useraccess->new_access($useraccess_name, $useraccess_desc);
    
    if ($result) {
        $id = $useraccess->get_id_by_useraccess_name($useraccess_name);

        header("location: ../index.php?page=useraccess");
    } else {
        echo "<script>alert('Error adding nurse.'); window.history.back();</script>";
    }
}

/*Main Function Process for updating an admin */
function update_useraccess(){  
    $useraccess = new UserAccess();
    /*Receives the parameters passed from the profile updating page form */
    $id = $_POST['id'];
    $useraccess_name = ucfirst($_POST['useraccess_name']);
    $useraccess_desc = ucfirst($_POST['useraccess_desc']);

    /*Passes the parameters to the class function */
    $result = $useraccess->update_user_access($id,$useraccess_name,$useraccess_desc);
    if($result){
        header('location: ../index.php?page=useraccess&subpage=profile&id=' . $id);
    }
}

/*Main Function Process for deleting a user access */
function delete_useraccess()
{
    /*If parameter was passed succesfully */
    if (isset($_POST['id'])) {
        $useraccess = new UserAccess();
        /*Receives the parameters passed from the delete button */
        $id = $_POST['id'];

        /*Passes the parameters to the class function */
        $result = $useraccess->delete_useraccess($id);

        /*If result was executed */
        if ($result) {
            header("location: ../index.php?page=useraccess&subpage=records");
        }
        /*If result was interrupted */
        else {
            echo "Error deleting user access.";
        }
    }
    /*If parameter was not passed successfully */
    else {
        echo "Invalid User Access ID.";
    }
}
?>