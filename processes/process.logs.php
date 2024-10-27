<?php
// Assuming $action is passed to the logs processing script
switch ($action) {
    case 'add_schedule':
        $log_action = "Added Schedule";
        $log_description = "Added a new schedule for nurse ID {$nurse_id}";
        break;
    
    case 'delete_schedule':
        $log_action = "Deleted Schedule";
        $log_description = "Deleted a schedule for nurse ID {$nurse_id}";
        break;

    case 'update_schedule':
        $log_action = "Updated Schedule";
        $log_description = "Updated a schedule for nurse ID {$nurse_id}";
        break;

    default:
        $log_action = "Unknown Action";
        $log_description = "An unknown action occurred.";
        break;
}

// Function to insert log into the database
function addLog($log_action, $log_description, $adm_id, $nurse_id) {
    global $con; // Ensure $con is accessible
    $log_date_managed = date('Y-m-d');
    $log_time_managed = date('H:i:s');

    $log_insert_query = "INSERT INTO logs (log_action, log_description, log_time_managed, log_date_managed, adm_id, nurse_id) 
                         VALUES ('$log_action', '$log_description', '$log_time_managed', '$log_date_managed', '$adm_id', '$nurse_id')";
    
    mysqli_query($con, $log_insert_query);
}

// Call the logging function
addLog($log_action, $log_description, $adm_id, $nurse_id);

?>
