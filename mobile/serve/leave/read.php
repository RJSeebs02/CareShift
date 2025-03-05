<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(204);
    exit;
}

include_once '../../../config/connect.php'; 
include_once '../../classes/leave.php';

$conn = Database::getInstance();
$leave = new Leave($conn);

$data = json_decode(file_get_contents("php://input"));

// Check if nurse_id is provided
if (isset($_GET['nurse_id'])) {
    $nurse_id = $_GET['nurse_id'];
    $stmt = $leave->readByNurseId($nurse_id);

    // Prepare array for the results
    $leaves = array();
    $leaves["records"] = array();

    // Fetch results
    if ($stmt) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $leave_item = array(
                "leave_type" => $leave_type,
                "leave_desc" => $leave_desc,
                "leave_start_date" => $leave_start_date,
                "leave_end_date" => $leave_end_date,
                "leave_date_filed" => $leave_date_filed, 
                "leave_time_filed" => $leave_time_filed, 
                "leave_status" => $leave_status,
            );

            array_push($leaves["records"], $leave_item);
        }

        // Set response code to 200 OK
        http_response_code(200);
        echo json_encode($leaves);
    } else {
        // Set response code to 500 Internal Server Error
        http_response_code(500);
        echo json_encode(array("message" => "Unable to fetch leaves."));
    }
} else {
    // Set response code to 400 Bad Request
    http_response_code(400);
    echo json_encode(array("message" => "Nurse ID is required."));
}
?>
