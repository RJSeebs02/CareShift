<?php
include '../config/config.php';

header('Content-Type: application/json');

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

$query = "SELECT emp_id, sched_date, sched_start_time, sched_end_time FROM schedule";
$result = mysqli_query($con, $query);

$events = [];

while ($row = mysqli_fetch_assoc($result)) {
    $events[] = [
        'title' => "Nurse ID: " . $row['emp_id'], 
        'start' => $row['sched_date'] . 'T' . $row['sched_start_time'],
        'end' => $row['sched_date'] . 'T' . $row['sched_end_time'],
    ];
}

echo json_encode($events);
mysqli_close($con);
?>
