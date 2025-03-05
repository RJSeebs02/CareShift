<<<<<<< HEAD
<?php
include '../config/config.php';

$log = new Log();  
$logs = $log->list_logs();

if ($logs) {
    echo json_encode($logs);
} else {
    echo json_encode([]);
}
?>
=======
<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(204);
    exit;
}

require_once '/home/careshift.helioho.st/httpdocs/config/connect.php';
require_once '/home/careshift.helioho.st/httpdocs/class/class.logs.php';

$conn = Database::getInstance();
$log = new Log($conn);

// Check if nurse_id is provided
if (isset($_GET['nurse_id'])) {
    $nurse_id = $_GET['nurse_id'];
    
    // Ensure that nurse_id is an integer and prevent SQL injection
    if (!filter_var($nurse_id, FILTER_VALIDATE_INT)) {
        http_response_code(400);
        echo json_encode(array("message" => "Invalid nurse ID format."));
        exit;
    }
    
    $logs = $log->getLogsByNurse($nurse_id);

    // Check if logs are found
    if ($logs) {
        $logsResponse = array();
        $logsResponse["records"] = array();

        // Iterate through the logs and prepare the response
        foreach ($logs as $logItem) {
            $logResponse = array(
                "log_action" => $logItem['log_action'],
				"log_description" => $logItem['log_description'],
				"log_date_managed" => $logItem['log_date_managed'],
				"log_time_managed" => $logItem['log_time_managed'],
            );

            array_push($logsResponse["records"], $logResponse);
        }

        // Set response code to 200 OK
        http_response_code(200);
        echo json_encode($logsResponse);
    } else {
        // Set response code to 404 Not Found with detailed message
        http_response_code(404);
        echo json_encode(array("message" => "No logs found for nurse ID: $nurse_id."));
    }
} else {
    // Set response code to 400 Bad Request
    http_response_code(400);
    echo json_encode(array("message" => "Nurse ID is required."));
}
?>
>>>>>>> fa57ef9cad725f4de6f3f7079a21b6419102a7c6
