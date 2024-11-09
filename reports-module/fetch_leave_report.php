<?php
include '../config/config.php';  
include '../class/class.leave.php'; 

header('Content-Type: application/json');

$leave = new Leave(); 

$response = [];

try {
    $leaveCount = $leave->countPendingLeaves(); 
    $response['pending_leaves'] = $leaveCount;
    
    echo json_encode($response);
} catch (Exception $e) {
    echo json_encode(['error' => 'Error fetching leave data: ' . $e->getMessage()]);
}

exit();
?>