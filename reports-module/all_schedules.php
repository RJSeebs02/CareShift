<<<<<<< HEAD
<?php
require_once 'class.report.php'; 
$report = new Report();

if (isset($_GET['nurse_id'])) {
    $nurse_id = $_GET['nurse_id'];
    $schedules = $report->getSchedulesByNurseId($nurse_id);
    echo json_encode($schedules); 
}
=======
<?php
require_once 'class/class.report.php'; 
$report = new Report();

if (isset($_GET['nurse_id'])) {
    $nurse_id = $_GET['nurse_id'];
    $schedules = $report->getSchedulesByNurseId($nurse_id);
    echo json_encode($schedules); 
}
>>>>>>> fa57ef9cad725f4de6f3f7079a21b6419102a7c6
?>