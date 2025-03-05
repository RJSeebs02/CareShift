<<<<<<< HEAD
<?php


$weekOffset = isset($_GET['weekOffset']) ? (int)$_GET['weekOffset'] : 0;
$weekDates = $schedule->getCurrentWeekDates($weekOffset);
$startOfWeek = new DateTime($weekDates[0]);
$monthStart = new DateTime($startOfWeek->format('Y-m-01'));
$weekOfMonth = ceil(($startOfWeek->format('d') + $monthStart->format('N') - 1) / 7);
$monthName = $startOfWeek->format('F');
?>

<div class="heading">
    <h1><i class="fas fa-solid fa-clock"></i>&nbsp;Schedule</h1>
    <a href="index.php?page=schedule" class="right_button"><i class="fa fa-list-ol" aria-hidden="true"></i>&nbspSchedules List</a>
    <a href="index.php?page=schedule&subpage=calendar" class="right_button"><i class="fa fa-calendar"></i>&nbspCalendar</a>
    
<?php if ($useraccess_id != 2 ): ?>
    <a href="index.php?page=schedule&subpage=add" class="right_button"><i class="fa fa-plus"></i>&nbspAdd Sched</a>
    <a href="index.php?page=schedule&subpage=generate" class="right_button"><i class="fa fa-plus"></i>&nbspGenerate</a>
<?php endif; ?>

</div>

<div class="schedule_table_header">
    <button class="nav-button" onclick="navigateWeek(<?php echo $weekOffset - 1; ?>)"><i class="fa-solid fa-left-long"></i></button>
    <h2>Week <?php echo $weekOfMonth; ?> of <?php echo $monthName; ?></h2>
    <button class="nav-button" onclick="navigateWeek(<?php echo $weekOffset + 1; ?>)"><i class="fa-solid fa-right-long"></i></button>
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
        if ($nurses) {
            foreach ($nurses as $value) {
                extract($value);
                $row_url = "index.php?page=nurses&subpage=profile&id=" . $nurse_id;
                ?>
                <tr onclick="location.href='<?php echo $row_url; ?>'" style="cursor: pointer;">
                    <td><?php echo $nurse_lname . ', ' . $nurse_fname . ' ' . $nurse_mname; ?></td>
                    <?php
                    foreach ($weekDates as $date) {
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
=======
<?php
$weekOffset = isset($_GET['weekOffset']) ? (int)$_GET['weekOffset'] : 0;
$weekDates = $attendance->getCurrentWeekDates($weekOffset);
$startOfWeek = new DateTime($weekDates[0]);
$monthStart = new DateTime($startOfWeek->format('Y-m-01'));
$weekOfMonth = ceil(($startOfWeek->format('d') + $monthStart->format('N') - 1) / 7);
$monthName = $startOfWeek->format('F');
?>

<div class="heading">
    <h1><i class="fas fa-solid fa-clock"></i>&nbsp;Schedule</h1>
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
	<i class="fa-solid fa-user-plus"></i>&nbspGenerate</a>
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
		<p>Blank - No Shift</p>
	</div>
</div>

<div class="schedule_table_header">
    <button class="nav-button" onclick="navigateWeek(<?php echo $weekOffset - 1; ?>)"><i class="fa-solid fa-left-long"></i></button>
    <h2>Week <?php echo $weekOfMonth; ?> of <?php echo $monthName . ' ' . $startOfWeek->format('Y'); ?></h2>
    <button class="nav-button" onclick="navigateWeek(<?php echo $weekOffset + 1; ?>)"><i class="fa-solid fa-right-long"></i></button>
</div>

<table id="tablerecords">
    <thead>
        <tr>
            <th>ID</th>
			<th>Nurse Name</th>
            <?php foreach ($weekDates as $date): ?>
                <th><?php echo (new DateTime($date))->format('d'); ?></th>
            <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($nurses) {
            foreach ($nurses as $value) {
                extract($value);
                $row_url = "index.php?page=schedule&subpage=profile&id=" . $nurse_id;
                ?>
                <tr onclick="location.href='<?php echo $row_url; ?>'" style="cursor: pointer;">
					<td><?php echo $nurse_id?></td>
                    <td><?php echo $nurse_lname . ', ' . $nurse_fname . ' ' . $nurse_mname; ?></td>
                    <?php
                    foreach ($weekDates as $date) {
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
</script>
>>>>>>> fa57ef9cad725f4de6f3f7079a21b6419102a7c6
