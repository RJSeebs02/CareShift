<?php
include '../config/config.php';  
include '../class/class.nurse.php';

$department = isset($_GET['department']) ? $_GET['department'] : 'all';

try {
    $nurse = new Nurse($conn);

    $nurses = $nurse->fetchNursesWithDutiesForWeek($department);

    header('Content-Type: application/json');
    echo json_encode($nurses);

} catch (Exception $e) {
    http_response_code(500); 
    echo json_encode(['error' => $e->getMessage()]);
}
