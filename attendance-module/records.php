<div class="heading">
    <h1><i class="fas fa-lock" aria-hidden="true"></i>&nbspAdmins</h1>
    <a href="index.php?page=admins" class="right_button"><i class="fa fa-list-ol" aria-hidden="true"></i>&nbspAdmin List</a>
    <a href="index.php?page=admins&subpage=add" class="right_button"><i class="fa fa-plus"></i>&nbspAdd Admin</a>
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
            <th>Admin ID</th>
            <th>Username</th>
            <th>Name</th>
            <th>Email</th>
            <th>Contact No.</th>
            <th>Department</th>
            <th>Access</th>
        </tr>
    </thead>
    <tbody>
        <?php
        /* Display each admin record located in the database */
        if ($admin->list_admins() != false) {
            foreach ($admin->list_admins() as $value) {
                extract($value);
                // Create a link for each row using the emp_id
                $row_url = "index.php?page=admins&subpage=profile&id=" . $adm_id;
                ?>
                <tr onclick="location.href='<?php echo $row_url; ?>'" style="cursor: pointer;">
                    <td><?php echo $adm_id; ?></td>
                    <td><?php echo $adm_username; ?></td>
                    <td><?php echo $adm_lname . ', ' . $adm_fname . ' ' . $adm_mname; ?></td>
                    <td><?php echo $adm_email; ?></td>
                    <td><?php echo $adm_contact; ?></td>
                    <td><?php echo $admin->get_admin_department_name($adm_id); ?></td>
                    <td><?php echo $admin->get_admin_access_name($adm_id); ?></td>
                </tr>
                <?php
            }
        } else {
            ?>
            <tr>
                <td colspan="7">"No Record Found."</td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>
