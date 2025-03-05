<?php
// Allow from any origin
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: *"); // Allow all origins, or specify allowed ones like 'http://localhost:51760'
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Allow-Max-Age: 86400'); // cache for 1 day
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    exit(0);
}

// Get database connection
include_once '../../../config/connect.php';
include_once '../../classes/nurse.php';

$conn = Database::getInstance();
$nurse = new Nurse($conn);

// Check if nurse_id is provided via GET
$nurseId = $_GET['nurse_id'] ?? '';

if (!empty($nurseId)) {
    // Fetch nurse details based on nurse_id
    $data = $nurse->readSingle($nurseId);

    if ($data) {
        // Check if nurse_img is available and format it correctly
        if (isset($data['nurse_img']) && !empty($data['nurse_img'])) {
            // Check if it's a base64 image, else treat it as a URL
            if (strpos($data['nurse_img'], 'data:image') === false) {
                // If it's a URL, format it properly
                $data['nurse_img'] = "https://careshift.helioho.st/upload/" . $data['nurse_img'];
            } 
            // No need to prepend base64 data URI again if it's already base64
        }

        // Set response code - 200 OK
        http_response_code(200);
        echo json_encode($data); // Output the JSON if data exists
    } else {
        // Set response code - 404 Not found
        http_response_code(404);
        echo json_encode(array("message" => "No nurse found."));
    }
} else {
    // Set response code - 400 Bad request
    http_response_code(400);
    echo json_encode(array("message" => "Nurse ID is missing."));
}
?>
