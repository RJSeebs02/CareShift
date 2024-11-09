<?php
include '../config/config.php';

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the nurse_id from the GET request (default to 'all')
$nurse_id = isset($_GET['nurse_id']) ? $_GET['nurse_id'] : 'all';

// Prepare the base query
$query = "
    SELECT s.nurse_id, s.sched_date, s.sched_start_time, s.sched_end_time, n.nurse_lname, n.nurse_position
    FROM schedule s
    JOIN nurse n ON s.nurse_id = n.nurse_id
";

// If a specific nurse is selected, modify the query using a prepared statement
if ($nurse_id !== 'all') {
    $query .= " WHERE s.nurse_id = ?";
}

$stmt = mysqli_prepare($con, $query);

// If a specific nurse is selected, bind the parameter
if ($nurse_id !== 'all') {
    mysqli_stmt_bind_param($stmt, 's', $nurse_id);
}

// Execute the statement
mysqli_stmt_execute($stmt);

// Get the result
$result = mysqli_stmt_get_result($stmt);

$events = [];

// Fetch events and format them
while ($row = mysqli_fetch_assoc($result)) {
    // Format the start time
    $start_time = $row['sched_date'] . 'T' . $row['sched_start_time'];
    
    // Check if the shift ends after midnight
    if ($row['sched_end_time'] < $row['sched_start_time']) {
        $end_date = new DateTime($row['sched_date']);
        $end_date->modify('+1 day');
        $end_time = $end_date->format('Y-m-d') . 'T' . $row['sched_end_time'];
    } else {
        $end_time = $row['sched_date'] . 'T' . $row['sched_end_time'];
    }

    $nurse_name = htmlspecialchars($row['nurse_lname'] . ', ' . $row['nurse_position']); // Encode to prevent XSS

    $events[] = [
        'title' => $nurse_name,
        'start' => $start_time,
        'end' => $end_time,
        'allDay' => false,
    ];
}

// Send the response as JSON
echo json_encode($events);

// Close the connection
mysqli_close($con);
?>