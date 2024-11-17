<?php
include '../config/config.php';
include '../class/class.schedule.php';
include '../class/class.logs.php';
include '../class/class.nurse.php';

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
        // Check if a schedule already exists
        if (!$schedule->schedule_exists($nurse_id, $sched_date)) {
            $result = $schedule->new_schedule($nurse_id, $sched_date, $formatted_start_time, $work_hours);

            if ($result) {
                $nurse_name = $schedule->get_nurse_name($nurse_id);

                $log_action = "Added Schedule";
                $log_description = "Added a New Schedule: $nurse_name (Nurse ID: $nurse_id) on $sched_date";
                $adm_id = $_SESSION['adm_id'];

                $log->addLog($log_action, $log_description, $adm_id);
            }
        } else {
            // Log or notify if the schedule already exists (optional)
            $nurse_name = $schedule->get_nurse_name($nurse_id);
            $log_action = "Schedule Exists";
            $log_description = "Schedule already exists for $nurse_name (Nurse ID: $nurse_id) on $sched_date";
            $adm_id = $_SESSION['adm_id'];

            $log->addLog($log_action, $log_description, $adm_id);
        }
    }

    header('Location: ../index.php?page=schedule');
}


function auto_generate_schedule($con) {
    $schedule = new Schedule($con);
    $log = new Log($con);
    
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];

    $nurse_ids = explode(',', $_POST['nurse_id']);

    $timeSlots = [
        '06:00:00' => '14:00:00',
        '14:00:00' => '22:00:00',
        '22:00:00' => '06:00:00'
    ];

    $startDateTime = new DateTime($startDate);
    $endDateTime = new DateTime($endDate);
    $interval = new DateInterval('P1D');
    $datePeriod = new DatePeriod($startDateTime, $interval, $endDateTime->modify('+1 day'));

    foreach ($nurse_ids as $nurse_id) {
        foreach ($datePeriod as $date) {
            $sched_date = $date->format('Y-m-d');

            if (!$schedule->schedule_exists($nurse_id, $sched_date)) {
                $randomIndex = array_rand($timeSlots);
                $start_time = $randomIndex;
                $end_time = $timeSlots[$randomIndex];

                if ($schedule->generate_schedule($nurse_id, $sched_date, $start_time, $end_time)) {
                    $nurse_name = $schedule->get_nurse_name($nurse_id);

                    $log_action = "Added Schedule";
                    $log_description = "Added a New Schedule: $nurse_name (Nurse ID: $nurse_id) on $sched_date";
                    $adm_id = $_SESSION['adm_id'];

                    $log->addLog($log_action, $log_description, $adm_id);
                }
            }
        }
    }

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



mysqli_close($con);
?>
