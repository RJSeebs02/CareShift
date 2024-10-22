<?php
include '../config/config.php';

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nurse_id = $_POST['nurse_id'];
    $sched_date = $_POST['sched_date'];
    $sched_start_time = $_POST['sched_start_time'];
    $work_hours = $_POST['work_hours'];

    // Calculate the end time based on work hours
    $start_time = new DateTime($sched_date . ' ' . $sched_start_time);
    $end_time = clone $start_time; // Clone to avoid modifying the original start time
    $end_time->modify("+{$work_hours} hours");

    // Format the times for insertion into the database
    $formatted_start_time = $start_time->format('H:i:s');
    $formatted_end_time = $end_time->format('H:i:s');
    $end_date = $end_time->format('Y-m-d');  // The end date may change if shift crosses midnight

    // Insert into the database
    $insert_query = "INSERT INTO schedule (nurse_id, sched_date, sched_start_time, sched_end_time) 
                     VALUES ('$nurse_id', '$sched_date', '$formatted_start_time', '$formatted_end_time')";

    if (mysqli_query($con, $insert_query)) {
        echo "<script>alert('Schedule generated successfully!'); window.location.href = '../index.php?page=schedule&subpage=calendar';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($con) . "'); window.location.href = '../index.php?page=schedule&subpage=calendar';</script>";
    }
}

mysqli_close($con);
?>
