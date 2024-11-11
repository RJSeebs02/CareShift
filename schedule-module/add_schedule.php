<div class="heading">
    <h1><i class="fas fa-solid fa-clock"></i>&nbspAdd Schedule</h1>
    <a href="index.php?page=schedule" class="right_button"><i class="fa fa-list-ol" aria-hidden="true"></i>&nbspSchedules List</a>
</div>

<div class="add_form_wrapper">
<form class="add_form" action="processes/process.schedule.php?action=new" method="POST">
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
                            <option value="<?php echo $nurse_id;?>"><?php echo $nurse_fname.' '.$nurse_mname.' '.$nurse_lname; ?></option>
                            <?php
                        }
                    }
                ?>
            </select>
            <label for="sched_date">Schedule Date</label>
            <input type="date" id="sched_date" name="sched_date" required>

            <label for="sched_start_time">Schedule Start Time</label>
            <input type="time" id="sched_start_time" name="sched_start_time" required>
        </div>
        <div class="add_form-right">
            <label for="work_hours">No. of Work Hours</label>
            <input type="number" id="work_hours" name="work_hours" required>
        </div>
    </div>
    <button type="submit" class="submit-btn">Add Schedule</button>
</form>
</div>
