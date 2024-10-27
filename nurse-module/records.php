<div class="heading">
    <h1><i class="fas fa-solid fa-user-nurse"></i>&nbspNurses</h1>
    <a href="index.php?page=nurses&subpage=add" class="right_button"><i class="fa fa-plus"></i>&nbspAdd Nurse</a>
</div>
<span class="right">
    <div class="search_bar">Search:<input type="text" id="search" name="search" onkeyup="">
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
        </tr>
    </thead>
    <tbody>
        <?php
        /* Display each admin record located in the database */
        if ($nurse->list_nurses() != false) {
            foreach ($nurse->list_nurses() as $value) {
                extract($value);
                // Create a link for each row using the nurse_id
                $row_url = "index.php?page=nurses&subpage=profile&id=" . $nurse_id;
                ?>
                <tr onclick="location.href='<?php echo $row_url; ?>'" style="cursor: pointer;">
                    <td><?php echo $nurse_id; ?></td>
                    <td><?php echo $nurse_lname . ', ' . $nurse_fname . ' ' . $nurse_mname; ?></td>
                    <td><?php echo $nurse_email; ?></td>
                    <td><?php echo $nurse_contact; ?></td>
                    <td><?php echo $nurse_department; ?></td>
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
