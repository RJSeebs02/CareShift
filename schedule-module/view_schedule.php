<?php
// view_schedule.php

// Include the MySQLi connection from config.php
include '../config/config.php';  // Adjust the path if necessary

// Check if nurse_id is set in the URL
if (isset($_GET['nurse_id']) && !empty($_GET['nurse_id'])) {
    // Get the nurse ID from the URL
    $nurse_id = $_GET['nurse_id'];

    // Prepare the SQL query using MySQLi
    $query = "SELECT schedule_time FROM schedules WHERE nurse_id = ?";

    // Initialize a prepared statement
    if ($stmt = mysqli_prepare($con, $query)) {
        // Bind the nurse_id parameter
        mysqli_stmt_bind_param($stmt, "i", $nurse_id);
        
        // Execute the statement
        mysqli_stmt_execute($stmt);
        
        // Bind result variable
        mysqli_stmt_bind_result($stmt, $schedule_time);
        
        // Fetch the result
        if (mysqli_stmt_fetch($stmt)) {
            echo $schedule_time; // Return just the schedule time
        } else {
            echo "No schedule found.";
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        echo "Error: Could not prepare statement.";
    }

    // Close the database connection
    mysqli_close($con);
} else {
    echo "Invalid nurse ID.";
}
?>