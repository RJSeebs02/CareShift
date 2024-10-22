<div class="content_wrapper">
    <div class="heading">
        <h1><i class="fas fa-solid fa-clock"></i>&nbsp;Schedule</h1>

        
        <!-- Add Schedule Button (opens modal) -->
        <button id="addScheduleBtn" class="right_button">
            <i class="fa fa-plus"></i>&nbspAdd Schedule
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

    <?php
    $subpage = isset($_GET['subpage']) ? $_GET['subpage'] : 'calendar';

    switch($subpage){
        case 'add_sched':
            require_once 'add_schedule.php';
        break;
        case 'calendar':
            require_once 'calendar.php';
        break; 
        default:
            require_once 'calendar.php';
        break;
    }
?>
</div>


<!-- Modal Structure -->
<div id="addScheduleModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h1><i class="fa fa-plus"></i>&nbspAdd Nurse Schedule</h1>
        <form method="post" action="schedule-module/generate_schedule.php">
            <label for="nurse_id">Select Nurse:</label>
            <select name="nurse_id" required>
                <?php
                if (!$con) {
                    die("Connection failed: " . mysqli_connect_error());
                }

                // Query to select nurses from the nurse table
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

            <!-- Date Selection -->
            <label for="sched_date">Select Date:</label>
            <input type="date" name="sched_date" required>

            <!-- Start Time Selection -->
            <label for="sched_start_time">Start Time:</label>
            <input type="time" name="sched_start_time" required>

            <!-- Number of Hours -->
            <label for="work_hours">Number of Hours:</label>
            <input type="number" name="work_hours" min="1" required>

            <!-- Submit Button -->
            <button type="submit">Generate Schedule</button>
        </form> 
    </div>
</div>
