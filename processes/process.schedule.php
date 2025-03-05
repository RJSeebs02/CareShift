<?php
include '../config/config.php';
include '../class/class.schedule.php';
include '../class/class.logs.php';
include '../class/class.nurse.php';
include_once '../class/class.notifications.php';

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
    case 'add_nurse':
        if (isset($_GET['nurse_id'])) {
            $nurse_id = $_GET['nurse_id'];
            generate_schedule_for_week($con, $nurse_id);
        }
    break;
}

function create_new_schedule($con) {
    $schedule = new Schedule();
    $log = new Log();
    $nurse = new Nurse();
	$notifications = new Notifications();

    $nurse_ids = explode(',', $_POST['nurse_id']); 
    $sched_date = $_POST['sched_date'];

    // Shift time and work hours
    if (isset($_POST['shift_time']) && !empty($_POST['shift_time'])) {
        $shift_time = $_POST['shift_time'];
        $shift_mappings = [
            '6:00 - 14:00' => ['start' => '06:00:00', 'work_hours' => 8],
            '14:00 - 22:00' => ['start' => '14:00:00', 'work_hours' => 8],
            '22:00 - 6:00' => ['start' => '22:00:00', 'work_hours' => 8]
        ];

        if (array_key_exists($shift_time, $shift_mappings)) {
            $sched_start_time = $shift_mappings[$shift_time]['start'];
            $work_hours = $shift_mappings[$shift_time]['work_hours'];
        } else {
            die('Invalid Shift Time');
        }
    } else {
        $sched_start_time = $_POST['sched_start_time'];
        $work_hours = $_POST['work_hours'];
    }

    // Start date and time calculation
    $start_time = new DateTime($sched_date . ' ' . $sched_start_time);
    $end_time = clone $start_time;
    $end_time->modify("+{$work_hours} hours");

    // Format start and end time
    $formatted_start_time = $start_time->format('H:i:s');
    $formatted_end_time = $end_time->format('H:i:s');
    $end_date = $end_time->format('Y-m-d'); // end_date calculation based on start time + work hours

    // Insert schedule for each nurse
    foreach ($nurse_ids as $nurse_id) {
        // Check if the nurse is on leave
        if ($schedule->is_nurse_on_leave($con, $nurse_id, $sched_date)) {
            // Nurse is on leave, log the message
            $nurse_name = $schedule->get_nurse_name($nurse_id);
            $log_action = "Attempted to Add Schedule";
            $log_description = "Cannot schedule $nurse_name (Nurse ID: $nurse_id) on $sched_date as they are on leave.";
            $adm_id = $_SESSION['adm_id'];
            $log->addLog($log_action, $log_description, $adm_id);
        } else {
            // Proceed to add schedule if the nurse is not on leave
            $result = $schedule->new_schedule($nurse_id, $sched_date, $formatted_start_time, $work_hours);
            
            if ($result) {
                $nurse_name = $schedule->get_nurse_name($nurse_id);
                $log_action = "Added Schedule";
                $log_description = "Added a New Schedule: $nurse_name (Nurse ID: $nurse_id) on $sched_date from $formatted_start_time to $formatted_end_time";
                $adm_id = $_SESSION['adm_id'];
                $log->addLog($log_action, $log_description, $adm_id);
				
				// Create notification message
				$notif_type = "Schedule";
				$notif_title = "New Shift Schedule Added";
				$new_formatted_sched_date = (new DateTime($sched_date))->format('F j, Y');
				$notif_msg = "You have a new shift schedule on $new_formatted_sched_date.\nTime: $formatted_start_time to $formatted_end_time";

				// Add notification
				$notifications->addNotifications($notif_type, $notif_title, $notif_msg, $nurse_id);
            }
        }
    }

    header('Location: ../index.php?page=schedule');
}


function auto_generate_schedule($con) {
    $schedule = new Schedule($con);
    $log = new Log($con);
	$notifications = new Notifications();

    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];
    $nurse_ids = explode(',', $_POST['nurse_id']);

    $timeSlots = [
        '06:00:00' => '14:00:00', // Morning
        '14:00:00' => '22:00:00', // Afternoon
        '22:00:00' => '06:00:00'  // Graveyard
    ];

    $startDateTime = new DateTime($startDate);
    $endDateTime = new DateTime($endDate);
    $interval = new DateInterval('P1D');
    $datePeriod = new DatePeriod($startDateTime, $interval, $endDateTime->modify('+1 day'));

    // Log file for debugging
    $debugLogFile = "../logs/debug_schedule.log";
    file_put_contents($debugLogFile, "Auto-generate Schedule Debug Log\n", FILE_APPEND);

    foreach ($datePeriod as $date) {
        $sched_date = $date->format('Y-m-d');

        // Shuffle nurse IDs for randomness
        shuffle($nurse_ids);

        // Round-robin allocation of shifts
        $shiftKeys = array_keys($timeSlots);
        $shiftCount = count($shiftKeys);

        foreach ($nurse_ids as $index => $nurse_id) {
            // Check if a schedule already exists for this nurse on the given date
            if ($schedule->schedule_exists($nurse_id, $sched_date)) {
                continue;
            }

            // Get the last shift assigned to the nurse
            $lastShift = $schedule->get_last_shift($nurse_id);
            $lastAssignedShift = $lastShift ? $lastShift['sched_start_time'] : null;

            // Determine the shift to assign
            $shiftIndex = $index % $shiftCount; // Round-robin index
            $start_time = $shiftKeys[$shiftIndex];
            $end_time = $timeSlots[$start_time];

            // Avoid consecutive graveyard shifts
            if ($lastAssignedShift === '22:00:00' && $start_time === '22:00:00') {
                $shiftIndex = ($shiftIndex + 1) % $shiftCount; // Skip to next shift
                $start_time = $shiftKeys[$shiftIndex];
                $end_time = $timeSlots[$start_time];
            }

            // Save the schedule to the database
            if ($schedule->generate_schedule($nurse_id, $sched_date, $start_time, $end_time)) {
                $nurse_name = $schedule->get_nurse_name($nurse_id);

                // Log the assigned shift for debugging
                $logMessage = "Date: $sched_date | Nurse ID: $nurse_id | Nurse: $nurse_name | Shift: $start_time - $end_time\n";
                file_put_contents($debugLogFile, $logMessage, FILE_APPEND);

                // Add to log table
                $log_action = "Added Schedule";
                $log_description = "Added a New Schedule: $nurse_name (Nurse ID: $nurse_id) on $sched_date with shift $start_time - $end_time";
                $adm_id = $_SESSION['adm_id'];
                $log->addLog($log_action, $log_description, $adm_id);
				
				// Create notification message
				$notif_type = "Schedule";
				$notif_title = "New Shift Schedule Added";
				$new_formatted_sched_date = (new DateTime($sched_date))->format('F j, Y');
				$notif_msg = "You have a new shift schedule on $new_formatted_sched_date.\nTime: $start_time to $end_time";

				// Add notification
				$notifications->addNotifications($notif_type, $notif_title, $notif_msg, $nurse_id);
            }
        }
    }

    header('Location: ../index.php?page=schedule');
}




function generate_schedule_for_week($con, $nurse_id) {
    $schedule = new Schedule($con);
    $log = new Log($con);

    // Get today's date
    $startDate = new DateTime('today');
    
    // Calculate the upcoming Saturday
    $endDate = clone $startDate;
    $endDate->modify('next Saturday');

    // Define time slots
    $timeSlots = [
        '06:00:00' => '14:00:00',
        '14:00:00' => '22:00:00',
        '22:00:00' => '06:00:00'
    ];

    // Create a date period for the current week
    $interval = new DateInterval('P1D');
    $datePeriod = new DatePeriod($startDate, $interval, $endDate->modify('+1 day'));

    foreach ($datePeriod as $date) {
        $sched_date = $date->format('Y-m-d');

        // Check if a schedule already exists for the nurse on this date
        if (!$schedule->schedule_exists($nurse_id, $sched_date)) {
            // Randomly select a time slot
            $randomIndex = array_rand($timeSlots);
            $start_time = $randomIndex;
            $end_time = $timeSlots[$randomIndex];

            // Generate the schedule
            if ($schedule->generate_schedule($nurse_id, $sched_date, $start_time, $end_time)) {
                $nurse_name = $schedule->get_nurse_name($nurse_id);

                // Log the action
                $log_action = "Added Schedule";
                $log_description = "Added a New Schedule: $nurse_name (Nurse ID: $nurse_id) on $sched_date";
                $adm_id = $_SESSION['adm_id'];

                $log->addLog($log_action, $log_description, $adm_id);
            }
        }
    }

    // Redirect to the schedule page
    header('Location: ../index.php?page=schedule');
}

function update_schedule(){  
    $schedule = new Schedule();
    $log = new Log();

    $eventSchedId = $_POST['eventSchedId'];
    $eventNurseId = $_POST['eventNurseId'];
    $eventDate = $_POST['eventDate'];
    $eventStart = $_POST['eventStart'];
    $eventEnd = $_POST['eventEnd'];

    $result = $schedule->update_schedule($eventSchedId, $eventDate, $eventStart, $eventEnd);
    
    if ($result) {
        $nurse_name = $schedule->get_nurse_name($eventSchedId);

        $log_action = "Updated Schedule";
        $log_description = "Updated Schedule for $nurse_name (Nurse ID: $eventNurseId) on $eventDate";
        $adm_id = $_SESSION['adm_id'];
        $log->addLog($log_action, $log_description, $adm_id, $eventNurseId);

        header('location: ../index.php?page=schedule');
    }
}


function delete_schedule(){
    if (isset($_POST['eventSchedId'])) {
        $schedule = new Schedule();
        $log = new Log();
		$nurse = new Nurse();
		
		$id = $_POST['eventSchedId'];
		$nurse_id = $_POST['eventNurseId'];
		$date = $_POST['eventDate'];
		$startTime = $_POST['eventStart'];
		$endTime = $_POST['eventEnd'];
		
		$nurse_id = $schedule->get_schedule_nurse_id($id);
		$nurse_name = $schedule->get_nurse_name($nurse_id);
		
        $result = $schedule->delete_schedule($id);

        if ($result) {
            $log_action = "Deleted Schedule";
            $log_description = "Deleted Schedule of (Nurse ID: $id)\n$date\n$startTime - $endTime";
            $adm_id = $_SESSION['adm_id'];

            $log->addLog($log_action, $log_description, $adm_id, $id);
            header("location: ../index.php?page=schedule&subpage=calendar");
        } else {
            echo "Error deleting schedule.";
        }
    } else {
        echo "Schedule not found.";
    }
}

mysqli_close($con);
?>