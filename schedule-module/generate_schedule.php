<<<<<<< HEAD
<div class="heading">
    <h1><i class="fas fa-solid fa-clock"></i>&nbspGenerate Schedule</h1>
    <a href="index.php?page=schedule" class="right_button"><i class="fa fa-list-ol" aria-hidden="true"></i>&nbspSchedules List</a>
    <a href="index.php?page=schedule&subpage=calendar" class="right_button"><i class="fa fa-calendar"></i>&nbspCalendar</a>
    
<?php if ($useraccess_id != 2 ): ?>
    <a href="index.php?page=schedule&subpage=add" class="right_button"><i class="fa fa-plus"></i>&nbspAdd Sched</a>
    <a href="index.php?page=schedule&subpage=generate" class="right_button"><i class="fa fa-plus"></i>&nbspGenerate</a>
<?php endif; ?>
</div>

<div class="add_form_wrapper">
<form class="add_form" action="processes/process.schedule.php?action=generate" method="POST">
    <div class="form_wrapper">
        <div class="add_form_left">
            <label for="nurse_id">Nurse</label>
            <div class="custom-multiselect">
                <input type="text" id="nurseDropdown" placeholder="Select Nurse" readonly onclick="toggleDropdown()">
                <div class="dropdown-options" id="dropdownOptions">
                    <label><input type="checkbox" id="selectAll" onclick="toggleSelectAll()"> Select All</label>
                        <?php
                        if($nurses != false){
                            foreach($nurses as $value){
                                extract($value);
                                echo '<label><input type="checkbox" value="'.$nurse_id.'" class="nurse-option" onchange="updateSelectedNurses()"> '.$nurse_fname.' '.$nurse_mname.' '.$nurse_lname.'</label>';
                            }
                        }
                        ?>
                </div>
                <input type="hidden" name="nurse_id" id="nurse_id" required>
            </div>

        </div>
        <div class="add_form-right">
            <label for="start_date">Schedule Start Date</label>
            <input type="date" id="start_date" name="start_date" required>

            <label for="end_date">Schedule End Date</label>
            <input type="date" id="end_date" name="end_date" required>
        </div>
    </div>
    <button type="submit" class="submit-btn">Add Schedule</button>
</form>
</div>
=======
<div class="heading">
    <h1><i class="fas fa-solid fa-clock"></i>&nbsp;Schedule</h1>
	<a href="index.php?page=schedule" class="right_button <?= $page == 'schedule' && !isset($_GET['subpage']) ? 'active' : '' ?>">
		<i class="fa fa-list-ol" aria-hidden="true"></i>&nbspSchedules List
	</a>
	<a href="index.php?page=schedule&subpage=calendar" class="right_button <?= $page == 'schedule' && $subpage == 'calendar' ? 'active' : '' ?>">
    	<i class="fa fa-calendar"></i>&nbspCalendar
	</a>
    
<?php if ($useraccess_id != 2 ): ?>
    <a href="index.php?page=schedule&subpage=add" class="right_button <?= $page == 'schedule' && $subpage == 'add' ? 'active' : '' ?>">
		<i class="fa fa-plus"></i>&nbspAdd Sched
	</a>
    <a href="index.php?page=schedule&subpage=generate" class="right_button <?= $page == 'schedule' && $subpage == 'generate' ? 'active' : '' ?>">
	<i class="fa-solid fa-user-plus"></i>&nbspGenerate</a>
<?php endif; ?>
</div>

<div class="add_form_wrapper">
<form class="add_form" action="processes/process.schedule.php?action=generate" method="POST">
    <div class="form_wrapper">
        <div class="add_form_left">
            <label for="nurse_id">Nurse</label>
            <div class="custom-multiselect">
                <input type="text" id="nurseDropdown" placeholder="Select Nurse" readonly onclick="toggleDropdown()">
                <div class="dropdown-options" id="dropdownOptions">
                    <label><input type="checkbox" id="selectAll" onclick="toggleSelectAll()"> Select All</label>
                        <?php
                        if($nurses != false){
                            foreach($nurses as $value){
                                extract($value);
                                echo '<label><input type="checkbox" value="'.$nurse_id.'" class="nurse-option" onchange="updateSelectedNurses()"> '.$nurse_fname.' '.$nurse_mname.' '.$nurse_lname.'</label>';
                            }
                        }
                        ?>
                </div>
                <input type="hidden" name="nurse_id" id="nurse_id" required>
            </div>

        </div>
        <div class="add_form-right">
            <label for="start_date">Schedule Start Date</label>
            <input type="date" id="start_date" name="start_date" required>

            <label for="end_date">Schedule End Date</label>
            <input type="date" id="end_date" name="end_date" required>
        </div>
    </div>
    <button type="submit" class="submit-btn">Add Schedule</button>
</form>
</div>
>>>>>>> fa57ef9cad725f4de6f3f7079a21b6419102a7c6
