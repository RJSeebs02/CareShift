<div class="heading">
    <h1><i class="fas fa-solid fa-chart-line"></i>&nbspReports</h1>
</div>

<table id="tablerecords">   
    <thead>
        <tr>
            <th>Nurse ID</th>
            <th>Name</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($report->generateReports() != false) {
            foreach ($report->generateReports() as $value) {
                extract($value);
                ?>
                <tr onclick="viewSchedules(<?php echo $nurse_id; ?>)" style="cursor: pointer;">
                    <td><?php echo htmlspecialchars($nurse_id); ?></td>
                    <td><?php echo htmlspecialchars($name); ?></td>
                    <td><?php echo htmlspecialchars($schedule_status); ?></td>
                </tr>
                <?php
            }
        } else {
            ?>
            <tr>
                <td colspan="4">No Record Found.</td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>

<!-- Modal or section for showing nurse schedules -->
<div id="scheduleModal" style="display:none;">
    <h2>Nurse Schedules</h2>
    <table id="scheduleTable">
        <thead>
            <tr>
                <th>Schedule ID</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Start Time</th>
                <th>End Time</th>
            </tr>
        </thead>
        <tbody>
            <!-- Schedule details will be dynamically injected here -->
        </tbody>
    </table>
    <button onclick="closeModal()">Close</button>
</div>