<?php
include '../config/config.php';  // Adjust path as necessary
include '../class/class.nurse.php';

header('Content-Type: application/json');

// Get the selected department (if any)
$department = isset($_GET['department']) ? $_GET['department'] : '';

$nurse = new Nurse();

if ($department) {
    try {
        if ($department === 'all') {
            // If 'all' is selected, count nurses for all departments
            $nurseCount = $nurse->countTotalNurses(); // Get total nurses from all departments
            echo json_encode(['available_nurses' => $nurseCount]);
        } else {
            // If a specific department is selected, count nurses in that department
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