<div class="heading">
    <h1><i class="fas fa-solid fa-user-nurse"></i>&nbspAdd Employee</h1>
    <a href="index.php?page=employees" class="right_button"><i class="fa fa-list-ol" aria-hidden="true"></i>&nbspEmployee List</a>
</div>

<div class="add_form_wrapper">
<form class="add_form" action="processes/process.employee.php?action=new" method="POST">
    <div class="form_wrapper">
        <div class="add_form_left">
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" required>

            <label for="middle_name">Middle Name:</label>
            <input type="text" id="middle_name" name="middle_name" required>

            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" required>
        </div>
        <div class="add_form-right">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="contact_no">Contact No.:</label>
            <input type="tel" id="contact_no" name="contact_no" required>

            <label for="department">Department:</label>
            <select id="department" name="department" required>
                <option value="">Select Department</option>
                <option value="Clinical Nursing Area">Clinical Nursing Area</option>
                <option value="Special Care Nursing Area">Special Care Nursing Area</option>
            </select>
        </div>
    </div>
    <button type="submit" class="submit-btn">Add Employee</button>
</form>
</div>