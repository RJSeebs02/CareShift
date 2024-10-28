<?php
require_once 'class.report.php'; // Include the class
$report = new Report();

if (isset($_GET['nurse_id'])) {
    $nurse_id = $_GET['nurse_id'];
    $schedules = $report->getSchedulesByNurseId($nurse_id);
    echo json_encode($schedules); // Return data as JSON
}
?>