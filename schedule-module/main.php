<?php
// Get the week offset from the query parameter, default to 0
$weekOffset = isset($_GET['weekOffset']) ? (int)$_GET['weekOffset'] : 0;

// Fetch week dates based on the offset
$weekDates = $schedule->getCurrentWeekDates($weekOffset);

// Calculate the week number of the month
$startOfWeek = new DateTime($weekDates[0]); // First day of the week
$monthStart = new DateTime($startOfWeek->format('Y-m-01')); // First day of the current month
$weekOfMonth = ceil(($startOfWeek->format('d') + $monthStart->format('N') - 1) / 7); // Week number within the month

// Get the current month name (full month name)
$monthName = $startOfWeek->format('F'); // Full month name (e.g., November)

?>
    <div class="heading">
        <h1><i class="fas fa-solid fa-clock"></i>&nbsp;Schedule</h1>

        <a href="index.php?page=schedule&subpage=add" class="right_button"><i class="fa fa-plus"></i>&nbspAdd Sched</a>
        <a href="index.php?page=schedule&subpage=generate" class="right_button"><i class="fa fa-plus"></i>&nbspGenerate</a>

    </div>

<!-- Schedule Table -->
<div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
    <button class="nav-button" onclick="navigateWeek(<?php echo $weekOffset - 1; ?>)">Previous Week</button>
    <span>Week <?php echo $weekOfMonth; ?> of <?php echo $monthName; ?></span>
    <button class="nav-button" onclick="navigateWeek(<?php echo $weekOffset + 1; ?>)">Next Week</button>
</div>

<table id="tablerecords">
    <thead>
        <tr>
            <th>Nurse Name</th>
            <?php foreach ($weekDates as $date): ?>
                <th><?php echo $date; ?></th>
            <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($nurse->list_nurses() != false) {
            foreach ($nurse->list_nurses() as $value) {
                extract($value);
                $row_url = "index.php?page=nurses&subpage=profile&id=" . $nurse_id;
                ?>
                <tr onclick="location.href='<?php echo $row_url; ?>'" style="cursor: pointer;">
                    <td><?php echo $nurse_lname . ', ' . $nurse_fname . ' ' . $nurse_mname; ?></td>
                    <?php
                    // Loop through each date to check for the schedule and get the shift code
                    foreach ($weekDates as $date) {
                        // Call the function to get the shift code for this nurse on this date
                        $shiftCode = $schedule->get_shift_code($nurse_id, $date);
                        echo "<td>$shiftCode</td>";
                    }
                    ?>
                </tr>
                <?php
            }
        } else {
            ?>
            <tr>
                <td colspan="<?php echo count($weekDates) + 1; ?>">No Record Found.</td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>


</div>



<!-- View Schedule Modal -->
<div id="viewScheduleModal" class="modal">
    <div class="modal-content">
        <span class="close" id="viewScheduleClose">&times;</span>
        <h1><i class="fa fa-plus"></i>&nbsp;Shift Details</h1>
        <form action="processes/process.schedule.php?action=update" method="POST">

            <!-- Hidden input for sched_id -->
            <input type="hidden" id="eventSchedId" name="eventSchedId">

            <!-- Hidden input for nurse_id -->
            <input type="hidden" id="eventNurseId" name="eventNurseId">

            <label for="eventNurse">Nurse</label>
            <input type="text" id="eventNurse" name="eventNurse" readonly>
            
            <!-- Schedule Details -->
            <label for="eventPosition">Nurse Position</label>
            <input type="text" id="eventPosition" name="eventPosition" readonly>

            <label for="eventDepartment">Department</label>
            <input type="text" id="eventDepartment" name="eventDepartment" readonly>

            <label for="eventDate">Date</label>
            <input type="date" id="eventDate" name="eventDate" required />
            
            <label for="eventStart">Shift Start Time</label>
            <input type="time" id="eventStart" name="eventStart" required>

            <label for="eventEnd">Shift End Time:</label>
            <input type="time" id="eventEnd" name="eventEnd" required>

            <button type="submit" class="submit-btn">Update Schedule</button>
        </form>
    </div>
</div>
<div id="calendar">