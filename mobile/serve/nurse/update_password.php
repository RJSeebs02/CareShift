<?php
// Allow from any origin
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT");

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(204);
    exit;
}

include_once '../../../config/connect.php';
include_once '../../classes/nurse.php';

$conn = Database::getInstance();
$nurse = new Nurse($conn);

// Get the data from the incoming request
$data = json_decode(file_get_contents("php://input"));

// Check if the data is an object and required fields are not empty
if (
    is_object($data) &&
    !empty($data->nurse_id) && // Ensure nurse_id is provided for the update
    !empty($data->current_password) && // Ensure current password is provided
    !empty($data->new_password) && // Ensure new password is provided
    !empty($data->confirm_password) // Ensure confirmation password is provided
) {
    // Set nurse properties
    $nurse->nurse_id = htmlspecialchars(strip_tags($data->nurse_id));
    $nurse->nurse_password = htmlspecialchars(strip_tags($data->current_password)); // This will be the current password entered
    $newPassword = htmlspecialchars(strip_tags($data->new_password));
    $confirmPassword = htmlspecialchars(strip_tags($data->confirm_password));

    // Check if the new password matches the confirmation password
    if ($newPassword !== $confirmPassword) {
        http_response_code(400);
        echo json_encode(array("message" => "New password and confirmation password do not match."));
        exit;
    }

    // Validate the current password
    if (!$nurse->validatePassword($nurse->nurse_password)) {
        http_response_code(400);
        echo json_encode(array("message" => "Current password is incorrect."));
        exit;
    }

    // Set the new password (plain-text) to be updated
    $nurse->nurse_password = $newPassword;

    // Update the password in the database
    if ($nurse->updatePassword()) {
        http_response_code(200);
        echo json_encode(array("message" => "Password was updated."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to update password."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to update password. Data is incomplete."));
}
?>
