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
include_once '../../classes/notifications.php';

$conn = Database::getInstance();
$notifications = new Notifications($conn);

$data = json_decode(file_get_contents("php://input"));

// Check if nurse_id is set in the GET request
if (isset($_GET['nurse_id'])) {
    $nurse_id = $_GET['nurse_id'];
    
    // Retrieve notifs for the specified nurse
    $data = $notifications->readNotifByNurseId($nurse_id);

    // Check if any notifs were found
    if ($data) {
        // Set response code to 200 OK
        http_response_code(200);
        echo json_encode($data); // Return the notifs as JSON
    } else {
        // Set response code to 404 Not Found
        http_response_code(404);
        echo json_encode(["message" => "No notifs found for the specified nurse."]);
    }
} else {
    // Set response code to 400 Bad Request
    http_response_code(400);
    echo json_encode(["message" => "Missing nurse_id parameter."]);
}
?>
