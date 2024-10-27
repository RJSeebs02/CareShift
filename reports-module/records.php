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
                <tr>
                    <td><?php echo htmlspecialchars($nurse_id); ?></td>
                    <td><?php echo htmlspecialchars($name); ?></td>
                    <td><?php echo htmlspecialchars($schedule_status); ?></td>
                </tr>
                <?php
            }
        } else {
            ?>
            <tr>
                <td colspan="3">No Record Found.</td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>
