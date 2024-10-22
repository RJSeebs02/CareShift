<div class="heading">
    <h1><i class="fas fa-regular fa-file-alt"></i>&nbspApply for Leave</h1>
    <a href="index.php?page=leave" class="right_button"><i class="fa fa-list-ol" aria-hidden="true"></i>&nbspLeave List</a>
</div>

<div class="add_form_wrapper">
    <form class="add_form" action="processes/process.leave.php?action=new" method="POST">
        <div class="form_wrapper">
            <div class="add_form_left">
                <label for="leave_type">Leave Type:</label>
                <select id="leave_type" name="leave_type" required>
                    <option value="Pregnancy">Pregnancy</option>
                    <option value="Emergency">Emergency</option>
                </select>                

                <label for="leave_start_date">Start Date:</label>
                <input type="date" id="leave_start_date" name="leave_start_date" required>

                <label for="leave_end_date">End Date:</label>
                <input type="date" id="leave_end_date" name="leave_end_date" required>
            </div>
            <div class="add_form_right">
                <label for="leave_desc">Description:</label>
                <textarea id="description" name="leave_desc" required></textarea>

                <input type="hidden" id="nurse_id" name="nurse_id" value="<?php echo $nurse_id; ?>"> 

                <input type="hidden" id="adm_id" name="adm_id" value="<?php echo $admin_id; ?>"> 
            </div>
        </div>
        <button type="submit" class="submit-btn">Submit Leave Application</button>
    </form>
</div>
