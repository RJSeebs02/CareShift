<?php
include '../config/config.php';
include '../class/class.rooms.php';

/*Parameters for switch case*/
$action = isset($_GET['action']) ? $_GET['action'] : '';

/*Switch case for actions in the process */
switch($action){
	case 'new':
        create_new_room($con);
	break;
    case 'update':
        update_room();
	break;
    case 'delete':
        delete_room();
    break;
}

/*Main Function Process for creating a room */
function create_new_room($con) {
    $room = new Rooms();
    /* Receives the parameters passed from the creation page form */
    $room_name = ucfirst($_POST['room_name']);
    $room_slots = $_POST['room_slots'];
    $room_status = 1;
    $department_id = $_POST['department_id'];

    /* Passes the parameters to the class function */
    $result = $room->new_room($room_name, $room_slots, $room_status, $department_id);
    
    if ($result) {
        $id = $room->get_id_by_room_name($room_name);

        header("location: ../index.php?page=rooms");
    } else {
        echo "<script>alert('Error adding room.'); window.history.back();</script>";
    }
}

/*Main Function Process for updating a room */
function update_room(){  
    $room = new Rooms();
    /*Receives the parameters passed from the profile updating page form */
    $id = $_POST['id'];
    $room_name = ucfirst($_POST['room_name']);
    $room_slots = $_POST['room_slots'];
    $status_id = $_POST['status_id'];
    $department_id = $_POST['department_id'];

    /*Passes the parameters to the class function */
    $result = $room->update_room($id,$room_name,$room_slots,$status_id,$department_id);
    if($result){
        header('location: ../index.php?page=rooms&subpage=profile&id=' . $id);
    }
}

/*Main Function Process for deleting a room */
function delete_room()
{
    /*If parameter was passed succesfully */
    if (isset($_POST['id'])) {
        $room = new Rooms();
        /*Receives the parameters passed from the delete button */
        $id = $_POST['id'];

        /*Passes the parameters to the class function */
        $result = $room->delete_room($id);

        /*If result was executed */
        if ($result) {
            header("location: ../index.php?page=rooms&subpage=records");
        }
        /*If result was interrupted */
        else {
            echo "Error deleting room.";
        }
    }
    /*If parameter was not passed successfully */
    else {
        echo "Invalid Room ID.";
    }
}
?>