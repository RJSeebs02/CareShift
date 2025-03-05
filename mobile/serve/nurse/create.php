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
include_once '../../classes/nurse.php'; 

$conn = Database::getInstance();
$nurse = new Nurse($conn);

$data = json_decode(file_get_contents("php://input"));

// Check if the data is an object and required fields are not empty
if (
    is_object($data) &&
    !empty($data->nurse_email) &&
    !empty($data->nurse_password) &&
    !empty($data->nurse_fname) &&
    !empty($data->nurse_mname) &&
    !empty($data->nurse_lname) &&
    !empty($data->nurse_sex) &&
    !empty($data->nurse_contact) &&
    !empty($data->nurse_position) &&
    !empty($data->department_id)
) {
    // Create a new nurse object with the database connection
    $nurse = new Nurse($conn); // Pass the database connection

    // Set nurse property values
    $nurse->nurse_email = htmlspecialchars(strip_tags($data->nurse_email));
    $nurse->nurse_password = htmlspecialchars(strip_tags($data->nurse_password)); 
    $nurse->nurse_fname = htmlspecialchars(strip_tags($data->nurse_fname));
    $nurse->nurse_mname = htmlspecialchars(strip_tags($data->nurse_mname));
    $nurse->nurse_lname = htmlspecialchars(strip_tags($data->nurse_lname));
    $nurse->nurse_sex = htmlspecialchars(strip_tags($data->nurse_sex));
    $nurse->nurse_contact = htmlspecialchars(strip_tags($data->nurse_contact));
    $nurse->nurse_position = htmlspecialchars(strip_tags($data->nurse_position));
    $nurse->department_id = htmlspecialchars(strip_tags($data->department_id));

    // Create the nurse
    if ($nurse->create()) {
        http_response_code(201);
        echo json_encode(array("message" => "Account was created."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to create account."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to create account. Data is incomplete."));
}
?>
