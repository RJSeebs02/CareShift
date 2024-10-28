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
                <option value="">Select Sex</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
            <label for="position">Position</label>
            <select id="position" name="position" required>
                <option value="">Select Position</option>
                <option value="Nurse I">Nurse I</option>
                <option value="Nurse II">Nurse II</option>
                <option value="Nurse III">Nurse III</option>
            </select>

            <label for="contact_no">Contact No.</label>
            <input type="tel" id="contact_no" name="contact_no" placeholder="Enter Contact Number" required>

            <label for="department">Department</label>
            <select id="department" name="department" required>
                <option value="">Select Department</option>
                <option value="Operating Room">Operating Room</option>
                <option value="Clinical Nursing Area">Clinical Nursing Area</option>
                <option value="Special Care Nursing Area">Special Care Nursing Area</option>
            </select>
        </div>
    </div>
    <button type="submit" class="submit-btn">Add Nurse</button>
</form>
</div>