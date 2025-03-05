<<<<<<< HEAD
<?php
include '../config/config.php';  
include '../class/class.leave.php'; 

header('Content-Type: application/json');

$leave = new Leave(); 
$response = [];

try {
    $department = isset($_GET['department']) ? $_GET['department'] : '';
    if ($department === 'all') {
        $leaveCount = $leave->countTotalPendingLeaves();
        $response['pending_leaves'] = $leaveCount;
    } else {
        $departmentLeaves = $leave->countPendingLeavesByDepartment($department);
        $response['pending_leaves'] = $departmentLeaves ? $departmentLeaves[0]['count'] : 0;
    }

    echo json_encode($response);
} catch (Exception $e) {
    echo json_encode(['error' => 'Error fetching leave data: ' . $e->getMessage()]);
}

exit();
=======
<?php
include '../config/config.php';  
include '../class/class.leave.php'; 

header('Content-Type: application/json');

$leave = new Leave(); 
$response = [];

try {
    $department = isset($_GET['department']) ? $_GET['department'] : '';
    $leaveCount = $leave->countTotalLeaves();
    $response['pending_leaves'] = $leaveCount;

    echo json_encode($response);
} catch (Exception $e) {
    echo json_encode(['error' => 'Error fetching leave data: ' . $e->getMessage()]);
}

exit();
>>>>>>> fa57ef9cad725f4de6f3f7079a21b6419102a7c6
?>