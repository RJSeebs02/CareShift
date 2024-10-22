<div class="heading">
    <h1><i class="fas fa-lock" aria-hidden="true"></i>&nbspAdd Admin</h1>
    <a href="index.php?page=admins" class="right_button"><i class="fa fa-list-ol" aria-hidden="true"></i>&nbspAdmin List</a>
</div>

<div class="add_form_wrapper">
<form class="add_form" action="processes/process.admin.php?action=new" method="POST">
    <div class="form_wrapper">
        <div class="add_form_left">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

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

            <label for="access">Access:</label>
            <select id="access" name="access" required>
                <option value="">Select Access</option>
                <option value="Super Admin">Super Admin</option>
                <option value="Head Nurse">Head Nurse</option>
                <option value="OR Scheduler">Operating Room Scheduler</option>
                <option value="CNA Scheduler">Clinical Nursing Area Scheduler</option>
                <option value="SCN Scheduler">Special Care Nursing Scheduler</option>
            </select>
        </div>
    </div>
    <button type="submit" class="submit-btn">Add Admin</button>
</form>
</div>