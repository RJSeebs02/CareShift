<?php
require_once 'class/class.report.php'; 
$report = new Report();

if (isset($_GET['nurse_id'])) {
    $nurse_id = $_GET['nurse_id'];
    $schedules = $report->getSchedulesByNurseId($nurse_id);
    echo json_encode($schedules); 
}
?>