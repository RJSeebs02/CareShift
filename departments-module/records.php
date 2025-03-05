<<<<<<< HEAD
<div class="heading">
    <h1><i class="fas fa-solid fa-users-line"></i>&nbspDepartments</h1>

    <?php if ($useraccess_id != 2 && $useraccess_id != 3): ?>
    <a href="index.php?page=departments" class="right_button"><i class="fa fa-list-ol" aria-hidden="true"></i>&nbspDept. List</a>
    <a href="index.php?page=departments&subpage=add" class="right_button"><i class="fa fa-plus"></i>&nbspAdd Dept.</a>
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
=======
<div class="heading">
    <h1><i class="fas fa-solid fa-users-line"></i>&nbspDepartments</h1>

    <?php if ($useraccess_id != 2 && $useraccess_id != 3): ?>
	<a href="index.php?page=departments" class="right_button <?= $page == 'departments' && !isset($_GET['subpage']) ? 'active' : '' ?>">
		<i class="fa fa-list-ol" aria-hidden="true"></i>&nbspDept. List
	</a>
	<a href="index.php?page=departments&subpage=add" class="right_button <?= $page == 'departments' && $subpage == 'add' ? 'active' : '' ?>">
		<i class="fa fa-plus"></i>&nbspAdd Dept.
	</a>
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
>>>>>>> fa57ef9cad725f4de6f3f7079a21b6419102a7c6
