<div class="heading">
    <h1><i class="fas fa-solid fa-user-shield"></i>&nbspUser Access</h1>
    <a href="index.php?page=useraccess&subpage=add" class="right_button"><i class="fa fa-plus"></i>&nbspAdd Access Level</a>
</div>
<span class="right">
    <div class="search_bar">Search:<input type="text" id="search" name="search" onkeyup="">
    </div>
</span>

<table id="tablerecords">   
    <thead>
        <tr>
            <th>ID</th>
            <th>Access Level Name</th>
            <th>Access</th>
        </tr>
    </thead>
    <tbody>
        <?php
        /* Display each admin record located in the database */
        if ($useraccess->list_useraccess() != false) {
            foreach ($useraccess->list_useraccess() as $value) {
                extract($value);
                // Create a link for each row using the nurse_id
                $row_url = "index.php?page=useraccess&subpage=profile&id=" . $useraccess_id;
                ?>
                <tr onclick="location.href='<?php echo $row_url; ?>'" style="cursor: pointer;">
                    <td><?php echo $useraccess_id; ?></td>
                    <td><?php echo $useraccess_name; ?></td>
                    <td><?php echo $useraccess_desc; ?></td>
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
