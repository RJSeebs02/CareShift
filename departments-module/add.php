<div class="heading">
    <h1><i class="fas fa-solid fa-users-line"></i>&nbspAdd Department</h1>
    <a href="index.php?page=departments" class="right_button"><i class="fa fa-list-ol" aria-hidden="true"></i>&nbspDepartments List</a>
</div>

<div class="add_form_wrapper">
<form class="add_form" action="processes/process.department.php?action=new" method="POST">
    <div class="form_wrapper">
        <div class="add_form_left">
            <label for="department_name">Department Name</label>
            <input type="text" id="department_name" name="department_name" placeholder="Enter Department Name" required>

            <label for="department_desc">Description</label>
            <input type="text" id="department_desc" name="department_desc" placeholder="Enter Department Desc" required>
        </div>
        <div class="add_form-right">
            <label for="dept_type_id">Department</label>
            <select required id="dept_type_id" name="dept_type_id">
                <option value="" disabled selected>Select Department Type</option>
                    <?php
                    if($dept_type->list_dept_type() != false){
                        foreach($dept_type->list_dept_type() as $value){
                            extract($value);
                            ?>
                            <option value="<?php echo $dept_type_id;?>"><?php echo $dept_type_name; ?></option>
                            <?php
                        }
                    }
                ?>
            </select>
        </div>
    </div>
    <button type="submit" class="submit-btn">Add Department</button>
</form>
</div>