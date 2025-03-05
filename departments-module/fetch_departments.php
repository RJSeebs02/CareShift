<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(204);
    exit;
}

require_once '/home/careshift.helioho.st/httpdocs/config/connect.php';
require_once '/home/careshift.helioho.st/httpdocs/class/class.departments.php';

$conn = Database::getInstance();
$departments = new Departments($conn);

// Check if a specific department ID is provided
if (isset($_GET['department_id'])) {
    $department_id = $_GET['department_id'];
    
    // Ensure that department_id is an integer and prevent SQL injection
    if (!filter_var($department_id, FILTER_VALIDATE_INT)) {
        http_response_code(400);
        echo json_encode(array("message" => "Invalid department ID format."));
        exit;
    }

    // Fetch department by ID
    $departmentData = $departments->getDepartmentById($department_id);

    // Check if the department is found
    if ($departmentData) {
        http_response_code(200);
        echo json_encode($departmentData);
    } else {
        http_response_code(404);
        echo json_encode(array("message" => "No department found with ID: $department_id."));
    }
} else {
    // If no department_id is provided, fetch all departments
    $departments = $departments->getAllDepartments();

    // Check if departments are found
    if ($departments) {
        $departmentsResponse = array();
        $departmentsResponse["records"] = array();

        // Iterate through the departments and prepare the response
        foreach ($departments as $dept) {
            $departmentResponse = array(
                "department_id" => $dept['department_id'],
                "department_name" => $dept['department_name']
            );

            array_push($departmentsResponse["records"], $departmentResponse);
        }

        http_response_code(200);
        echo json_encode($departmentsResponse);
    } else {
        http_response_code(404);
        echo json_encode(array("message" => "No departments found."));
    }
}
?>
