<?php
// Get the week offset from URL or default to current week (0)
$weekOffset = isset($_GET['weekOffset']) ? (int)$_GET['weekOffset'] : 0;
$weekDates = $attendance->getCurrentWeekDates($weekOffset);

// Calculate the start of the week and the start of the month
$startOfWeek = new DateTime($weekDates[0]);
$monthStart = new DateTime($startOfWeek->format('Y-m-01'));

// Calculate the current week number in the month
$weekOfMonth = (int) ceil(($startOfWeek->format('d') + $monthStart->format('N') - 1) / 7);
$monthName = $startOfWeek->format('F');  // Month name
$weekDays = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

// Create an associative array to map each day to its corresponding date
$daysWithDates = [];
foreach ($weekDays as $dayName) {
    // Calculate the date for each day of the current week
    $dayDate = date('Y-m-d', strtotime("{$dayName} this week", strtotime($weekDates[0])));
    $daysWithDates[$dayName] = $dayDate;
}

$nurse_id = $nurse->get_id($id);

// Prepare the days array for the week
$days = [];
foreach ($weekDates as $date) {
    $days[] = ['date' => $date];
}

// Initialize arrays to store chart data
$daysForChart = [];
$totalHoursPerDay = [];

// Loop through each day in the week to gather total hours worked
foreach ($weekDates as $dayDate) {
    $dayName = date('l', strtotime($dayDate)); // Day name (e.g., Monday)
    $totalHours = $attendance->calculate_total_timechart($nurse_id, $dayDate); // Function to calculate total hours

    // Add the day and total hours to the chart data arrays
    $daysForChart[] = $dayName;
    $totalHoursPerDay[] = $totalHours;
}

?>

<div class="heading">
    <h1><i class="fas fa-solid fa-clock"></i>&nbsp<?php echo $nurse->get_fname($id).' '.$nurse->get_lname($id).'\'s ';?>Schedule</h1>

    <a href="index.php?page=schedule" class="right_button <?= $page == 'schedule' && !isset($_GET['subpage']) ? 'active' : '' ?>">
        <i class="fa fa-list-ol" aria-hidden="true"></i>&nbspSchedules List
    </a>
    <a href="index.php?page=schedule&subpage=calendar" class="right_button <?= $page == 'schedule' && $subpage == 'calendar' ? 'active' : '' ?>">
        <i class="fa fa-calendar"></i>&nbspCalendar
    </a>

<?php if ($useraccess_id != 2 ): ?>
    <a href="index.php?page=schedule&subpage=add" class="right_button <?= $page == 'schedule' && $subpage == 'add' ? 'active' : '' ?>">
        <i class="fa fa-plus"></i>&nbspAdd Sched
    </a>
    <a href="index.php?page=schedule&subpage=generate" class="right_button <?= $page == 'schedule' && $subpage == 'generate' ? 'active' : '' ?>">
        <i class="fa fa-plus"></i>&nbspGenerate</a>
<?php endif; ?>

</div>

<div class="search-bar-header">
    <span class="right">
        <div class="search_bar">
            <label for="search">Search:</label>
            <input type="text" id="search" class="search" name="search" onkeyup="filterTable()">
        </div>
    </span>
</div>

<div class="legend-header">
    <div class="legend-content">
        <h2>LEGEND: </h2>
        <p>A - Morning Shift</p>
        <p>P - Afternoon Shift</p>
        <p>G - Graveyard Shift</p>
        <p>L - On Leave</p>
    </div>
</div>

<div class="schedule_table_header">
    <button class="nav-button" onclick="navigateWeek(<?php echo $weekOffset - 1; ?>)"><i class="fa-solid fa-left-long"></i></button>
    <h2>Week <?php echo $weekOfMonth; ?> of <?php echo $monthName . ' ' . $startOfWeek->format('Y'); ?></h2>
    <button class="nav-button" onclick="navigateWeek(<?php echo $weekOffset + 1; ?>)"><i class="fa-solid fa-right-long"></i></button>
</div>

<div class="content_layer2_wrapper">
    <div id="attendance_report">
        <form id="departmentForm" method="GET">
            <label for="departmentSelectAttendance">Weekly Attendance Report</label>
        </form>
        <div class="charts-container">
            <canvas id="AttendanceDeptChart"></canvas> 
        </div>
    </div>
</div>

<table id="tablerecords">   
    <thead>
        <tr>
            <th>Day</th>
            <th>Date</th>
            <th>Shift</th>
            <th>Status</th>
            <th>Check-In Time</th>
            <th>Check-Out Time</th>
            <th>Total Time</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Check if the days array is not empty
        if (!empty($days)) {
            foreach ($days as $day) {
                // Extract day data
                $dayDate = $day['date'];
                $dayName = date('l', strtotime($dayDate)); // Get the day name (e.g., Monday)

                // Fetch shift code for the day
                $shiftCode = $schedule->get_shift_code($nurse_id, $dayDate) ?: 'No Shift';
                
                // Fetch shift total time for the nurse
                $shiftTotalTime = $attendance->calculate_total_time($nurse_id, $dayDate);

                // Check if a result is returned and get the formatted value
                $totalTimeFormatted = isset($shiftTotalTime['formatted']) ? $shiftTotalTime['formatted'] : 'N/A';

                // Initialize default status and times
                $status = "Not Logged In";
                $checkInTime = "N/A";
                $checkOutTime = "N/A";

                // Fetch attendance status for the day
                $attendanceStatus = $attendance->check_attendance($nurse_id, $dayDate);

                // If attendance is found, update status and times
                if ($attendanceStatus) {
                    $checkInTime = date('h:i A', strtotime($attendanceStatus['att_time']));
                    $checkOutTime = !empty($attendanceStatus['att_out_time']) 
                        ? date('h:i A', strtotime($attendanceStatus['att_out_time'])) 
                        : 'N/A';

                    // Set the status to 'Completed' if check-out time exists, otherwise 'Ongoing'
                    if (!empty($attendanceStatus['att_out_time'])) {
                        $status = "Logged Out";
                    } else {
                        $status = "Logged In";
                    }
                }

                // Determine class based on status
                $status_class = 'status-unknown'; // Fallback class
                if ($status === 'Logged Out') {
                    $status_class = 'status-completed';
                } elseif ($status === 'Logged In') {
                    $status_class = 'status-ongoing';
                } elseif ($status === 'Not Logged In') {    
                    $status_class = 'status-not-available';
                }
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($dayName); ?></td>
                    <td><?php echo htmlspecialchars(date('F j', strtotime($dayDate))); ?></td>
                    <td><?php echo htmlspecialchars($shiftCode); ?></td>
                    <td><p class="<?php echo $status_class; ?>"><?php echo htmlspecialchars($status); ?></p></td>
                    <td><?php echo htmlspecialchars($checkInTime); ?></td>
                    <td><?php echo htmlspecialchars($checkOutTime); ?></td>
                    <td><?php echo htmlspecialchars($totalTimeFormatted); ?></td>
                </tr>
                <?php
            }
        } else {
            ?>
            <tr>
                <td colspan="5">No Record Found.</td>
            </tr>
            <?php
        }
        ?>
    </tbody>
</table>

<script>
    // Line chart data for total hours worked
    const attendanceData = {
        labels: <?php echo json_encode($daysForChart); ?>,  // Days of the week
        datasets: [{
            label: 'Total Hours Worked',
            data: <?php echo json_encode($totalHoursPerDay); ?>,  // Total hours for each day
            borderColor: 'rgb(75, 192, 192)',  // Line color (teal)
            backgroundColor: 'rgba(75, 192, 192, 0.2)',  // Light teal background
            fill: true,  // Fill the area under the line
            tension: 0.1  // Smoothness of the line
        }]
    };

    // Line chart configuration for Total Hours Worked
    const attendanceConfig = {
        type: 'line',  // Line chart type
        data: attendanceData,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
            },
            scales: {
                y: {
                    beginAtZero: true,  // Ensure y-axis starts at zero
                    title: {
                        display: true,
                        text: 'Total Hours Worked'
                    }
                }
            }
        }
    };

    // Render the chart
    var AttendanceDeptChart = new Chart(
        document.getElementById('AttendanceDeptChart'),
        attendanceConfig
    );
</script>
