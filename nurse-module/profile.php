<div class="heading">
    <h1><i class="fas fa-solid fa-user-nurse"></i>&nbsp<?php echo $nurse->get_fname($id).' '.$nurse->get_lname($id).'\'s ';?>Profile</h1>
    <a href="index.php?page=nurses" class="right_button"><i class="fa fa-list-ol" aria-hidden="true"></i>&nbspNurse List</a>
</div>

<div class="add_form_wrapper">
<form class="add_form" action="processes/process.nurse.php?action=update" method="POST">
    <div class="form_wrapper">
        <div class="add_form_left">

            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" value="<?php echo $nurse->get_fname($id);?>" required>

            <label for="middle_name">Middle Name:</label>
            <input type="text" id="middle_name" name="middle_name" value="<?php echo $nurse->get_mname($id);?>" required>

            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" value="<?php echo $nurse->get_lname($id);?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $nurse->get_email($id);?>" required>
        </div>
        <div class="add_form-right">

            <label for="position">Position:</label>
            <select id="position" name="position" required>
                <option value="<?php echo $nurse->get_position($id);?>"><?php echo $nurse->get_position($id);?></option>
                <option value="Nurse I">Nurse I</option>
                <option value="Nurse II">Nurse II</option>
                <option value="Nurse III">Nurse III</option>
            </select>

            <label for="contact_no">Contact No.:</label>
            <input type="tel" id="contact_no" name="contact_no" value="<?php echo $nurse->get_contact($id);?>" required>

            <label for="department">Department:</label>
            <select id="department" name="department" required>
                <option value="<?php echo $nurse->get_department($id);?>"><?php echo $nurse->get_department($id);?></option>
                <option value="Operating Room">Operating Room</option>
                <option value="Clinical Nursing Area">Clinical Nursing Area</option>
                <option value="Special Care Nursing Area">Special Care Nursing Area</option>
            </select>

            <label for="id"></label>
            <input type="text" id="id" class="text" name="id" value="<?php echo $nurse->get_id($id);?>" hidden>
        </div>
    </div>
    <button type="submit" class="submit-btn">Update Nurse</button>
</form>
</div>