<div class="heading">
    <h1><i class="fas fa-lock" aria-hidden="true"></i>&nbsp<?php echo $admin->get_fname($id).'\'s ';?>Profile</h1>
    <a href="index.php?page=admins" class="right_button"><i class="fa fa-list-ol" aria-hidden="true"></i>&nbspAdmin List</a>
    <a href="index.php?page=admins&subpage=add" class="right_button"><i class="fa fa-plus"></i>&nbspAdd Admin</a>
</div>

<div class="add_form_wrapper">
<form class="add_form" action="processes/process.admin.php?action=update" method="POST">
    <div class="form_wrapper">
        <div class="add_form_left">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?php echo $admin->get_username($id);?>" readonly>

            <label for="first_name">First Name</label>
            <input type="text" id="first_name" name="first_name" value="<?php echo $admin->get_fname($id);?>" required>

            <label for="middle_name">Middle Name</label>
            <input type="text" id="middle_name" name="middle_name" value="<?php echo $admin->get_mname($id);?>" required>

            <label for="last_name">Last Name</label>
            <input type="text" id="last_name" name="last_name" value="<?php echo $admin->get_lname($id);?>" required>
        </div>
        <div class="add_form-right">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo $admin->get_email($id);?>" required>

            <label for="contact_no">Contact No.</label>
            <input type="tel" id="contact_no" name="contact_no" value="<?php echo $admin->get_contact($id);?>" required>

            <label for="department">Department</label>
            <select required id="department" name="department">
                    <?php
                    if($departments->list_department() != false){
                        foreach($departments->list_department() as $value){
                            extract($value);
                            ?>
                            <option value="<?php echo $department_id;?>" <?php echo ($department_id == $admin->get_department_id($id)) ? 'selected' : ''; ?>>
                                <?php echo $department_name; ?>
                            </option>
                            <?php
                        }
                    }
                ?>
            </select>
            
            <label for="access">Access</label>
            <select required id="access" name="access">
                    <?php
                    if($useraccess->list_useraccess() != false){
                        foreach($useraccess->list_useraccess() as $value){
                            extract($value);
                            ?>
                            <option value="<?php echo $useraccess_id;?>" <?php echo ($useraccess_id == $admin->get_access_id($id)) ? 'selected' : ''; ?>>
                                <?php echo $useraccess_name; ?>
                            </option>
                            <?php
                        }
                    }
                ?>
            </select>

            <label for="id"></label>
            <input type="text" id="id" class="text" name="id" value="<?php echo $admin->get_id_by_id($id);?>" hidden>
        </div>
    </div>
    <button type="submit" class="submit-btn">Update Admin</button>
</form>

<form action="processes/process.admin.php?action=delete" method="POST">
    <input type="text" id="id" class="text" name="id" value="<?php echo $admin->get_id_by_id($id);?>" hidden>
    <button type="submit" class="delete-btn">Delete Admin</button>
</form>

</div>