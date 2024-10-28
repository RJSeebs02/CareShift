<div class="content_wrapper">
    <div class="heading">
        <h1><i class="fas fa-solid fa-clock"></i>&nbsp;Schedule</h1>

        
        <!-- Add Schedule Button (opens modal) -->
        <button id="addScheduleBtn" class="right_button">
            <i class="fa fa-plus"></i>&nbspAdd Schedule
        </button>

        <!-- Multiple Schedule Button (opens modal) -->
        <button id="multipleScheduleBtn" class="right_button">
            <i class="fa fa-plus"></i>&nbspMultiple Assign
        </button>

        <!-- Auto Generate Schedule Button (opens modal) -->
        <button id="generateScheduleBtn" class="right_button">
            <i class="fa fa-plus"></i>&nbspAuto Generate
        </button>
    </div>
        <form id="nurseForm" method="GET" action="">
            <label for="nurseSelect">Select Nurse:</label>
            <select id="nurseSelect" name="nurse_id" onchange="this.form.submit()">
                <option value="all">All Nurses</option>
                <?php
                // Query to select nurses from the nurse table
                $query = "SELECT nurse_id, CONCAT(nurse_lname, ', ', nurse_fname) AS name FROM nurse";
                $result = mysqli_query($con, $query);

                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $selected = isset($_GET['nurse_id']) && $_GET['nurse_id'] == $row['nurse_id'] ? 'selected' : '';
                        echo "<option value='{$row['nurse_id']}' $selected>{$row['name']}</option>";
                    }
                } else {
                    echo "<option>No nurses found</option>";
                }
                ?>
            </select>
        </form>
</div>


<!-- Add Schedule Modal -->
<div id="addScheduleModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h1><i class="fa fa-plus"></i>&nbsp;Add Nurse Schedule</h1>
        <form action="processes/process.schedule.php?action=new" method="POST">
            <label for="nurse_id">Select Nurse:</label>
            <select name="nurse_id" required>
                <?php
                if (!$con) {
                    die("Connection failed: " . mysqli_connect_error());
                }

                $query = "SELECT nurse_id, CONCAT(nurse_fname, ' ', nurse_lname) AS name FROM nurse";
                $result = mysqli_query($con, $query); 

                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='{$row['nurse_id']}'>{$row['name']}</option>";
                    }
                } else {
                    echo "<option>No nurses found</option>";
                }
                ?>
            </select>
            
            <!-- Schedule Details -->
            <label for="sched_date">Schedule Date:</label>
            <input type="date" name="sched_date" required>
            <label for="sched_start_time">Start Time:</label>
            <input type="time" name="sched_start_time" required>
            <label for="work_hours">Work Hours:</label>
            <input type="number" name="work_hours" required>

            <button type="submit">Add Schedule</button>
        </form>
    </div>
</div>

<!-- Multiple Schedule Modal -->
<div id="multipleScheduleModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h1><i class="fa fa-plus"></i>&nbsp;Multiple Assign Nurse Schedule</h1>
        <form action="processes/process.schedule.php?action=multiple" method="POST">
            
            <!-- Nurse Selection (either all nurses or specific ones) -->
            <label for="nurse_id">Select Nurse:</label>
            <div>
                <input type="checkbox" name="nurse_id[]" value="all" id="selectAllNurses">
                <label for="selectAllNurses">All Nurses</label>
            </div>
            <?php
            if (!$con) {
                die("Connection failed: " . mysqli_connect_error());
            }

            $query = "SELECT nurse_id, CONCAT(nurse_fname, ' ', nurse_lname) AS name FROM nurse";
            $result = mysqli_query($con, $query); 

            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<div>";
                    echo "<input type='checkbox' name='nurse_id[]' value='{$row['nurse_id']}' id='nurse_{$row['nurse_id']}'>";
                    echo "<label for='nurse_{$row['nurse_id']}'>{$row['name']}</label>";
                    echo "</div>";
                }
            } else {
                echo "<p>No nurses found</p>";
            }
            ?>

            <!-- Other Inputs -->
            <label for="start_date">Start Date:</label>
            <input type="date" name="start_date" required>
            <label for="end_date">End Date:</label>
            <input type="date" name="end_date" required>
            <label for="start_time">Start Time:</label>
            <input type="time" name="start_time" required>
            <label for="end_time">End Time:</label>
            <input type="time" name="end_time" required>

            <button type="submit">Assign Schedule</button>
        </form>
    </div>
</div>

<!-- Generate Schedule Modal -->
<div id="generateScheduleModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h1><i class="fa fa-cogs"></i>&nbsp;Auto Generate Nurse Schedule</h1>
        <form action="processes/process.schedule.php?action=generate" method="POST">

            <!-- Nurse Selection (either all nurses or specific ones) -->
            <label for="nurse_id">Select Nurse:</label>
            <div>
                <input type="checkbox" name="nurse_id[]" value="all" id="selectAllNurses">
                <label for="selectAllNurses">All Nurses</label>
            </div>
            <?php
            if (!$con) {
                die("Connection failed: " . mysqli_connect_error());
            }

            $query = "SELECT nurse_id, CONCAT(nurse_fname, ' ', nurse_lname) AS name FROM nurse";
            $result = mysqli_query($con, $query); 

            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<div>";
                    echo "<input type='checkbox' name='nurse_id[]' value='{$row['nurse_id']}' id='nurse_{$row['nurse_id']}'>";
                    echo "<label for='nurse_{$row['nurse_id']}'>{$row['name']}</label>";
                    echo "</div>";
                }
            } else {
                echo "<p>No nurses found</p>";
            }
            ?>

            <!-- Date Inputs -->
            <label for="start_date">Start Date:</label>
            <input type="date" name="start_date" required>
            <label for="end_date">End Date:</label>
            <input type="date" name="end_date" required>

            <!-- Submit Button -->
            <button type="submit">Generate Schedule</button>
        </form>
    </div>
</div>



<!-- View Schedule Modal -->
<div id="viewScheduleModal" class="modal">
    <div class="modal-content">
        <span class="close" id="viewScheduleClose">&times;</span>
        <h1><i class="fa fa-plus"></i>&nbsp;Shift Details</h1>
        <form action="processes/process.schedule.php?action=update" method="POST">
            <label for="eventNurse">Nurse</label>
            <select name="eventNurse" disabled>
                <?php
                if (!$con) {
                    die("Connection failed: " . mysqli_connect_error());
                }

                $query = "SELECT nurse_id, CONCAT(nurse_fname, ' ', nurse_lname) AS name FROM nurse";
                $result = mysqli_query($con, $query); 

                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='{$row['nurse_id']}'>{$row['name']}</option>";
                    }
                } else {
                    echo "<option>No nurses found</option>";
                }
                ?>
            </select>
            
            <!-- Schedule Details -->
            <label for="eventPosition">Nurse Position</label>
            <input type="text" id="eventPosition" name="eventPosition" readonly>

            <label for="eventDepartment">Department</label>
            <input type="text" id="eventDepartment" name="eventDepartment" readonly>
            
            <label for="eventStart">Shift Start Time</label>
            <input type="text" id="eventStart" name="eventStart" readonly>

            <label for="eventEnd">Shift End Time:</label>
            <input type="text" id="eventEnd" name="eventEnd" readonly>
        </form>
    </div>
</div>

<div id="calendar"></div>
