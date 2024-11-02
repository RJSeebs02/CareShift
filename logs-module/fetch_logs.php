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
