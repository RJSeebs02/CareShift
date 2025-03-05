<?php
include '../config/config.php';  
include '../class/class.nurse.php';

header('Content-Type: application/json');

$nurse = new Nurse($conn);
$response = [];

try {
    // Get department from the URL, default to 'all' if not provided
    $department = isset($_GET['department']) ? $_GET['department'] : 'all';
    // Get date range (optional)
    $startDate = isset($_GET['start_date']) ? $_GET['start_date'] : null;
    $endDate = isset($_GET['end_date']) ? $_GET['end_date'] : null;

    // Debugging: log the department and date range being passed
    error_log("Received department: " . $department);
    error_log("Received date range: " . $startDate . " to " . $endDate);

    // Fetch attendance data
    if ($department == 'all') {
        // Fetch all attendance records
        $attendanceData = $nurse->fetchAllAttendance($startDate, $endDate);
    } else {
        // Fetch attendance for a specific department
        $attendanceData = $nurse->fetchAttendanceByDepartment($department, $startDate, $endDate);
    }

    if ($attendanceData === false) {
        throw new Exception("Failed to fetch attendance data.");
    }

    // Add the attendance data to the response
    $response['attendance'] = $attendanceData;

    // Return the data as JSON
    echo json_encode($response);
} catch (Exception $e) {
    // Log the error for debugging purposes
    error_log("Error fetching attendance data: " . $e->getMessage());
    
    // Return the error message as JSON
    echo json_encode(['error' => 'Error fetching attendance data: ' . $e->getMessage()]);
}

exit();
?>
