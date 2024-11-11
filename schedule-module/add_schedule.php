<div class="heading">
    <h1><i class="fas fa-solid fa-clock"></i>&nbspAdd Schedule</h1>
    <a href="index.php?page=schedule" class="right_button"><i class="fa fa-list-ol" aria-hidden="true"></i>&nbspSchedules List</a>
</div>

<div class="add_form_wrapper">
<form class="add_form" action="processes/process.schedule.php?action=new" method="POST">
    <div class="form_wrapper">
        <div class="add_form_left">
            <label for="nurse_id">Nurse</label>
            <div class="custom-multiselect">
                <input type="text" id="nurseDropdown" placeholder="Select Nurse" readonly onclick="toggleDropdown()">
                <div class="dropdown-options" id="dropdownOptions">
                    <label><input type="checkbox" id="selectAll" onclick="toggleSelectAll()"> Select All</label>
                        <?php
                        if($nurse->list_nurses() != false){
                            foreach($nurse->list_nurses() as $value){
                                extract($value);
                                echo '<label><input type="checkbox" value="'.$nurse_id.'" class="nurse-option" onchange="updateSelectedNurses()"> '.$nurse_fname.' '.$nurse_mname.' '.$nurse_lname.'</label>';
                            }
                        }
                        ?>
                </div>
                <input type="hidden" name="nurse_id" id="nurse_id" required>
            </div>
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
