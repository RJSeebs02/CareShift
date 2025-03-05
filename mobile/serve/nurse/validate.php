<?php
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');
}

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    exit(0);
}

include_once '../../../config/connect.php';
include_once '../../classes/nurse.php';

$conn = Database::getInstance();
$nurse = new Nurse($conn);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->nurse_email) && !empty($data->nurse_password)) {
    $nurse->nurse_email = htmlspecialchars(strip_tags($data->nurse_email));
    $nurse->nurse_password = htmlspecialchars(strip_tags($data->nurse_password));

    $nurseDetails = $nurse->getNurseByEmail($nurse->nurse_email);

    if ($nurseDetails) {
        if ($nurse->nurse_password === $nurseDetails['nurse_password']) {
            http_response_code(200);
            echo json_encode(array(
                "message" => "Account is valid.",
                "validation" => true,
                "nurse_id" => $nurseDetails['nurse_id']
            ));
        } else {
            http_response_code(403);
            echo json_encode(array("message" => "Invalid password.", "validation" => false));
        }
    } else {
        http_response_code(404);
        echo json_encode(array("message" => "Nurse details not found."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Unable to validate account. Data is incomplete."));
}
?>
