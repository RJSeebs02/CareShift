<div class="heading">
    <h1><i class="fas fa-solid fa-users-line"></i>&nbsp<?php echo $departments->get_department_name($id).' ';?>Details</h1>
    <a href="index.php?page=departments" class="right_button"><i class="fa fa-list-ol" aria-hidden="true"></i>&nbspDept. List</a>
    <a href="index.php?page=departments&subpage=add" class="right_button"><i class="fa fa-plus"></i>&nbspAdd Dept.</a>
</div>

<div class="add_form_wrapper">
<form class="add_form" action="processes/process.department.php?action=update" method="POST">
    <div class="form_wrapper">
        <div class="add_form_left">
            <label for="department_name">Department Name</label>
            <input type="text" id="department_name" name="department_name" value="<?php echo $departments->get_department_name($id);?>" required>

            <label for="department_desc">Description</label>
            <input type="text" id="department_desc" name="department_desc" value="<?php echo $departments->get_department_desc($id);?>" required>
        </div>
        <div class="add_form-right">
            <label for="dept_type_id">Department</label>
            <select required id="dept_type_id" name="dept_type_id">
                <?php
                if($dept_type->list_dept_type() != false){
                    foreach($dept_type->list_dept_type() as $value){
                        extract($value);
                ?>
                            <option value="<?php echo $dept_type_id; ?>" <?php echo ($dept_type_id == $departments->get_department_dept_type_id($id)) ? 'selected' : ''; ?>>
                                <?php echo $dept_type_name; ?>
                            </option>
                                <?php
                }
            }
            ?>

            <label for="id"></label>
            <input type="text" id="id" class="text" name="id" value="<?php echo $departments->get_id_by_id($id);?>" hidden>
        </div>
    </div>
    <button type="submit" class="submit-btn">Update Department</button>
</form>

<form action="processes/process.department.php?action=delete" method="POST">
    <input type="text" id="id" class="text" name="id" value="<?php echo $departments->get_id_by_id($id);?>" hidden>
    <button type="submit" class="delete-btn">Delete Department</button>
</form>

</div>