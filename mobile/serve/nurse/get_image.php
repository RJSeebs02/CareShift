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

// Create a database connection
$conn = Database::getInstance();
$nurse = new Nurse($conn);

// Get nurse_id from query parameter
if (isset($_GET['nurse_id'])) {
    $nurse_id = htmlspecialchars(strip_tags($_GET['nurse_id']));

    // Set nurse properties
    $nurse->nurse_id = $nurse_id;

    // Fetch the nurse image from the database
    $nurseData = $nurse->getProfileImage();

    // Check if the nurse image data is available
    if ($nurseData && !empty($nurseData['nurse_img'])) {
        // Get the image URL from the database
        $imageUrl = $nurseData['nurse_img'];

        // Return the image URL as JSON response
        echo json_encode(['image_url' => $imageUrl]);
    } else {
        // Return error if no nurse found or image not set
        http_response_code(404);
        echo json_encode(array("message" => "Image not found or nurse not found."));
    }
} else {
    // Return error if nurse_id is not provided
    http_response_code(400);
    echo json_encode(array("message" => "Nurse ID is required."));
}
?>
