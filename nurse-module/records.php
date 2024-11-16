<?php
$admin_id = $admin->get_id_by_username($_SESSION['adm_username']);
$access_id = $admin->get_access_id($admin_id);
$scheduler_department_id = $admin->get_department_id($admin_id);

if ($access_id == 3) { 
    $nurses = $nurse->list_nurses_by_department($scheduler_department_id);
} else {
    $nurses = $nurse->list_nurses(); 
}
?>

<div class="heading">
    <h1><i class="fas fa-solid fa-user-nurse"></i>&nbspNurses</h1>

    <?php if ($useraccess_id != 3): ?>
    <a href="index.php?page=nurses" class="right_button"><i class="fa fa-list-ol" aria-hidden="true"></i>&nbspNurse List</a>
    <a href="index.php?page=nurses&subpage=add" class="right_button"><i class="fa fa-plus"></i>&nbspAdd Nurse</a>
    <?php endif; ?>
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
            <th>Nurse ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Contact No.</th>
            <th>Department</th>
            <th>Position</th>
        </tr>
    </thead>
    <tbody>
        <?php
        /* Display nurses based on the department filter for Scheduler */
        if ($nurses) {
            foreach ($nurses as $value) {
                extract($value);
                // Create a link for each row using the nurse_id
                $row_url = "index.php?page=nurses&subpage=profile&id=" . $nurse_id;
                ?>
                <tr onclick="location.href='<?php echo $row_url; ?>'" style="cursor: pointer;">
                    <td><?php echo $nurse_id; ?></td>
                    <td><?php echo $nurse_lname . ', ' . $nurse_fname . ' ' . $nurse_mname; ?></td>
                    <td><?php echo $nurse_email; ?></td>
                    <td><?php echo $nurse_contact; ?></td>
                    <td><?php echo $nurse->get_nurse_department_name($nurse_id); ?></td>
                    <td><?php echo $nurse_position; ?></td>
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