<div class="content_wrapper">
    <div class="heading">
        <h1><i class="fas fa-solid fa-chart-line"></i>&nbspReports</h1>
    </div>
</div>

<div class="content_layer1_wrapper">

    <div id="nurses_report">
        <form id="departmentForm" method="GET">
            <label for="departmentSelect">Nurse Count</label>
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
        <form id="departmentForm" method="GET">
            <label for="departmentSelectLeave">Pending Leaves Count</label>
            <select id="departmentSelectLeave" name="department_id">
                <option value="all">All Departments</option>
                <?php
                $query = "SELECT department_id, department_name FROM department";
                $result = mysqli_query($con, $query);

                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $selected = isset($_GET['department_id']) && $_GET['department_id'] == $row['department_id'] ? 'selected' : '';
                        echo "<option value='{$row['department_id']}' $selected>{$row['department_name']}</option>";
                    }
                } else {
                    echo "<option>No departments found</option>";
                }
                ?>
            </select>
        </form>
        <p>Pending Leaves: <span id="leave-count"></span></p>
    </div>

</div>

<div class="charts-container">
    <canvas id="nurseChart"></canvas>
    <canvas id="leaveChart"></canvas>
</div>

<div id="qr-reader" style="width: 300px;"></div>
<button onclick="showQrModal()">Scan QR Code</button>

<div id="qrModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeQrModal()">&times;</span>
        <h2>QR Code Content</h2>
        <p id="qr-content">No content scanned yet.</p>
    </div>
</div>