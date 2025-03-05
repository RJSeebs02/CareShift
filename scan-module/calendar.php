<div class="heading">
    <h1><i class="fas fa-solid fa-clock"></i>&nbspSchedule Calendar</h1>
    <a href="index.php?page=schedule" class="right_button"><i class="fa fa-list-ol" aria-hidden="true"></i>&nbspList</a>
    <a href="index.php?page=schedule&subpage=calendar" class="right_button"><i class="fa fa-calendar"></i>&nbspCalendar</a>
    
<?php if ($useraccess_id != 2 ): ?>
    <a href="index.php?page=schedule&subpage=add" class="right_button"><i class="fa fa-plus"></i>&nbspAdd Sched</a>
    <a href="index.php?page=schedule&subpage=generate" class="right_button"><i class="fa fa-plus"></i>&nbspGenerate</a>
<?php endif; ?>
</div>

<div id="viewScheduleModal" class="modal">
    <div class="modal-content">
        <span class="close" id="viewScheduleClose">&times;</span>
        <h1><i class="fa fa-plus"></i>&nbsp;Shift Details</h1>
        <form action="processes/process.schedule.php?action=update" method="POST">
            <input type="hidden" id="eventSchedId" name="eventSchedId">
            <input type="hidden" id="eventNurseId" name="eventNurseId">
            <label for="eventNurse">Nurse</label>
            <input type="text" id="eventNurse" name="eventNurse" readonly>
            <label for="eventPosition">Nurse Position</label>
            <input type="text" id="eventPosition" name="eventPosition" readonly>
            <label for="eventDepartment">Department</label>
            <input type="text" id="eventDepartment" name="eventDepartment" readonly>
            <label for="eventDate">Date</label>
            <input type="date" id="eventDate" name="eventDate" required />
            <label for="eventStart">Shift Start Time</label>
            <input type="time" id="eventStart" name="eventStart" required>
            <label for="eventEnd">Shift End Time:</label>
            <input type="time" id="eventEnd" name="eventEnd" required>
            <button type="submit" class="submit-btn">Update Schedule</button>
        </form>
    </div>
</div>

<div id="calendar"></div>