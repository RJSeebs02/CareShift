<?php
include '../config/config.php';
include '../class/class.rooms.php';
include '../class/class.logs.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';

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

function create_new_room($con) {
    $room = new Rooms();
    $log = new Log();

    $room_name = ucfirst($_POST['room_name']);
    $room_slots = $_POST['room_slots'];
    $room_status = 1;
    $department_id = $_POST['department_id'];

    $result = $room->new_room($room_name, $room_slots, $room_status, $department_id);
    
    if ($result) {
        $id = $room->get_id_by_room_name($room_name);

        $log_action = "Created New Room";
        $log_description = "Created Room: $room_name (Room ID: $id)";
        $adm_id = $_SESSION['adm_id'];

        $log->addLog($log_action, $log_description, $adm_id);

        header("location: ../index.php?page=rooms");
    } else {
        echo "<script>alert('Error adding room.'); window.history.back();</script>";
    }
}

function update_room(){  
    $room = new Rooms();
    $log = new Log();

    $id = $_POST['id'];
    $room_name = ucfirst($_POST['room_name']);
    $room_slots = $_POST['room_slots'];
    $status_id = $_POST['status_id'];
    $department_id = $_POST['department_id'];

    $result = $room->update_room($id, $room_name, $room_slots, $status_id, $department_id);
    
    if($result){

        $log_action = "Updated Room";
        $log_description = "Updated Details for $room_name (Room ID: $id)";
        $adm_id = $_SESSION['adm_id'];

        $log->addLog($log_action, $log_description, $adm_id, $id);
        header('location: ../index.php?page=rooms&subpage=profile&id=' . $id);
    }
}

function delete_room(){
    if (isset($_POST['id'])) {
        $room = new Rooms();
        $log = new Log();
        $id = $_POST['id'];

        $room_name = $room->get_room_name($id);

        $result = $room->delete_room($id);

        if ($result) {
            $log_action = "Deleted Room";
            $log_description = "Deleted Room: $room_name (Room ID: $id)";
            $adm_id = $_SESSION['adm_id'];
            
            $log->addLog($log_action, $log_description, $adm_id, $id);
            header("location: ../index.php?page=rooms&subpage=records");
        } else {
            echo "Error deleting room.";
        }
    } else {
        echo "Invalid Room ID.";
    }
}
?>
