<?php
include '../config/config.php';
include '../class/class.schedule.php';
include '../class/class.logs.php';

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
    case 'generate':
        auto_generate_schedule($con);
    break;
    }

    function create_new_schedule($con) {
        $schedule = new Schedule();
        $log = new Log();
    
        $nurse_ids = explode(',', $_POST['nurse_id']); 
        $sched_date = $_POST['sched_date'];
        $sched_start_time = $_POST['sched_start_time'];
        $work_hours = $_POST['work_hours'];
    
        $start_time = new DateTime($sched_date . ' ' . $sched_start_time);
        $end_time = clone $start_time;
        $end_time->modify("+{$work_hours} hours");
    
        $formatted_start_time = $start_time->format('H:i:s');
        $formatted_end_time = $end_time->format('H:i:s');
        $end_date = $end_time->format('Y-m-d');
    
        foreach ($nurse_ids as $nurse_id) {
            $result = $schedule->new_schedule($nurse_id, $sched_date, $formatted_start_time, $work_hours);
            
            if ($result) {
                $log_action = "Added Schedule";
                $log_description = "Added a new schedule for nurse ID $nurse_id";
                $log_date_managed = date('Y-m-d');
                $log_time_managed = date('H:i:s');
                $adm_id = $_SESSION['adm_id'];
    
                $log->addLog($log_action, $log_description, $adm_id, $nurse_id);
            }
        }
    
        header('Location: ../index.php?page=schedule');
    }
    

function auto_generate_schedule($con) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the schedule start and end dates
        $startDate = $_POST['start_date'];
        $endDate = $_POST['end_date'];

        // Get the selected nurse IDs
        $nurseIds = explode(',', $_POST['nurse_id']);

        // Define time slots
        $timeSlots = [
            '06:00:00' => '14:00:00', // 6 AM - 2 PM
            '14:00:00' => '22:00:00', // 2 PM - 10 PM
            '22:00:00' => '06:00:00'  // 10 PM - 6 AM
        ];

        // Convert the start and end dates to DateTime objects
        $startDateTime = new DateTime($startDate);
        $endDateTime = new DateTime($endDate);

        // Loop through each day in the range
        $interval = new DateInterval('P1D'); // Interval of 1 day
        $datePeriod = new DatePeriod($startDateTime, $interval, $endDateTime->modify('+1 day')); // Include the end date

        // Loop through each selected nurse
        foreach ($nurseIds as $nurseId) {
            // Loop through the date range
            foreach ($datePeriod as $date) {
                $schedDate = $date->format('Y-m-d'); // Format the date to 'Y-m-d'

                // Check if the nurse already has a schedule for this date
                $checkQuery = "SELECT * FROM schedule WHERE nurse_id = ? AND sched_date = ?";
                $stmt = $con->prepare($checkQuery);
                $stmt->bind_param("is", $nurseId, $schedDate);
                $stmt->execute();
                $result = $stmt->get_result();

                // If no schedule exists for the nurse on that day, generate a new schedule
                if ($result->num_rows == 0) {
                    // Randomly choose a time slot
                    $randomIndex = array_rand($timeSlots);
                    $startTime = $randomIndex;
                    $endTime = $timeSlots[$randomIndex];

                    // Insert the schedule for this nurse and date
                    $insertQuery = "INSERT INTO schedule (nurse_id, sched_date, sched_start_time, sched_end_time) 
                                    VALUES (?, ?, ?, ?)";
                    $insertStmt = $con->prepare($insertQuery);
                    $insertStmt->bind_param("isss", $nurseId, $schedDate, $startTime, $endTime);
                    $insertStmt->execute();
                }

                $stmt->close();
            }
        }

        // Redirect or show a success message
        header('Location: ../index.php?page=schedule');
    }
}

/*Main Function Process for updating a schedule */
function update_schedule(){  
    $schedule = new Schedule();
    /*Receives the parameters passed from the profile updating page form */
    $eventSchedId = $_POST['eventSchedId'];
    $eventNurseId = $_POST['eventNurseId'];
    $eventDate = $_POST['eventDate'];  // Check if this is being received correctly
    $eventStart = $_POST['eventStart'];
    $eventEnd = $_POST['eventEnd'];

    // Debugging to check values
    var_dump($eventDate, $eventStart, $eventEnd);  // Debug output

    /*Passes the parameters to the class function */
    $result = $schedule->update_schedule($eventSchedId, $eventDate, $eventStart, $eventEnd);
    if ($result) {
        header('location: ../index.php?page=schedule');
    }
}




mysqli_close($con);
?>
