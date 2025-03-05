<?php 
  // Query to fetch nurse count, leave count, and attendance count
$query = $conn->query("
    SELECT 
        d.department_name, 
        COUNT(DISTINCT n.nurse_id) AS nurse_count, 
        COUNT(DISTINCT l.leave_id) AS leave_count,
        COUNT(DISTINCT a.att_id) AS attendance_count
    FROM department d
    LEFT JOIN nurse n ON d.department_id = n.department_id
    LEFT JOIN `leave` l ON n.nurse_id = l.nurse_id
    LEFT JOIN attendance a ON n.nurse_id = a.nurse_id 
        AND DATE(CONVERT_TZ(a.att_date, '+00:00', '+08:00')) = DATE(CONVERT_TZ(NOW(), '+00:00', '+08:00'))
    GROUP BY d.department_name;
");

if (!$query) {
    die("Query failed: " . $conn->error);  
}

  // Initialize arrays to store data
  $nursecount = [];
  $leavecount = [];
  $attendancecount = []; 
  $department = [];

  // Fetch the data
  while ($data = $query->fetch_assoc()) {
    $nursecount[] = $data['nurse_count'];
    $leavecount[] = $data['leave_count'];
    $attendancecount[] = $data['attendance_count']; 
    $department[] = $data['department_name'];
  }

  // Fetch Weekly Nurse Data from `fetch_nurse_weekly.php`
  $weeklyData = file_get_contents("https://careshift.helioho.st/reports-module/fetch_nurse_weekly.php");
  $weeklyNurses = json_decode($weeklyData, true); 

?>

<div class="content_wrapper">
    <div class="heading">
        <h1><i class="fas fa-solid fa-chart-line"></i>&nbspReports</h1>
    </div>

    <div class="content_layer1_wrapper">
        <div id="nurses_report">
            <form id="departmentForm" method="GET">
                <label for="departmentSelectNurse">Nurse Count</label>
                <p>Available Nurses: <span id="nurse-count"></span></p>  
            </form>
            <div class="charts-container">
                <canvas id="NurseCountDeptChart"></canvas>
            </div>
        </div>
        <div id="leaves_report">
            <form id="departmentForm" method="GET">
                <label for="departmentSelectLeave">Nurse Leaves Count</label>
                <p>Total Leaves: <span id="leave-count"></span></p>
            </form>
            <div class="charts-container">
                <canvas id="LeaveCountDeptChart"></canvas> 
            </div>
        </div>
    </div>
    <div class="content_layer2_wrapper">
        <div id="attendance_report">
            <form id="departmentForm" method="GET">
                <label for="departmentSelectAttendance">Nurse Attendance</label>
            </form>
            <div class="charts-container">
                <canvas id="AttendanceDeptChart"></canvas> 
            </div>
        </div>
    </div>
    
	<div class="content_layer3_wrapper">
		<div id="weekly-nurse-report">
			<h2>Weekly Nurse Assignment</h2>
			
			<div class="schedule_table_header">
				<button id="prev-day-btn"><i class="fa-solid fa-left-long"></i></button>
				<h2 id="current-date"><?php echo date('l, F j, Y'); ?></h2> <!-- Display current date in desired format -->
				<button id="next-day-btn"><i class="fa-solid fa-right-long"></i></button>
			</div>
			

			<!-- Arrow button to show next day's schedule -->
			
			

			<table id="tablerecordsreport">   
				<thead>
					<tr>
						<th>Nurse Name</th>
						<th>Department</th>
					</tr>
				</thead>
				<tbody id="nurse-list">
					<?php 
					// Get the current date (today) in 'YYYY-MM-DD' format for comparison
					$currentDate = date('Y-m-d'); 

					// Filter nurses for today or later
					$todayNurses = [];
					foreach ($weeklyNurses as $nurse) {
						// Check if the nurse's scheduled date is today or later
						if ($nurse['sched_date'] >= $currentDate) {
							$todayNurses[] = $nurse;
						}
					}

					// Check if there are any nurses scheduled for today or later
					if (!empty($todayNurses)) {
						// Loop through and display nurses
						foreach ($todayNurses as $nurse) {
							echo "<tr>";
							echo "<td>{$nurse['nurse_fname']} {$nurse['nurse_lname']}</td>";
							echo "<td>{$nurse['department_name']}</td>";
							echo "</tr>";
						}
					} else {
						echo "<tr><td colspan='2'>No nurses are scheduled for today or later.</td></tr>";
					}
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<script>
    // Pie chart data for nurse count
    const data = {
        labels: <?php echo json_encode($department); ?>,  // Department names
        datasets: [{
            label: 'Nurse Count',
            data: <?php echo json_encode($nursecount); ?>,  // Nurse count data
            backgroundColor: [
                'rgb(255, 99, 132)',    // Red
                'rgb(54, 162, 235)',    // Blue
                'rgb(255, 205, 86)',    // Yellow
                'rgb(75, 192, 192)',    // Teal
                'rgb(153, 102, 255)',   // Purple
                'rgb(255, 99, 71)',     // Tomato
                'rgb(32, 178, 170)',    // Sea Green
                'rgb(255, 69, 0)',      // Red Orange
                'rgb(0, 255, 127)',     // Spring Green
                'rgb(255, 165, 0)',     // Orange
                'rgb(255, 105, 180)',   // Hot Pink
                'rgb(0, 255, 255)',     // Cyan
                'rgb(255, 20, 147)',    // Deep Pink
            ],
            hoverOffset: 4
        }]
    };

    // Pie chart data for leave count
    const leaveData = {
        labels: <?php echo json_encode($department); ?>, // Department names
        datasets: [{
            label: 'Leave Count',
            data: <?php echo json_encode($leavecount); ?>, // Leave count data
            backgroundColor: [
                'rgb(255, 99, 132)',    // Red
                'rgb(54, 162, 235)',    // Blue
                'rgb(255, 205, 86)',    // Yellow
                'rgb(75, 192, 192)',    // Teal
                'rgb(153, 102, 255)',   // Purple
                'rgb(255, 99, 71)',     // Tomato
                'rgb(32, 178, 170)',    // Sea Green
                'rgb(255, 69, 0)',      // Red Orange
                'rgb(0, 255, 127)',     // Spring Green
                'rgb(255, 165, 0)',     // Orange
                'rgb(255, 105, 180)',   // Hot Pink
                'rgb(0, 255, 255)',     // Cyan
                'rgb(255, 20, 147)',    // Deep Pink
            ],
            hoverOffset: 4
        }]
    };

    // Line chart data for attendance count
    const attendanceData = {
        labels: <?php echo json_encode($department); ?>,  // Department names
        datasets: [{
            label: 'Attendance Count',
            data: <?php echo json_encode($attendancecount); ?>,  // Attendance count data
            borderColor: 'rgb(75, 192, 192)',  // Line color (teal)
            backgroundColor: 'rgba(75, 192, 192, 0.2)',  // Light teal background for the line
            fill: true,  // Filling the area under the line
            tension: 0.1  // Smoothness of the line
        }]
    };

    // Chart configuration for Nurse Count
    const config = {
        type: 'pie',
        data: data,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'left',
                },
            }
        }
    };

    // Pie chart configuration for Leave Count
    const leaveConfig = {
        type: 'pie',
        data: leaveData,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'left',
                },
            }
        }
    };

    // Line chart configuration for Attendance
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
						text: 'Nurses Present'
					},
					ticks: {
						// Eliminate decimals by converting the value to an integer (truncate the decimals)
						callback: function(value) {
							return parseInt(value);  // Remove decimals by truncating the value
						}
					}
				}
			}
		}
	};



    // Create the charts
    var NurseCountDeptChart = new Chart(
        document.getElementById('NurseCountDeptChart'),
        config
    );

    var LeaveCountDeptChart = new Chart(
        document.getElementById('LeaveCountDeptChart'),
        leaveConfig
    );

    var AttendanceDeptChart = new Chart(
        document.getElementById('AttendanceDeptChart'),
        attendanceConfig
    );

$(document).ready(function() {
    // Initialize the current date as a Date object
    let currentDateObj = new Date();

    // Function to update the displayed date
    function updateDateDisplay() {
        // Format the current date as 'l, F j, Y' (e.g., "Sunday, January 12, 2025")
        let formattedDate = currentDateObj.toLocaleDateString('en-US', {
            weekday: 'long', // 'Sunday'
            year: 'numeric', // '2025'
            month: 'long', // 'January'
            day: 'numeric' // '12'
        });

        // Update the current date display
        $('#current-date').text(formattedDate);
    }

    // Function to fetch nurses for the current date
    function fetchNursesForDay() {
        // Fetch nurses for the current day
        $.ajax({
            url: 'reports-module/fetch_nurses_for_day.php',
            method: 'GET',
            data: {
                date: currentDateObj.toISOString().split('T')[0] // Send the current date in ISO format
            },
            success: function(data) {
                let nurseList = '';
                
                if (data.length > 0) {
                    // Populate the table with the nurses for the current date
                    data.forEach(function(nurse) {
                        nurseList += `<tr><td>${nurse.nurse_fname} ${nurse.nurse_lname}</td><td>${nurse.department_name}</td></tr>`;
                    });
                } else {
                    nurseList = "<tr><td colspan='2'>No nurses are scheduled for this day.</td></tr>";
                }

                // Update the nurse list table
                $('#nurse-list').html(nurseList);
            },
            error: function() {
                alert('Error fetching data.');
            }
        });
    }

    // Click event for the next-day button
    $('#next-day-btn').click(function() {
        // Move the current date forward by 1 day
        currentDateObj.setDate(currentDateObj.getDate() + 1);

        // Update the displayed date
        updateDateDisplay();

        // Fetch the next day's nurses
        fetchNursesForDay();
    });

    // Click event for the previous-day button
    $('#prev-day-btn').click(function() {
        // Move the current date backward by 1 day
        currentDateObj.setDate(currentDateObj.getDate() - 1);

        // Update the displayed date
        updateDateDisplay();

        // Fetch the previous day's nurses
        fetchNursesForDay();
    });

    // Initialize with the current date on page load
    updateDateDisplay();
    fetchNursesForDay();
});

</script>
