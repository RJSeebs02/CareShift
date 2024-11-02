<div class="heading">
    <h1><i class="fas fa-solid fa-users-line"></i>&nbspDepartments</h1>
    <a href="index.php?page=departments&subpage=add" class="right_button"><i class="fa fa-plus"></i>&nbspAdd Department</a>
</div>
<span class="right">
    <div class="search_bar">Search:<input type="text" id="search" name="search" onkeyup="">
    </div>
</span>

<table id="tablerecords">   
    <thead>
        <tr>
            <th>Department ID</th>
            <th>Department Name</th>
            <th>Description</th>
            <th>Department Type</th>
        </tr>
    </thead>
    <tbody>
        <?php
        /* Display each admin record located in the database */
        if ($departments->list_department() != false) {
            foreach ($departments->list_department() as $value) {
                extract($value);
                // Create a link for each row using the nurse_id
                $row_url = "index.php?page=departments&subpage=profile&id=" . $department_id;
                ?>
                <tr onclick="location.href='<?php echo $row_url; ?>'" style="cursor: pointer;">
                    <td><?php echo $department_id; ?></td>
                    <td><?php echo $department_name; ?></td>
                    <td><?php echo $department_desc; ?></td>
                    <td><?php echo $departments->get_department_dept_type_name($department_id); ?></td>
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
