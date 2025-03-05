<?php
include '../config/config.php';  
include '../class/class.nurse.php';

$selectedDate = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');  
$date = date('Y-m-d', strtotime($selectedDate));

try {
    $nurse = new Nurse($conn);

    $nurses = $nurse->fetchNursesForDay($selectedDate);

    header('Content-Type: application/json');
    echo json_encode($nurses);

} catch (Exception $e) {
    http_response_code(500); 
    echo json_encode(['error' => $e->getMessage()]);
}