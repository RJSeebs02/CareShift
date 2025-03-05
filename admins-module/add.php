<<<<<<< HEAD
<div class="heading">
    <h1><i class="fas fa-lock" aria-hidden="true"></i>&nbspAdd Admin</h1>
    <a href="index.php?page=admins" class="right_button"><i class="fa fa-list-ol" aria-hidden="true"></i>&nbspAdmin List</a>
    <a href="index.php?page=admins&subpage=add" class="right_button"><i class="fa fa-plus"></i>&nbspAdd Admin</a>
</div>

<div class="add_form_wrapper">
<form class="add_form" action="processes/process.admin.php?action=new" method="POST">
    <div class="form_wrapper">
        <div class="add_form_left">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" placeholder="Enter Username" required>

            <label for="first_name">First Name</label>
            <input type="text" id="first_name" name="first_name" placeholder="Enter First Name" required>

            <label for="middle_name">Middle Name</label>
            <input type="text" id="middle_name" name="middle_name" placeholder="Enter Middle Name" required>

            <label for="last_name">Last Name</label>
            <input type="text" id="last_name" name="last_name" placeholder="Enter Last Name" required>
        </div>
        <div class="add_form-right">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter Email Address" required>

            <label for="contact_no">Contact No.</label>
            <input type="tel" id="contact_no" name="contact_no" placeholder="Enter Contact Number" required>

            <label for="department">Department</label>
            <select required id="department" name="department">
                <option value="" disabled selected>Select Department</option>
                    <?php
                    if($departments->list_department() != false){
                        foreach($departments->list_department() as $value){
                            extract($value);
                            ?>
                            <option value="<?php echo $department_id;?>"><?php echo $department_name; ?></option>
                            <?php
                        }
                    }
                ?>
            </select>

            <label for="access">Access</label>
            <select required id="access" name="access">
                <option value="" disabled selected>Select User Access</option>
                    <?php
                    if($useraccess->list_useraccess() != false){
                        foreach($useraccess->list_useraccess() as $value){
                            extract($value);
                            ?>
                            <option value="<?php echo $useraccess_id;?>"><?php echo $useraccess_name; ?></option>
                            <?php
                        }
                    }
                ?>
            </select>
        </div>
    </div>
    <button type="submit" class="submit-btn">Add Admin</button>
</form>
=======
<div class="heading">
    <h1><i class="fas fa-lock" aria-hidden="true"></i>&nbspAdd Admin</h1>
    <a href="index.php?page=admins" class="right_button <?= $page == 'admins' && !isset($_GET['subpage']) ? 'active' : '' ?>">
		<i class="fa fa-list-ol" aria-hidden="true"></i>&nbspAdmin List</a>
    <a href="index.php?page=admins&subpage=add" class="right_button <?= $page == 'admins' && $subpage == 'add' ? 'active' : '' ?>">
		<i class="fa fa-plus"></i>&nbspAdd Admin</a>
</div>

<div class="add_form_wrapper">
<form class="add_form" action="processes/process.admin.php?action=new" method="POST">
    <div class="form_wrapper">
        <div class="add_form_left">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" placeholder="Enter Username" required>

            <label for="first_name">First Name</label>
            <input type="text" id="first_name" name="first_name" placeholder="Enter First Name" required>

            <label for="middle_name">Middle Name</label>
            <input type="text" id="middle_name" name="middle_name" placeholder="Enter Middle Name" required>

            <label for="last_name">Last Name</label>
            <input type="text" id="last_name" name="last_name" placeholder="Enter Last Name" required>
        </div>
        <div class="add_form-right">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter Email Address" required>

            <label for="contact_no">Contact No.</label>
            <input type="tel" id="contact_no" name="contact_no" placeholder="Enter Contact Number" required>

            <label for="department">Department</label>
            <select required id="department" name="department">
                <option value="" disabled selected>Select Department</option>
                    <?php
                    if($departments->list_department() != false){
                        foreach($departments->list_department() as $value){
                            extract($value);
                            ?>
                            <option value="<?php echo $department_id;?>"><?php echo $department_name; ?></option>
                            <?php
                        }
                    }
                ?>
            </select>

            <label for="access">Access</label>
            <select required id="access" name="access">
                <option value="" disabled selected>Select User Access</option>
                    <?php
                    if($useraccess->list_useraccess() != false){
                        foreach($useraccess->list_useraccess() as $value){
                            extract($value);
                            ?>
                            <option value="<?php echo $useraccess_id;?>"><?php echo $useraccess_name; ?></option>
                            <?php
                        }
                    }
                ?>
            </select>
        </div>
    </div>
    <button type="submit" class="submit-btn">Add Admin</button>
</form>
>>>>>>> fa57ef9cad725f4de6f3f7079a21b6419102a7c6
</div>