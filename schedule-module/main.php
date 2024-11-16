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
    <a href="index.php?page=schedule&subpage=calendar" class="right_button"><i class="fa fa-calendar"></i>&nbspCalendar</a>
    
<?php if ($useraccess_id != 2 ): ?>
    <a href="index.php?page=schedule&subpage=add" class="right_button"><i class="fa fa-plus"></i>&nbspAdd Sched</a>
    <a href="index.php?page=schedule&subpage=generate" class="right_button"><i class="fa fa-plus"></i>&nbspGenerate</a>
<?php endif; ?>

</div>

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