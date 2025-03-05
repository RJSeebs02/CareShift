<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: POST, OPTIONS");

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

// Ensure required parameters
if (!isset($_POST['nurse_id']) || !isset($_FILES['nurse_img'])) {
    echo json_encode(["error" => "Required parameters are missing."]);
    exit;
}

$nurseId = intval($_POST['nurse_id']); // Ensure nurse_id is treated as an integer
$uploadDir = '../../../upload/'; // Adjust path as necessary
$response = ['success' => false];

// Check directory permissions
if (!is_writable($uploadDir)) {
    echo json_encode(['success' => false, 'message' => 'Directory is not writable.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fileTmpPath = $_FILES['nurse_img']['tmp_name'];
    $fileName = basename($_FILES['nurse_img']['name']);
    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

    // Ensure unique file name
    $newFileName = "profile_$nurseId." . $fileExtension;
    $uploadFilePath = $uploadDir . $newFileName;

    // Move the uploaded file
    if (move_uploaded_file($fileTmpPath, $uploadFilePath)) {
        // Save URL to the database
        $fileUrl = "https://careshift.helioho.st/upload/" . $newFileName; // Adjust domain accordingly

        // Save URL to the database using PDO syntax
        $sql = "UPDATE nurse SET nurse_img = :nurse_img WHERE nurse_id = :nurse_id";
        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
            // Bind parameters
            $stmt->bindParam(':nurse_img', $fileUrl, PDO::PARAM_STR);
            $stmt->bindParam(':nurse_id', $nurseId, PDO::PARAM_INT);
            
            // Execute query
            if ($stmt->execute()) {
                $response['success'] = true;
                $response['message'] = 'Profile picture uploaded and saved to database successfully.';
                $response['nurse_img'] = $fileUrl;
            } else {
                $response['message'] = 'Failed to save image URL to the database.';
            }
        } else {
            $response['message'] = 'Database query preparation failed.';
        }
    } else {
        $response['message'] = 'Failed to move uploaded file.';
    }
} else {
    $response['message'] = 'Invalid request method.';
}

echo json_encode($response);
?>
