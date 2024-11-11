<?php
include '../config/config.php';  
include '../class/class.nurse.php';

header('Content-Type: application/json');

$department = isset($_GET['department']) ? $_GET['department'] : 'all';

$nurse = new Nurse();

if ($department) {
    try {
        $department = isset($_GET['department']) ? $_GET['department'] : 'all';
        if ($department === 'all') {
            $nurseCount = $nurse->countTotalNurses(); 
            echo json_encode(['available_nurses' => $nurseCount]);
        } else {
            $nurseCount = $nurse->countAvailableNursesByDepartment($department);
            echo json_encode(['available_nurses' => $nurseCount]);
        }
    } catch (Exception $e) {
        echo json_encode(['error' => 'Error fetching nurse data: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Invalid or missing department']);
}

exit();
?>