<div class="heading">
    <h1><i class="fas fa-solid fa-receipt"></i>&nbspLogs</h1>
</div>
<span class="right">
    <div class="search_bar">Search:<input type="text" id="search" name="search" onkeyup="">
    </div>
</span>
    <table id="tablerecords">   
        <thead>
            <tr>
                <th>Date</th>
                <th>Time</th>
                <th>Actor</th>
                <th>Action</th>
                <th>Subject</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
        <?php
        /* Display each log record from the database */
        if ($logs = $log->list_logs()) {
            foreach ($logs as $value) {
                extract($value); // Extracts variables from the array
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($log_date_managed); ?></td>
                    <td><?php echo htmlspecialchars($log_time_managed); ?></td>
                    <td><?php echo htmlspecialchars($adm_fname . ' ' . $adm_lname); ?></td> <!-- Admin's full name -->
                    <td><?php echo htmlspecialchars($log_action); ?></td>
                    <td><?php echo isset($nurse_lname) ? htmlspecialchars($nurse_fname . ' ' . $nurse_lname) : 'N/A'; ?></td> 
                    <td><?php echo htmlspecialchars($log_description); ?></td>
                </tr>
                <?php
            }
        } else {
            ?>
            <tr>
                <td colspan="6">No Record Found.</td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
