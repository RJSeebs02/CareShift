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

$data = json_decode(file_get_contents("php://input"));

// Check if the data is an object and required fields are not empty
if (
    is_object($data) &&
    !empty($data->nurse_id) && // Ensure `nurse_id` is provided for the update
    !empty($data->nurse_email) &&
    !empty($data->nurse_fname) &&
    !empty($data->nurse_mname) &&
    !empty($data->nurse_lname) &&
    !empty($data->nurse_contact)
) {
    // Set nurse property values
    $nurse->nurse_id = htmlspecialchars(strip_tags($data->nurse_id));
    $nurse->nurse_email = htmlspecialchars(strip_tags($data->nurse_email));
    $nurse->nurse_fname = htmlspecialchars(strip_tags($data->nurse_fname));
    $nurse->nurse_mname = htmlspecialchars(strip_tags($data->nurse_mname));
    $nurse->nurse_lname = htmlspecialchars(strip_tags($data->nurse_lname));
    $nurse->nurse_contact = htmlspecialchars(strip_tags($data->nurse_contact));

    // Update the nurse
    if ($nurse->update()) {
        http_response_code(200);
        echo json_encode(array("message" => "Account was updated."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to update account."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to update account. Data is incomplete."));
}
?>
