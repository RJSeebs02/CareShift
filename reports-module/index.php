<div class="content_wrapper">
    <div class="heading">
        <h1><i class="fas fa-solid fa-chart-line"></i>&nbspReports</h1>
    </div>
</div>

<div class="content_layer1_wrapper">
    <div id="nurses_report">
        <form id="departmentForm" method="GET">
            <label for="departmentSelect">Select Department:</label>
            <select id="departmentSelect" name="department_id" onchange="">
                <option value="all">All Departments</option>
                <?php
                // Query to select departments from the department table
                $query = "SELECT department_id, department_name FROM department";
                $result = mysqli_query($con, $query);

                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        // Check if the department_id from the query matches the selected department_id in the URL
                        $selected = isset($_GET['department_id']) && $_GET['department_id'] == $row['department_id'] ? 'selected' : '';
                        echo "<option value='{$row['department_id']}' $selected>{$row['department_name']}</option>";
                    }
                } else {
                    echo "<option>No departments found</option>";
                }
                ?>
            </select>
        </form>
        <p>Available Nurses: <span id="nurse-count"></span></p>
    </div>
    
    <div id="leaves_report">
        <p>Pending Leaves: <span id="leave-count">0</span></p>
    </div>
    <div id="vacant_report"></div>
    <div id="overworked_report"></div>
</div>