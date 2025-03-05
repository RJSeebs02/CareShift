<<<<<<< HEAD
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
            $totalNurses = $nurse->countTotalNurses();
            echo json_encode(['available_nurses' => $nurseCount, 'total_nurses' => $totalNurses]);
        } else {
            $nurseCount = $nurse->countAvailableNursesByDepartment($department);
            $totalNurses = $nurse->countTotalNurses();
            echo json_encode(['available_nurses' => $nurseCount, 'total_nurses' => $totalNurses]);
        }
    } catch (Exception $e) {
        echo json_encode(['error' => 'Error fetching nurse data: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Invalid or missing department']);
}

exit();
?>
=======
<?php
include '../config/config.php';  
include '../class/class.nurse.php';

header('Content-Type: application/json');

$nurse = new Nurse($conn);
$response = [];

try {
    // Get department from the URL, default to 'all' if not provided
    $department = isset($_GET['department']) ? $_GET['department'] : 'all';

    // Debugging: log the department being passed
    error_log("Received department: " . $department);

    // Fetch total nurses (or implement department filtering if needed)
    $totalNurses = $nurse->countTotalNurses();

    if ($totalNurses === false) {
        throw new Exception("Failed to fetch total nurses count.");
    }

    // Return the total nurse count
    $response['total_nurses'] = $totalNurses;

    echo json_encode($response);
} catch (Exception $e) {
    // Log the error for debugging purposes
    error_log("Error fetching nurse data: " . $e->getMessage());
    
    // Return the error message as JSON
    echo json_encode(['error' => 'Error fetching nurse data: ' . $e->getMessage()]);
}

exit();
?>
>>>>>>> fa57ef9cad725f4de6f3f7079a21b6419102a7c6
