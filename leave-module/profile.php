<div class="heading">
    <h1><i class="fas fa-regular fa-file-alt"></i>&nbspLeave # <?php echo $leave->get_id_by_id($id).' ';?>Profile</h1>
    <a href="index.php?page=leave" class="right_button"><i class="fa fa-list-ol" aria-hidden="true"></i>&nbspLeave List</a>
    <a href="index.php?page=leave&subpage=add" class="right_button"><i class="fa fa-file-pen" aria-hidden="true"></i>&nbspApply Leave</a>
</div>
<div class="leave-date-and-time">
    <h2>Date Filed: <?php echo $leave->get_leave_date_filed($id);?></h2>
    <h2>Time Filed: <?php echo $leave->get_leave_time_filed($id);?></h2>
</div>

<?php
if ($leave->get_leave_status($id) == 'Approved' || $leave->get_leave_status($id) == 'Denied'){
?>

<div class="add_form_wrapper">
<form class="add_form" action="processes/process.leave.php?action=approve" method="POST">
    <div class="form_wrapper">
        <div class="add_form_left">
            <input type="text" id="leave_id" name="leave_id" value="<?php echo $leave->get_id_by_id($id);?>" hidden>

            <label for="nurse_name">Nurse</label>
            <input type="text" id="nurse_name" name="nurse_name" value="<?php echo $leave->get_leave_nurse_name($id);?>" readonly>

            <label for="leave_start_date">Leave Start Date</label>
            <input type="text" id="leave_start_date" name="leave_start_date" value="<?php echo $leave->get_leave_start_date($id);?>" readonly>

            <label for="leave_end_date">Leave End Date</label>
            <input type="text" id="leave_end_date" name="leave_end_date" value="<?php echo $leave->get_leave_end_date($id);?>" readonly>
        </div>
        <div class="add_form-right">
            <label for="leave_type">Leave Type</label>
            <input type="text" id="leave_type" name="leave_type" value="<?php echo $leave->get_leave_type($id);?>" readonly>
            
            <label for="leave_desc">Description</label>
            <input type="text" id="leave_desc" name="leave_desc" value="<?php echo $leave->get_leave_desc($id);?>" readonly>
            
            <label for="leave_status">Status</label>
            <input type="text" id="leave_status" name="leave_status" value="<?php echo $leave->get_leave_status($id);?>" readonly>

        </div>
    </div>
</form>

</div>

<?php
}else{?>

<div class="add_form_wrapper">
<form class="add_form" action="processes/process.leave.php?action=approve" method="POST">
    <div class="form_wrapper">
        <div class="add_form_left">
            <input type="text" id="leave_id" name="leave_id" value="<?php echo $leave->get_id_by_id($id);?>" hidden>

            <label for="nurse_name">Nurse</label>
            <input type="text" id="nurse_name" name="nurse_name" value="<?php echo $leave->get_leave_nurse_name($id);?>" readonly>

            <label for="leave_start_date">Leave Start Date</label>
            <input type="text" id="leave_start_date" name="leave_start_date" value="<?php echo $leave->get_leave_start_date($id);?>" readonly>

            <label for="leave_end_date">Leave End Date</label>
            <input type="text" id="leave_end_date" name="leave_end_date" value="<?php echo $leave->get_leave_end_date($id);?>" readonly>
        </div>
        <div class="add_form-right">
            <label for="leave_type">Leave Type</label>
            <input type="text" id="leave_type" name="leave_type" value="<?php echo $leave->get_leave_type($id);?>" readonly>
            
            <label for="leave_desc">Description</label>
            <input type="text" id="leave_desc" name="leave_desc" value="<?php echo $leave->get_leave_desc($id);?>" readonly>

            <label for="leave_status">Status</label>
            <input type="text" id="leave_status" name="leave_status" value="<?php echo $leave->get_leave_status($id);?>" readonly>

        </div>
    </div>
    <button type="submit" class="submit-btn">Approve Leave</button>
</form>

<form action="processes/process.leave.php?action=deny" method="POST">
    <input type="text" id="leave_id" name="leave_id" value="<?php echo $leave->get_id_by_id($id);?>" hidden>
    <button type="submit" class="delete-btn">Deny Leave</button>
</form>

</div>

<?php
}