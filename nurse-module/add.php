<div class="heading">
    <h1><i class="fas fa-solid fa-user-nurse"></i>&nbspAdd Nurse</h1>
    <a href="index.php?page=nurses" class="right_button"><i class="fa fa-list-ol" aria-hidden="true"></i>&nbspNurse List</a>
</div>

<div class="add_form_wrapper">
<form class="add_form" action="processes/process.nurse.php?action=new" method="POST">
    <div class="form_wrapper">
        <div class="add_form_left">
            <label for="first_name">First Name</label>
            <input type="text" id="first_name" name="first_name" placeholder="Enter First Name" required>

            <label for="middle_name">Middle Name</label>
            <input type="text" id="middle_name" name="middle_name" placeholder="Enter Middle Name" required>

            <label for="last_name">Last Name</label>
            <input type="text" id="last_name" name="last_name" placeholder="Enter Last Name" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter Email Address" required>
        </div>
        <div class="add_form-right">
            <label for="sex">Sex</label>
            <select id="sex" name="sex" required>
                <option value="" disabled selected>Select Sex</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
            
            <label for="position">Position</label>
            <select id="position" name="position" required>
                <option value="" disabled selected>Select Position</option>
                <option value="Nurse I">Nurse I</option>
                <option value="Nurse II">Nurse II</option>
                <option value="Nurse III">Nurse III</option>
                <option value="Nurse IV">Nurse IV</option>
                <option value="Nurse V">Nurse V</option>
                <option value="Nursing Attendant I">Nursing Attendant I</option>
                <option value="Nursing Attendant II">Nursing Attendant II</option>
            </select>

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
        </div>
    </div>
    <button type="submit" class="submit-btn">Add Nurse</button>
</form>
</div>