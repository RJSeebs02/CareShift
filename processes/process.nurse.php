<?php
include '../config/config.php';
include '../class/class.nurse.php';

/*Parameters for switch case*/
$action = isset($_GET['action']) ? $_GET['action'] : '';

/*Switch case for actions in the process */
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
}

/*Main Function Process for creating an nurse */
function create_new_nurse($con) {
    $nurse = new Nurse();
    /* Receives the parameters passed from the creation page form */
    $first_name = ucfirst($_POST['first_name']);
    $middle_name = ucfirst($_POST['middle_name']);
    $last_name = ucfirst($_POST['last_name']);
    $email = $_POST['email'];
    $sex = $_POST['sex'];
    $contact_no = $_POST['contact_no'];
    $position = $_POST['position'];
    $department = $_POST['department'];

    $password = 123;

    /* Passes the parameters to the class function */
    $result = $nurse->new_nurse($password, $first_name, $middle_name, $last_name, $email, $sex, $contact_no, $position, $department);
    
    if ($result) {
        $id = $nurse->get_id($first_name);
        
        // You might want to log the action here, e.g., inserting into a logs table
        $log_action = "Added Nurse";
        $log_description = "Added a new nurse";
        $log_date_managed = date('Y-m-d');
        $log_time_managed = date('H:i:s');
        $adm_id = $_SESSION['adm_id']; 

        $log_insert_query = "INSERT INTO logs (log_action, log_description, log_time_managed, log_date_managed, adm_id) 
                             VALUES ('$log_action', '$log_description', '$log_time_managed', '$log_date_managed', '$adm_id')";
        
        mysqli_query($con, $log_insert_query);

        header("location: ../index.php?page=nurses");
    } else {
        echo "<script>alert('Error adding nurse.'); window.history.back();</script>";
    }
}

/*Main Function Process for updating an nurse */
function update_nurse(){  
    $nurse = new Nurse();
    /*Receives the parameters passed from the profile updating page form */
    $id = $_POST['id'];
    $first_name = ucfirst($_POST['first_name']);
    $middle_name = ucfirst($_POST['middle_name']);
    $last_name = ucfirst($_POST['last_name']);
    $email = $_POST['email'];
    $sex = $_POST['sex'];
    $contact_no = $_POST['contact_no'];
    $position = $_POST['position'];
    $department = $_POST['department'];

    /*Passes the parameters to the class function */
    $result = $nurse->update_nurse($id,$first_name,$middle_name,$last_name,$email,$sex,$contact_no,$position,$department);
    if($result){
        header('location: ../index.php?page=nurses&subpage=profile&id=' . $id);
    }
}

/*Main Function Process for deleting a nurse */
function delete_nurse()
{
    /*If parameter was passed succesfully */
    if (isset($_POST['id'])) {
        $nurse = new Nurse();
        /*Receives the parameters passed from the delete button */
        $id = $_POST['id'];

        /*Passes the parameters to the class function */
        $result = $nurse->delete_nurse($id);

        /*If result was executed */
        if ($result) {
            header("location: ../index.php?page=nurses&subpage=records");
        }
        /*If result was interrupted */
        else {
            echo "Error deleting nurse.";
        }
    }
    /*If parameter was not passed successfully */
    else {
        echo "Invalid Nurse ID.";
    }
}
?>