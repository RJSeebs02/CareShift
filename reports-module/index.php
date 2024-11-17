<?php 
  $con = new mysqli('localhost','root','','db_careshift');
  $query = $con->query("
    SELECT d.department_name, COUNT(n.nurse_id) AS nurse_count
    FROM department d
    LEFT JOIN nurse n ON d.department_id = n.department_id
    GROUP BY d.department_name;
  ");

  foreach($query as $data)
  {
    $nursecount[] = $data['nurse_count'];
    $department[] = $data['department_name'];
  }

?>



<div class="content_wrapper">
    <div class="heading">
        <h1><i class="fas fa-solid fa-chart-line"></i>&nbspReports</h1>
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
    <canvas id="NurseCountDeptChart"></canvas>
</div>

<script>
    const data = {
        labels: <?php echo json_encode($department); ?>,  // Department names
        datasets: [{
            label: 'Nurse Count',
            data: <?php echo json_encode($nursecount); ?>,  // Nurse count data
            backgroundColor: [
                'rgb(255, 99, 132)',
                'rgb(54, 162, 235)',
                'rgb(255, 205, 86)'
            ],
            hoverOffset: 4
        }]
    };

    const config = {
        type: 'doughnut',  // Keep doughnut chart type
        data: data,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'left',  // Set legend position to the left side of the chart
                },
            },
            // You can also modify other chart options here as needed
        }
    };

    var NurseCountDeptChart = new Chart(
        document.getElementById('NurseCountDeptChart'),
        config
    );
</script>
