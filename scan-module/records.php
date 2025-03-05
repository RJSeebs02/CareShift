<?php
// Get the dayOffset from the URL (default is 0 for today)
$dayOffset = isset($_GET['dayOffset']) ? intval($_GET['dayOffset']) : 0;

// Calculate the selected date based on the day offset
$dayDate = date('Y-m-d', strtotime("$dayOffset days"));
$dayFormattedDate = date('F d, Y', strtotime($dayDate));
$dayOfWeek = date('l', strtotime($dayDate)); // Get the day of the week

// Fetch nurses scheduled for the selected day
$nurses = $attendance->get_nurse_id_by_schedule($dayDate); // Pass the date to the function
?>

<div class="heading">
    <h1><i class="fas fa-solid fa-list-ol"></i>&nbspAttendance List</h1>
    <a href="index.php?page=scan" class="right_button <?= $page == 'scan' && !isset($_GET['subpage']) ? 'active' : '' ?>">
		<i class="fas fa-solid fa-qrcode"></i></i>&nbspScan</a>
	<a href="index.php?page=scan&subpage=records" class="right_button <?= $page == 'scan' && $subpage == 'records' ? 'active' : '' ?>">
		<i class="fa fa-list-ol" aria-hidden="true"></i>&nbspAttendance</a>
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
    <button class="nav-button" onclick="navigateDay(<?php echo $dayOffset - 1; ?>)"><i class="fa-solid fa-left-long"></i></button>
    <h2><?php echo $dayFormattedDate . ' - ' . $dayOfWeek; ?></h2>
    <button class="nav-button" onclick="navigateDay(<?php echo $dayOffset + 1; ?>)"><i class="fa-solid fa-right-long"></i></button>
</div>

<table id="tablerecords">
    <thead>
        <tr>
            <th>Nurse Name</th>
            <th>Shift</th>
            <th>Status</th>
            <th>Check-In Time</th>
			<th>Check-Out Time</th>
			<th>Total Time</th>
        </tr>
    </thead>
		<tbody>
			<?php
			if (!empty($nurses)) {
			foreach ($nurses as $nurse) {
				// Extract nurse data
				$nurse_id = $nurse['nurse_id'];
				$nurse_name = $nurse['nurse_lname'] . ', ' . $nurse['nurse_fname'] . ' ' . $nurse['nurse_mname'];
				$row_url = "index.php?page=nurses&subpage=profile&id=" . $nurse_id;

				// Fetch shift code for the nurse
				$shiftCode = $schedule->get_shift_code($nurse_id, $dayDate);
				
				// Fetch shift total time for the nurse
				$shiftTotalTime = $attendance->calculate_total_time($nurse_id, $dayDate);

				// Check if a result is returned and get the formatted value
				$totalTimeFormatted = isset($shiftTotalTime['formatted']) ? $shiftTotalTime['formatted'] : 'N/A';


				// Initialize default status and times
				$status = "Not Checked In";
				$checkInTime = "N/A";
				$checkOutTime = "N/A"; // Ensure this is defined before the `if` block

				// Fetch attendance status from the attendance table
				$attendanceStatus = $attendance->check_attendance($nurse_id, $dayDate);

				// If attendance is found, update status and times
				if ($attendanceStatus) {
					$checkInTime = date('h:i A', strtotime($attendanceStatus['att_time']));
					$checkOutTime = !empty($attendanceStatus['att_out_time']) 
						? date('h:i A', strtotime($attendanceStatus['att_out_time'])) 
						: 'N/A';

					// Set the status to 'Off Duty' if the nurse has checked out, otherwise 'On Duty'
					if (!empty($attendanceStatus['att_out_time'])) {
						$status = "Checked Out";
					} else {
						$status = "Checked In";
					}
				} else {
					$status = "Not Checked In"; // Default status if no attendance found
				}

				// Determine class based on leave status
				$status_class = 'status-unknown'; // Fallback class
				if ($status === 'Checked In') {
					$status_class = 'status-on-duty';
				} elseif ($status === 'Checked Out') {
					$status_class = 'status-off-duty'; // New class for "Off Duty"
				} elseif ($status === 'Not Checked In') {
					$status_class = 'status-na';
				}
				?>
				<tr onclick="location.href='<?php echo htmlspecialchars($row_url); ?>'" style="cursor: pointer;">
					<td><?php echo htmlspecialchars($nurse_name); ?></td>
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
				<td colspan="5">No Record Found.</td> <!-- Adjusted colspan -->
			</tr>
			<?php
		}
			?>
		</tbody>
</table>



<script>
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('legendModal');
    const openModalButton = document.getElementById('openModalButton');
    const closeModalButton = modal.querySelector('.close-button');

    const toggleModalVisibility = (isVisible) => {
        modal.style.display = isVisible ? 'flex' : 'none';
    };

    openModalButton.addEventListener('click', () => toggleModalVisibility(true));
    closeModalButton.addEventListener('click', () => toggleModalVisibility(false));

    window.addEventListener('click', (event) => {
        if (event.target === modal) {
            toggleModalVisibility(false);
        }
    });
});

// Function to navigate to a different day
function navigateDay(dayOffset) {
    // Redirect to the same page with the selected day offset as a query parameter
    const url = new URL(window.location.href);
    url.searchParams.set('dayOffset', dayOffset);
    window.location.href = url.toString();
}
</script>