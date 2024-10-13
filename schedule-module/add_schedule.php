<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Schedule</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Create Nurse Schedule</h1>
        <form method="post" action="schedule-module/generate_schedule.php">
            <label for="emp_id">Select Nurse:</label>
            <select name="emp_id" required>
                <?php
                if (!$con) {
                    die("Connection failed: " . mysqli_connect_error());
                }

                // Query to select nurses from the employee table
                $query = "SELECT emp_id, CONCAT(emp_fname, ' ', emp_lname) AS name FROM employee";
                $result = mysqli_query($con, $query); // Use $con (not $conn)

                // Check if the query returned any results
                if ($result && mysqli_num_rows($result) > 0) {
                    // Loop through the results and display them as options in the dropdown
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='{$row['emp_id']}'>{$row['name']}</option>";
                    }
                } else {
                    echo "<option>No nurses found</option>";
                }
                ?>
            </select>

            <!-- Date Selection -->
            <label for="sched_date">Select Date:</label>
            <input type="date" name="sched_date" required>

            <!-- Start Time Selection -->
            <label for="sched_start_time">Start Time:</label>
            <input type="time" name="sched_start_time" required>

            <!-- End Time Selection -->
            <label for="sched_end_time">End Time:</label>
            <input type="time" name="sched_end_time" required>

            <!-- Submit Button -->
            <button type="submit">Generate Schedule</button>
        </form>
    </div>
</body>
</html>
