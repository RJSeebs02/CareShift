<<<<<<< HEAD
<div class="heading">
    <h1><i class="fas fa-solid fa-user-nurse"></i>&nbsp<?php echo $nurse->get_fname($id).' '.$nurse->get_lname($id).'\'s ';?>Profile</h1>
    <a href="index.php?page=nurses" class="right_button"><i class="fa fa-list-ol" aria-hidden="true"></i>&nbspNurse List</a>
    <a href="index.php?page=nurses&subpage=add" class="right_button"><i class="fa fa-plus"></i>&nbspAdd Nurse</a>
</div>

<div class="add_form_wrapper">
<form class="add_form" action="processes/process.nurse.php?action=update" method="POST">
    <div class="form_wrapper">
    <img src="img/default_profile_pic.jpg" alt="Profile Image" class="profile_image">
        <div class="add_form_left">

            <label for="first_name">First Name</label>
            <input type="text" id="first_name" name="first_name" value="<?php echo $nurse->get_fname($id);?>" required>

            <label for="middle_name">Middle Name</label>
            <input type="text" id="middle_name" name="middle_name" value="<?php echo $nurse->get_mname($id);?>" required>

            <label for="last_name">Last Name</label>
            <input type="text" id="last_name" name="last_name" value="<?php echo $nurse->get_lname($id);?>" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo $nurse->get_email($id);?>" required>
        </div>
        <div class="add_form-right">

            <label for="sex">Sex</label>
            <select id="sex" name="sex" required>
                <option value="<?php echo $nurse->get_sex($id);?>"><?php echo $nurse->get_sex($id);?></option>
                <?php
                $sex = ["Male", "Female"];
                foreach ($sex as $sex) {
                    if ($sex != $nurse->get_sex($id)) {
                        echo "<option value=\"$sex\">$sex</option>";
                    }
                }
                ?>
            </select>

            <label for="position">Position</label>
            <select id="position" name="position" required>
                <option value="<?php echo $nurse->get_position($id); ?>"><?php echo $nurse->get_position($id); ?></option>
                <?php
                $positions = ["Nurse I", "Nurse II", "Nurse III", "Nurse IV", "Nurse V", "Nursing Attendant I", "Nursing Attendant II"];
                foreach ($positions as $position) {
                    if ($position != $nurse->get_position($id)) {
                        echo "<option value=\"$position\">$position</option>";
                    }
                }
                ?>
            </select>

            <label for="contact_no">Contact No.</label>
            <input type="tel" id="contact_no" name="contact_no" value="<?php echo $nurse->get_contact($id);?>" required>

            <label for="department">Department</label>
            <select required id="department" name="department">
                <option value="" disabled selected>Select Department</option>
                    <?php
                    if($departments->list_department() != false){
                        foreach($departments->list_department() as $value){
                            extract($value);
                            ?>
                            <option value="<?php echo $department_id; ?>" <?php echo ($department_id == $nurse->get_nurse_department_id($id)) ? 'selected' : ''; ?>>
                                <?php echo $department_name; ?>
                            </option>
                            <?php
                        }
                    }
                ?>
            </select>

            <label for="id"></label>
            <input type="text" id="id" class="text" name="id" value="<?php echo $nurse->get_id($id);?>" hidden>
        </div>
    </div>
    <button type="submit" class="submit-btn">Update Nurse</button>
</form>

<form action="processes/process.nurse.php?action=delete" method="POST">
    <input type="text" id="id" class="text" name="id" value="<?php echo $nurse->get_id($id);?>" hidden>
    <button type="submit" class="delete-btn">Delete Nurse</button>
</form>

=======
<div class="heading">
    <h1><i class="fas fa-solid fa-user-nurse"></i>&nbsp<?php echo $nurse->get_fname($id).' '.$nurse->get_lname($id).'\'s ';?>Profile</h1>
    <a href="index.php?page=nurses" class="right_button"><i class="fa fa-list-ol" aria-hidden="true"></i>&nbspNurse List</a>
    <a href="index.php?page=nurses&subpage=add" class="right_button"><i class="fa fa-plus"></i>&nbspAdd Nurse</a>
</div>

<div class="add_form_wrapper">
<form class="add_form" action="processes/process.nurse.php?action=update" method="POST">
    <div class="form_wrapper">
    <img src="img/default_profile_pic.jpg" alt="Profile Image" class="profile_image">
        <div class="add_form_left">

            <label for="first_name">First Name</label>
            <input type="text" id="first_name" name="first_name" value="<?php echo $nurse->get_fname($id);?>" required>

            <label for="middle_name">Middle Name</label>
            <input type="text" id="middle_name" name="middle_name" value="<?php echo $nurse->get_mname($id);?>" required>

            <label for="last_name">Last Name</label>
            <input type="text" id="last_name" name="last_name" value="<?php echo $nurse->get_lname($id);?>" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo $nurse->get_email($id);?>" required>
        </div>
        <div class="add_form-right">

            <label for="sex">Sex</label>
            <select id="sex" name="sex" required>
                <option value="<?php echo $nurse->get_sex($id);?>"><?php echo $nurse->get_sex($id);?></option>
                <?php
                $sex = ["Male", "Female"];
                foreach ($sex as $sex) {
                    if ($sex != $nurse->get_sex($id)) {
                        echo "<option value=\"$sex\">$sex</option>";
                    }
                }
                ?>
            </select>

            <label for="position">Position</label>
            <select id="position" name="position" required>
                <option value="<?php echo $nurse->get_position($id); ?>"><?php echo $nurse->get_position($id); ?></option>
                <?php
                $positions = ["Nurse I", "Nurse II", "Nurse III", "Nurse IV", "Nurse V", "Nursing Attendant I", "Nursing Attendant II"];
                foreach ($positions as $position) {
                    if ($position != $nurse->get_position($id)) {
                        echo "<option value=\"$position\">$position</option>";
                    }
                }
                ?>
            </select>

            <label for="contact_no">Contact No.</label>
            <input type="tel" id="contact_no" name="contact_no" value="<?php echo $nurse->get_contact($id);?>" required>

            <label for="department">Department</label>
            <select required id="department" name="department">
                <option value="" disabled selected>Select Department</option>
                    <?php
                    if($departments->list_department() != false){
                        foreach($departments->list_department() as $value){
                            extract($value);
                            ?>
                            <option value="<?php echo $department_id; ?>" <?php echo ($department_id == $nurse->get_nurse_department_id($id)) ? 'selected' : ''; ?>>
                                <?php echo $department_name; ?>
                            </option>
                            <?php
                        }
                    }
                ?>
            </select>

            <label for="id"></label>
            <input type="text" id="id" class="text" name="id" value="<?php echo $nurse->get_id($id);?>" hidden>
        </div>
    </div>
    <button type="submit" class="submit-btn">Update Nurse</button>
</form>

<form action="processes/process.nurse.php?action=delete" method="POST">
    <input type="text" id="id" class="text" name="id" value="<?php echo $nurse->get_id($id);?>" hidden>
    <button type="submit" class="delete-btn">Delete Nurse</button>
</form>

>>>>>>> fa57ef9cad725f4de6f3f7079a21b6419102a7c6
</div>