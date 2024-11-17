$(document).ready(function() {
    // Get nurse_id from URL parameters
    function getNurseIdFromUrl() {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get('nurse_id') || 'all'; // Default to 'all' if not set
    }

    var nurse_id = getNurseIdFromUrl(); // Fetch nurse_id from URL

    // Initialize FullCalendar with events for the selected nurse
    $('#calendar').fullCalendar({
        header: {
            left: 'prev,next,today',
            center: 'title',
            right: 'month,agendaWeek,list'
        },
        selectable: true,
        editable: false,
        buttonText: {
            today: 'Today',
            month: 'Month',
            week: 'Week',
            list: 'List'
        },
        events: 'schedule-module/fetch_schedule.php?nurse_id=' + nurse_id, // Load events based on nurse_id from the URL
        eventRender: function(event, element) {
            element.attr('title', event.title);
        },
        eventClick: function(event, jsEvent, view) {
            // Populate modal content
            $('#eventNurse').val(event.title);
            $('#eventPosition').val(event.position);
            $('#eventDepartment').val(event.department);
        
            // Set the sched_id in the hidden input field or display it
            $('#eventSchedId').val(event.id);  // Display sched_id in modal
        
            // Set the nurse_id in the hidden input field or display it
            $('#eventNurseId').val(event.nurse_id);  // Display nurse_id in modal
        
            // Extract date portion (YYYY-MM-DD) and set for eventDate input
            $('#eventDate').val(event.date);
            console.log("Event Date:", $('#eventDate').val());  // Debugging output
        
            // Extract time portion (HH:mm) and set for eventStartTime and eventEndTime
            $('#eventStart').val(event.start.format('HH:mm'));
            $('#eventEnd').val(event.end ? event.end.format('HH:mm') : '');
        
            // Show the modal
            $('#viewScheduleModal').css('display', 'block');
        }
        
    });

    $('#viewScheduleClose').on('click', function() {
        $('#viewScheduleModal').css('display', 'none');
    });

    // Close the modal when clicking outside the modal content
    $(window).on('click', function(event) {
        if ($(event.target).is('#viewScheduleModal')) {
            $('#viewScheduleModal').css('display', 'none');
        }
    });
});



$(document).ready(function() {
    // Initialize FullCalendar
    $('#calendar').fullCalendar({
        // Your calendar options here
        events: [], // Start with no events
        // Other options...
    });

    // Function to fetch the schedule
    window.fetchSchedule = function() {
        const nurseId = $('#nurseSelect').val();

        // Fetch the schedule based on the selected nurse
        fetch(`fetch_schedule.php?nurse_id=${nurseId}`)
            .then(response => response.json())
            .then(data => {
                // Update FullCalendar with the fetched events
                $('#calendar').fullCalendar('removeEvents'); // Clear existing events
                $('#calendar').fullCalendar('addEventSource', data); // Add new events
            })
            .catch(error => console.error('Error fetching schedule:', error));
    };
});







function viewSchedules(nurse_id) {
    // Send an AJAX request to fetch schedules for the selected nurse
    fetch('schedule-module/fetch_schedule.php?nurse_id=' + nurse_id)
        .then(response => response.json())
        .then(data => {
            let scheduleTable = document.querySelector('#scheduleTable tbody');
            scheduleTable.innerHTML = ''; // Clear previous results
            data.forEach(schedule => {
                let row = `<tr>
                               <td>${schedule.sched_id}</td>
                               <td>${schedule.start_date}</td>
                               <td>${schedule.end_date}</td>
                               <td>${schedule.start_time}</td>
                               <td>${schedule.end_time}</td>
                           </tr>`;
                scheduleTable.insertAdjacentHTML('beforeend', row);
            });
            document.getElementById('scheduleModal').style.display = 'block'; // Show modal
        });
}


function closeModal() {
    document.getElementById('scheduleModal').style.display = 'none';
}



function filterTable() {
    let searchInput = document.getElementById("search").value.toUpperCase();
    let table = document.getElementById("tablerecords");
    let tr = table.getElementsByTagName("tr");
    let recordFound = false;

    // Clear previous no record row if it exists
    let noRecordRow = document.getElementById("no-record-row");
    if (noRecordRow) {
        table.deleteRow(noRecordRow.rowIndex);
    }

    for (let i = 1; i < tr.length; i++) {
        let tdArray = tr[i].getElementsByTagName("td");
        let rowMatches = false;

        for (let j = 0; j < tdArray.length; j++) {
            if (tdArray[j] && tdArray[j].textContent.toUpperCase().includes(searchInput)) {
                rowMatches = true;
                break;
            }
        }

        tr[i].style.display = rowMatches ? "" : "none";
        if (rowMatches) {
            recordFound = true;
        }
    }

    // If no records are found, show "No record found"
    if (!recordFound) {
        noRecordRow = table.insertRow();
        noRecordRow.setAttribute("id", "no-record-row");
        let cell = noRecordRow.insertCell(0);
        cell.colSpan = 8; // Adjust based on the number of columns
        cell.textContent = "No record found.";
        cell.style.textAlign = "center";
    }
}

function navigateWeek(offset) {
    const url = new URL(window.location.href);
    url.searchParams.set('weekOffset', offset);
    window.location.href = url;
}





// Check if the current page is the "Scan" page
if (window.location.href.includes('page=scan')) {
    let scanData = '';  // To accumulate QR code scan data
    let manualTyping = false;  // Track if you are manually typing in an input

    // Listen for the focus event on input fields to stop scanner input
    document.querySelectorAll('input, textarea').forEach((element) => {
        element.addEventListener('focus', function () {
            manualTyping = true;  // User is typing, so disable scanner input
        });

        element.addEventListener('blur', function () {
            manualTyping = false; // Input has lost focus, enable scanner input
        });
    });

    // Function to listen for the keydown event globally
    document.addEventListener('keydown', function (event) {
        // Check if we should ignore the scanner input (when typing in a text field)
        if (manualTyping) return;  // Ignore scanner input while typing

        // If not typing and scanner input is detected (i.e., a key is pressed)
        if (event.key.length === 1) {
            scanData += event.key; // Accumulate the scanned characters
        }

        // If the Enter key is pressed, process the scanned data
        if (event.key === 'Enter' && scanData) {
            event.preventDefault();  // Prevent the default action of the Enter key
            processScanData();       // Process the accumulated scan data
        }
    });

    // Function to process the scanned QR code data
    function processScanData() {
        try {
            let data = JSON.parse(scanData);  // Attempt to parse the scanned data as JSON
            if (data.nurse_id) {
                // If nurse_id exists, redirect to the relevant profile scanning page
                const nurseId = data.nurse_id;
                window.location.href = `index.php?page=scan&subpage=profile&id=${nurseId}`;
            } else {
                console.error("nurse_id not found in scanned data");
            }
        } catch (e) {
            console.error("Invalid JSON format", e);  // Handle invalid scan data format
        }
        scanData = '';  // Clear scan data after processing
    }
} else {
    // Disable scanning functionality on other pages by doing nothing
    console.log("QR scanning disabled on this page.");
}











document.addEventListener("DOMContentLoaded", function() {
    const departmentSelect = document.getElementById("departmentSelect");
    const nurseCountElement = document.getElementById("nurse-count");

    if (departmentSelect) {
        departmentSelect.addEventListener("change", function() {
            const departmentId = departmentSelect.value;

            // Check if the department is "all"
            if (departmentId !== 'all') {
                // If a specific department is selected, fetch nurse count for that department
                fetch('reports-module/fetch_nurse_report.php?department=' + encodeURIComponent(departmentId))
                    .then(response => response.json())
                    .then(data => {
                        if (data.available_nurses !== undefined) {
                            nurseCountElement.textContent = data.available_nurses;
                        } else {
                            nurseCountElement.textContent = "Error: " + (data.message || "Unknown error");
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching nurse data:', error);
                        nurseCountElement.textContent = 'Error fetching data.';
                    });
            } else {
                // If "All Departments" is selected, fetch total nurse count across all departments
                fetch('reports-module/fetch_nurse_report.php?department=all')
                    .then(response => response.json())
                    .then(data => {
                        if (data.available_nurses !== undefined) {
                            nurseCountElement.textContent = data.available_nurses;
                        } else {
                            nurseCountElement.textContent = "Error: " + (data.message || "Unknown error");
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching nurse data for all departments:', error);
                        nurseCountElement.textContent = 'Error fetching data for all departments.';
                    });
            }
        });
    } else {
        console.error('Department select element not found.');
    }
}); // <-- Closing bracket for document.addEventListener("DOMContentLoaded")

document.addEventListener("DOMContentLoaded", function() {
    const leaveCountElement = document.getElementById("leave-count");

    // Function to fetch pending leave data
    function fetchLeaveReport() {
        // Fetch the count of pending leaves from fetch_leave_report.php
        fetch('reports-module/fetch_leave_report.php')
            .then(response => response.json())
            .then(data => {
                if (data.pending_leaves !== undefined) {
                    leaveCountElement.textContent = data.pending_leaves;
                } else {
                    leaveCountElement.textContent = "Error: " + (data.message || "Unknown error");
                }
            })
            .catch(error => {
                console.error('Error fetching leave data:', error);
                leaveCountElement.textContent = 'Error fetching leave data.';
            });
    }

    // Fetch leave count on page load
    fetchLeaveReport();
}); // <-- Closing bracket for do








function toggleDropdown() {
    const dropdown = document.getElementById("dropdownOptions");
    dropdown.style.display = dropdown.style.display === "none" || dropdown.style.display === "" ? "block" : "none";
}

// Optional: Close the dropdown if the user clicks outside of it
document.addEventListener("click", function (event) {
    const dropdown = document.getElementById("dropdownOptions");
    const input = document.getElementById("nurseDropdown");
    if (!input.contains(event.target) && !dropdown.contains(event.target)) {
        dropdown.style.display = "none";
    }
});



function toggleSelectAll() {
    const isChecked = document.getElementById('selectAll').checked;
    document.querySelectorAll('.nurse-option').forEach(function(checkbox) {
        checkbox.checked = isChecked;  // Set the checked status for all checkboxes
    });

    // Call the update function to update the hidden input and dropdown
    updateSelectedNurses();
}

function updateSelectedNurses() {
    let selectedNurses = [];
    let selectedNursesNames = [];

    document.querySelectorAll('.nurse-option:checked').forEach(function(checkbox) {
        selectedNurses.push(checkbox.value);
        selectedNursesNames.push(checkbox.nextSibling.textContent.trim());
    });

    // Update the value of the hidden input field
    document.getElementById('nurse_id').value = selectedNurses.join(',');
    
    // Update the text input field with selected nurse names
    document.getElementById('nurseDropdown').value = selectedNursesNames.join(', ');

    // Update the "Select All" checkbox based on the selection
    const allSelected = document.querySelectorAll('.nurse-option').length === selectedNurses.length;
    document.getElementById('selectAll').checked = allSelected;
}

// DISABLE RIGHT CLICK
//$(document).on("contextmenu", function(e) {
//    return false; 
//});


// REPORTS
document.addEventListener("DOMContentLoaded", function() {
    const departmentSelect = document.getElementById("departmentSelect");
    const nurseCountElement = document.getElementById("nurse-count");

    const ctx = document.getElementById('nurseChart').getContext('2d');
    let nurseChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Total Nurses', 'Available Nurses'],
            datasets: [{
                label: 'Nurse Count',
                data: [0, 0],
                backgroundColor: ['rgba(54, 162, 235, 0.2)', 'rgba(75, 192, 192, 0.2)'],
                borderColor: ['rgba(54, 162, 235, 0.2)', 'rgba(75, 192, 192, 0.2)'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true, 
                    min: 0,
                    max: 20
                }
            }
        }
    });

     if (departmentSelect) {
        if (!departmentSelect.value) {
            departmentSelect.value = 'all';
        }

        const departmentId = departmentSelect.value;
        fetchNurseData(departmentId);

        departmentSelect.addEventListener("change", function() {
            const departmentId = departmentSelect.value;
            fetchNurseData(departmentId);
        });

        function fetchNurseData(departmentId) {
            fetch('processes/process.nurse.php?action=fetch_by_department&department_id=' + encodeURIComponent(departmentId))
                .then(response => response.json())
                .then(data => {
                    if (data.available_nurses !== undefined && data.total_nurses !== undefined) {
                        nurseCountElement.textContent = data.available_nurses;
                        nurseChart.data.datasets[0].data = [data.total_nurses, data.available_nurses];
                        nurseChart.update();
                    } else {
                        nurseCountElement.textContent = "Error: " + (data.message || "Unknown error");
                    }
                })
                .catch(error => {
                    console.error('Error fetching nurse data:', error);
                    nurseCountElement.textContent = 'Error fetching data.';
                });
        }
    } else {
        console.error('Department select element not found.');
    }
});

document.addEventListener("DOMContentLoaded", function() {
    const departmentSelectLeave = document.getElementById("departmentSelectLeave");
    const leaveCountElement = document.getElementById("leave-count");

    const ctx = document.getElementById('leaveChart').getContext('2d');
    let leaveChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Pending Leaves'],
            datasets: [{
                label: 'Pending Leaves Count',
                data: [0], 
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    min: 0,
                    max: 20
                }
            }
        }
    });

    function fetchPendingLeaves(department = 'all') {
        const url = `reports-module/fetch_leave_report.php?department=${encodeURIComponent(department)}`;
        
        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.pending_leaves !== undefined) {
                    leaveCountElement.textContent = data.pending_leaves;

                    leaveChart.data.datasets[0].data = [data.pending_leaves];
                    leaveChart.update();
                } else {
                    leaveCountElement.textContent = "Error: " + (data.message || "Unknown error");
                }
            })
            .catch(error => {
                console.error('Error fetching leave data:', error);
                leaveCountElement.textContent = 'Error fetching leave data.';
            });
    }

    // Set default department to 'all' if no department is selected
    const initialDepartment = departmentSelectLeave ? departmentSelectLeave.value : 'all';
    fetchPendingLeaves(initialDepartment); // Fetch initial data

    // Add event listener to department selection dropdown
    if (departmentSelectLeave) {
        departmentSelectLeave.addEventListener("change", function() {
            const selectedDepartment = departmentSelectLeave.value;
            fetchPendingLeaves(selectedDepartment); // Fetch new data when department is changed
        });
    }
});

document.addEventListener("DOMContentLoaded", function () {
    const ctxDonut = document.getElementById('donutChart').getContext('2d');

    // Initialize Donut Chart
    let donutChart = new Chart(ctxDonut, {
        type: 'doughnut',
        data: {
            labels: ['Available Nurses', 'Pending Leaves'], // Adjust labels as needed
            datasets: [{
                label: 'Overview',
                data: [0, 0], // Initial values
                backgroundColor: [
                    'rgba(75, 192, 192, 0.6)', // Available Nurses
                    'rgba(255, 99, 132, 0.6)', // Pending Leaves
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 99, 132, 1)',
                ],
                borderWidth: 1,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            return `${label}: ${value}`;
                        }
                    }
                }
            }
        }
    });

    // Fetch data for Donut Chart
    function updateDonutChart() {
        const departmentId = document.getElementById('departmentSelect')?.value || 'all';
        Promise.all([
            fetch(`reports-module/fetch_nurse_report.php?department=${encodeURIComponent(departmentId)}`)
                .then(response => response.json()),
            fetch(`reports-module/fetch_leave_report.php?department=${encodeURIComponent(departmentId)}`)
                .then(response => response.json())
        ]).then(([nurseData, leaveData]) => {
            const availableNurses = nurseData?.available_nurses || 0;
            const pendingLeaves = leaveData?.pending_leaves || 0;

            donutChart.data.datasets[0].data = [availableNurses, pendingLeaves];
            donutChart.update();
        }).catch(error => console.error('Error fetching data for donut chart:', error));
    }

    // Fetch and update data on page load and department change
    updateDonutChart();

    const departmentSelect = document.getElementById('departmentSelect');
    if (departmentSelect) {
        departmentSelect.addEventListener('change', updateDonutChart);
    }
});


