<div class="heading">
    <h1><i class="fas fa-solid fa-user-nurse"></i>&nbspEmployees</h1>
    <a href="index.php?page=employees&subpage=add" class="right_button"><i class="fa fa-plus"></i>&nbspAdd Employee</a>
</div>

<table id="tablerecords">   
    <thead>
        <tr>
            <th>Nurse ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Contact No.</th>
            <th>Department</th>
        </tr>
    </thead>
    <tbody>
        <?php
        /* Display each admin record located in the database */
        if ($employee->list_employees() != false) {
            foreach ($employee->list_employees() as $value) {
                extract($value);
                // Create a link for each row using the emp_id
                $row_url = "index.php?page=employees&subpage=profile&id=" . $emp_id;
                ?>
                <tr onclick="location.href='<?php echo $row_url; ?>'" style="cursor: pointer;">
                    <td><?php echo $emp_id; ?></td>
                    <td><?php echo $emp_lname . ', ' . $emp_fname . ' ' . $emp_mname; ?></td>
                    <td><?php echo $emp_email; ?></td>
                    <td><?php echo $emp_contact; ?></td>
                    <td><?php echo $emp_department; ?></td>
                </tr>
                <?php
            }
        } else {
            ?>
            <tr>
                <td colspan="5">"No Record Found."</td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>
