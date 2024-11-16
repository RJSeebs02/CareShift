<div class="heading">
    <h1><i class="fas fa-regular fa-file-alt"></i>&nbspApply for Leave</h1>
    <a href="index.php?page=leave" class="right_button"><i class="fa fa-list-ol" aria-hidden="true"></i>&nbspLeave List</a>
    <a href="index.php?page=leave&subpage=add" class="right_button"><i class="fa fa-file-pen" aria-hidden="true"></i>&nbspApply Leave</a>
</div>

<div class="add_form_wrapper">
<form class="add_form" action="processes/process.leave.php?action=new" method="POST">
    <div class="form_wrapper">
        <div class="add_form_left">
            <label for="nurse_id">Nurse</label>
            <select required id="nurse_id" name="nurse_id">
                <option value="" disabled selected>Select Nurse</option>
                    <?php
                    if($nurse->list_nurses() != false){
                        foreach($nurse->list_nurses() as $value){
                            extract($value);
                            ?>
                            <option value="<?php echo $nurse_id;?>"><?php echo $nurse_lname.', '.$nurse_fname.' '.$nurse_mname; ?></option>
                            <?php
                        }
                    }
                ?>
            </select>

            <label for="leave_start_date">Leave Start Date</label>
            <input type="date" id="leave_start_date" name="leave_start_date" required>

            <label for="leave_end_date">Leave End Date</label>
            <input type="date" id="leave_end_date" name="leave_end_date" required>
        </div>
        <div class="add_form-right">
            <label for="leave_type">Leave Type</label>
            <select id="leave_type" name="leave_type" required>
                <option value="" disabled selected>Select Leave Type</option>
                <option value="Sick Leave">Sick Leave</option>
                <option value="Maternity/Paternity Leave">Maternity/Paternity Leave</option>
                <option value="Emergency Leave">Emergency Leave</option>
                <option value="Special Leave Benefits for Women">Special Leave Benefits for Women</option>
                <option value="Study Leave">Study Leave</option>
                <option value="Service Credits">Service Credits</option>
            </select>
            
            <label for="leave_desc">Description</label>
            <input type="text" id="leave_desc" name="leave_desc" placeholder="Enter Leave Description" required>

        </div>
    </div>
    <button type="submit" class="submit-btn">Submit Leave Application</button>
</form>
</div>