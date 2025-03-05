<<<<<<< HEAD
<div class="heading">
    <h1><i class="fas fa-solid fa-receipt"></i>&nbspLogs</h1>
</div>
<span class="right">
    <div class="search_bar">
        <label for="search">Search:</label>
        <input type="text" id="search" class="search" name="search" onkeyup="filterTable()">
    </div>
</span>
    <table id="tablerecords">   
        <thead>
            <tr>
                <th>Audit Log</th>
            </tr>
        </thead>
        <tbody>
        <?php
        /* Display each log record from the database */
        if ($log->list_logs() != false) {
            foreach ($log->list_logs() as $value) {
                extract($value);
                ?>
                <tr>
                <td>
                    <?php echo $log->get_adm_name($log_id) . '<br>';?>
                    <?php echo $log->get_desc($log_id) . '<br>';?>
                    <?php echo 'Date: '. $log->get_date($log_id) . '<br>';?>
                    <?php echo 'Time: '. $log->get_time($log_id)?>
                    </td>
                </tr>
                <?php
            }
        } else {
            ?>
            <tr>
                <td colspan="6">No Logs Found.</td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
=======
<div class="heading">
    <h1><i class="fas fa-solid fa-receipt"></i>&nbspLogs</h1>
</div>
<span class="right">
    <div class="search_bar">
        <label for="search">Search:</label>
        <input type="text" id="search" class="search" name="search" onkeyup="filterTable()">
    </div>
</span>
    <table id="tablerecords">   
        <thead>
            <tr>
                <th>Date</th>
				<th>Time</th>
				<th>User</th>
				<th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
        /* Display each log record from the database */
        if ($log->list_logs() != false) {
            foreach ($log->list_logs() as $value) {
                extract($value);
                ?>
                <tr>
                	<td>
						<?php echo $log->get_date($log_id);?>
					</td>
					<td>
						<?php echo $log->get_time($log_id)?>
                    </td>
					<td>
						<?php echo $log->get_adm_name($log_id);?>
                    </td>
					<td>
						<?php echo $log->get_desc($log_id);?>
					</td>
                </tr>
                <?php
            }
        } else {
            ?>
            <tr>
                <td colspan="6">No Logs Found.</td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
>>>>>>> fa57ef9cad725f4de6f3f7079a21b6419102a7c6
