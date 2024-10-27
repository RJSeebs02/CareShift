<?php
include '../config/config.php';
include '../class/class.schedule.php';

$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
    case 'new':
        create_new_schedule($con);
    break;
    case 'update':
        update_schedule();
    break;
    case 'delete':
        delete_schedule();
    break;
    case 'multiple':
        multiple_schedule($con);
    break;
    case 'generate':
        auto_generate_schedule($con);
    break;
    }

function create_new_schedule($con) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nurse_id = $_POST['nurse_id'];
        $sched_date = $_POST['sched_date'];
        $sched_start_time = $_POST['sched_start_time'];
        $work_hours = $_POST['work_hours'];

        $start_time = new DateTime($sched_date . ' ' . $sched_start_time);
        $end_time = clone $start_time; 
        $end_time->modify("+{$work_hours} hours");

        $formatted_start_time = $start_time->format('H:i:s');
        $formatted_end_time = $end_time->format('H:i:s');
        $end_date = $end_time->format('Y-m-d'); 

        $insert_query = "INSERT INTO schedule (nurse_id, sched_date, sched_start_time, sched_end_time) 
                         VALUES ('$nurse_id', '$sched_date', '$formatted_start_time', '$formatted_end_time')";

        if (mysqli_query($con, $insert_query)) {
            $log_action = "Added Schedule";
            $log_description = "Added a new schedule for nurse ID $nurse_id";
            $log_date_managed = date('Y-m-d');
            $log_time_managed = date('H:i:s'); 
            $adm_id = $_SESSION['adm_id']; 

            $log_insert_query = "INSERT INTO logs (log_action, log_description, log_time_managed, log_date_managed, adm_id, nurse_id) 
                                 VALUES ('$log_action', '$log_description', '$log_time_managed', '$log_date_managed', '$adm_id', '$nurse_id')";

            mysqli_query($con, $log_insert_query);

            echo "<script>alert('Schedule added successfully!'); window.location.href = '../index.php?page=schedule&subpage=calendar';</script>";
        } else {
            echo "<script>alert('Error: " . mysqli_error($con) . "'); window.location.href = '../index.php?page=schedule&subpage=calendar';</script>";
        }
    }
}

function multiple_schedule($con) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nurse_ids = isset($_POST['nurse_id']) ? $_POST['nurse_id'] : [];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];

        // Check if 'all' is selected
        if (in_array('all', $nurse_ids)) {
            $query = "SELECT nurse_id FROM nurse";
            $result = mysqli_query($con, $query);
            $nurse_ids = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $nurse_ids[] = $row['nurse_id'];
            }
        }

        // Loop through selected nurses and insert schedules
        foreach ($nurse_ids as $nurse_id) {
            $current_date = $start_date;
            while (strtotime($current_date) <= strtotime($end_date)) {
                $insert_query = "INSERT INTO schedule (nurse_id, sched_date, sched_start_time, sched_end_time) 
                                 VALUES ('$nurse_id', '$current_date', '$start_time', '$end_time')";
                
                if (!mysqli_query($con, $insert_query)) {
                    // Log error if query fails
                    error_log("Error inserting schedule: " . mysqli_error($con));
                    echo "<script>alert('Error inserting schedule for nurse ID $nurse_id: " . mysqli_error($con) . "');</script>";
                }
                
                // Move to the next date
                $current_date = date('Y-m-d', strtotime($current_date . ' +1 day'));
            }
        }

        // Redirect or show success message
        header("Location: ../index.php?page=schedules");
        exit();
    }
}

function auto_generate_schedule($con) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nurse_ids = isset($_POST['nurse_id']) ? $_POST['nurse_id'] : [];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];

        // Check if 'all' is selected
        if (in_array('all', $nurse_ids)) {
            $query = "SELECT nurse_id FROM nurse";
            $result = mysqli_query($con, $query);
            $nurse_ids = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $nurse_ids[] = $row['nurse_id'];
            }
        }

        // Define rotating shifts
        $shifts = [
            ['start_time' => '06:00:00', 'end_time' => '14:00:00'], // Morning shift
            ['start_time' => '14:00:00', 'end_time' => '22:00:00'], // Afternoon shift
            ['start_time' => '22:00:00', 'end_time' => '06:00:00']  // Night shift
        ];
        
        // Loop through selected nurses and insert schedules with rotating shifts
        foreach ($nurse_ids as $nurse_id) {
            $current_date = $start_date;
            $shift_index = 0; // Start with the first shift (morning)
            
            while (strtotime($current_date) <= strtotime($end_date)) {
                $current_shift = $shifts[$shift_index];

                $insert_query = "INSERT INTO schedule (nurse_id, sched_date, sched_start_time, sched_end_time) 
                                 VALUES ('$nurse_id', '$current_date', '{$current_shift['start_time']}', '{$current_shift['end_time']}')";

                if (!mysqli_query($con, $insert_query)) {
                    // Log error if query fails
                    error_log("Error inserting schedule: " . mysqli_error($con));
                    echo "<script>alert('Error inserting schedule for nurse ID $nurse_id: " . mysqli_error($con) . "');</script>";
                }
                
                // Rotate to the next shift
                $shift_index = ($shift_index + 1) % 3; // Cycle through 0, 1, 2 (morning, afternoon, night)
                
                // Move to the next date
                $current_date = date('Y-m-d', strtotime($current_date . ' +1 day'));
            }
        }

        // Redirect or show success message
        header("Location: ../index.php?page=schedules");
        exit();
    }
}

mysqli_close($con);
?>
