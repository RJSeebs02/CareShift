<div class="heading">
    <h1><i class="fas fa-regular fa-paste"></i>&nbspLeave Applicants</h1>
    <a href="index.php?page=leave&subpage=new" class="right_button"><i class="fa fa-file-pen" aria-hidden="true"></i>&nbspApply Leave</a>
</div>

<table id="tablerecords">   
    <thead>
        <tr>
            <th>Leave ID</th>
            <th>Applicants</th>
            <th>Department</th>
            <th>Leave Type</th>
            <th>Leave Status</th>
        </tr>
    </thead>
    <tbody>
        <?php
        /* Display each leave application record located in the database */
        if ($leave->list_leave_applications() != false) {
            foreach ($leave->list_leave_applications() as $application) {
                extract($application); // Extract application fields into variables
                // Create a link for each row using the leave_id
                $row_url = "index.php?page=leave&subpage=details&id=" . $leave_id; 
                ?>
                <tr onclick="location.href='<?php echo $row_url; ?>'" style="cursor: pointer;">
                    <td><?php echo $leave_id; ?></td>
                    <td><?php echo $nurse_lname . ', ' . $nurse_fname; ?></td>
                    <td><?php echo $nurse_department; ?></td>
                    <td><?php echo $leave_type; ?></td>
                    <td><?php echo $leave_status; ?></td>
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
