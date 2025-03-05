<?php
include '../config/config.php';
include '../class/class.attendance.php';
include '../class/class.logs.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';
$nurse_id = isset($_GET['nurse_id']) ? $_GET['nurse_id'] : ''; 

switch($action){
    case 'new':
        if ($nurse_id) {
            new_attendance($nurse_id, $con);  // Pass nurse_id to the function
        } else {
            echo "<script>alert('Nurse ID is missing.'); window.history.back();</script>";
        }
    break;
    case 'update':
        if ($nurse_id) {
            update_attendance($nurse_id, $con);  // Pass nurse_id to the function
        } else {
            echo "<script>alert('Nurse ID is missing.'); window.history.back();</script>";
        }
    break;
}

function new_attendance($nurse_id, $con) {
    $attendance = new Attendance();
    $log = new Log();

    // Get the current date and time
    $date = date('Y-m-d');  // Attendance date (today)
    $time = date('H:i:s');  // Attendance time (current time)

    // Check if attendance already exists for the nurse on the given date
    $existingAttendance = $attendance->check_attendance($nurse_id, $date);

    if ($existingAttendance) {
        // If attendance exists, check if the nurse is already checked in
        if (empty($existingAttendance['att_out_time'])) {
            // Update the attendance record with the check-out time
            $result = $attendance->update_checkout_time($nurse_id, $date, $time);
            
            if ($result) {
                // Log the action
                $log_action = "Checked Out";
                $log_description = "Nurse ID: $nurse_id checked out at $time";
                $adm_id = $_SESSION['adm_id'];  // Admin ID from the session

                $log->addLog($log_action, $log_description, $adm_id);

                // Redirect to the attendance page
                header("location: ../index.php?page=scan&subpage=profile&id=$nurse_id");
                exit();
            } else {
                // Error updating check-out time
                echo "<script>alert('Error recording check-out time.'); window.history.back();</script>";
            }
        } else {
            // If the nurse already has a check-out time, inform them
            echo "<script>alert('Attendance already checked out for today.'); window.history.back();</script>";
        }
    } else {
        // If no attendance exists, create new attendance (check-in)
        $result = $attendance->new_attendance($nurse_id, $date, $time);

        if ($result) {
            // Log the action
            $log_action = "Created New Attendance";
            $log_description = "Created attendance for Nurse ID: $nurse_id";
            $adm_id = $_SESSION['adm_id'];  // Admin ID from the session

            $log->addLog($log_action, $log_description, $adm_id);

            // Redirect to the attendance page
            header("location: ../index.php?page=scan&subpage=profile&id=$nurse_id");
            exit();
        } else {
            // Error creating attendance
            echo "<script>alert('Error creating new attendance.'); window.history.back();</script>";
        }
    }
}

function update_attendance($nurse_id, $con) {
    $attendance = new Attendance();
    $log = new Log();

    // Get the current date and time
    $att_date = date('Y-m-d');  // Attendance date (today)
    $att_out_time = date('H:i:s');  // Attendance time (current time)

    // Update the existing attendance record
    $result = $attendance->update_checkout_time($nurse_id, $att_date, $att_out_time);

    if ($result) {
        // Log the update action
        $log_action = "Updated Attendance";
        $log_description = "Updated attendance for Nurse ID: $nurse_id";
        $adm_id = $_SESSION['adm_id'];  // Admin ID from the session

        $log->addLog($log_action, $log_description, $adm_id);

        // Redirect to the attendance page
        header("location: ../index.php?page=scan&subpage=profile&id=$nurse_id");
        exit();
    } else {
        // If there's an error updating attendance
        echo "<script>alert('Error updating attendance.'); window.history.back();</script>";
    }
}
?>
