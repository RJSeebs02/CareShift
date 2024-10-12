<div class="heading">
    <h1><i class="fas fa-lock" aria-hidden="true"></i>&nbspAdmins</h1>
    <a href="index.php?page=admins&subpage=add" class="right_button"><i class="fa fa-plus"></i>&nbspAdd Admin</a>
</div>

<table id="tablerecords">   
    <thead>
        <tr>
            <th>Admin ID</th>
            <th>Username</th>
            <th>Name</th>
            <th>Email</th>
            <th>Contact No.</th>
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
                    <td><?php echo $adm_access; ?></td>
                </tr>
                <?php
            }
        } else {
            ?>
            <tr>
                <td colspan="6">"No Record Found."</td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>
