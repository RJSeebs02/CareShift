<?php
// Allow from any origin
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(204);
    exit;
}

include_once '../../../config/connect.php'; 
include_once '../../classes/leave.php';

$conn = Database::getInstance();
$leave = new Leave($conn);

// Decode the incoming JSON data
$data = json_decode(file_get_contents("php://input"), true);

// Check for JSON decoding errors
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode(["message" => "Invalid JSON format."]);
    exit();
}

// Check for required fields
if (
    !empty($data['leave_type']) && 
    !empty($data['leave_desc']) && 
    !empty($data['leave_start']) && 
    !empty($data['leave_end']) && 
    !empty($data['nurse_id']) // Ensure nurse_id is provided
) {
    // Set leave properties
    $leave->leave_type = htmlspecialchars(strip_tags($data['leave_type']));
    $leave->leave_desc = htmlspecialchars(strip_tags($data['leave_desc']));
    $leave->leave_start_date = htmlspecialchars(strip_tags($data['leave_start']));
    $leave->leave_end_date = htmlspecialchars(strip_tags($data['leave_end']));
    $leave->leave_status = 'Pending'; // Default status
    $leave->nurse_id = htmlspecialchars(strip_tags($data['nurse_id']));

    // Set current date and time for filing
    $leave->leave_date_filed = date('Y-m-d');  // Current date
    $leave->leave_time_filed = date('H:i:s');  // Current time

    // Attempt to create the leave entry in the database
    if ($leave->create()) {
        http_response_code(200);
        echo json_encode(["message" => "Leave was created successfully."]);
    } else {
        http_response_code(503);
        echo json_encode(["message" => "Unable to create leave. Please try again later."]);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "Incomplete data. Please ensure all required fields are provided."]);
}
?>
