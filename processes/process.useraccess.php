<?php
include '../config/config.php';
include '../class/class.useraccess.php';
include '../class/class.logs.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';

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

function create_new_useraccess($con) {
    $useraccess = new UserAccess();
    $log = new Log();

    $useraccess_name = ucfirst($_POST['useraccess_name']);
    $useraccess_desc = ucfirst($_POST['useraccess_desc']);

    $result = $useraccess->new_access($useraccess_name, $useraccess_desc);
    
    if ($result) {
        $id = $useraccess->get_id_by_useraccess_name($useraccess_name);
        $log->addLog("Added User Access", "Added new user access: $useraccess_name", $_SESSION['adm_id'], $id);
        header("location: ../index.php?page=useraccess");
    } else {
        echo "<script>alert('Error adding user access.'); window.history.back();</script>";
    }
}

function update_useraccess(){  
    $useraccess = new UserAccess();
    $log = new Log();

    $id = $_POST['id'];
    $useraccess_name = ucfirst($_POST['useraccess_name']);
    $useraccess_desc = ucfirst($_POST['useraccess_desc']);

    $result = $useraccess->update_user_access($id, $useraccess_name, $useraccess_desc);

    if($result){
        $log->addLog("Updated User Access", "Updated user access ID $id with name $useraccess_name", $_SESSION['adm_id'], $id);
        header('location: ../index.php?page=useraccess&subpage=profile&id=' . $id);
    }
}

function delete_useraccess()
{
    if (isset($_POST['id'])) {
        $useraccess = new UserAccess();
        $log = new Log();

        $id = $_POST['id'];

        $result = $useraccess->delete_useraccess($id);

        if ($result) {
            $log->addLog("Deleted User Access", "Deleted user access ID $id", $_SESSION['adm_id'], $id);
            header("location: ../index.php?page=useraccess&subpage=records");
        } else {
            echo "Error deleting user access.";
        }
    } else {
        echo "Invalid User Access ID.";
    }
}
?>
