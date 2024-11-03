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
            element.html(`
                <div style="white-space: normal;">
                    <strong>${event.title}</strong><br>
                    ${event.position}<br>
                    ${event.department}<br>
                    ${event.start}<br>
                    ${event.end}
                </div>
            `);
        },
        eventClick: function(event, jsEvent, view) {
            // Populate modal content
            $('#eventNurse').val(event.title);
            $('#eventPosition').val(event.position);
            $('#eventDepartment').val(event.department);
            $('#eventStart').val(event.start.format('MMMM D, YYYY h:mm A'));
            $('#eventEnd').val(event.end ? event.end.format('MMMM D, YYYY h:mm A') : 'N/A');
            
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


document.addEventListener('DOMContentLoaded', function() {
    // Get references to the "All Nurses" checkbox and individual checkboxes
    const selectAllNurses = document.getElementById('selectAllNurses');
    const nurseCheckboxes = document.querySelectorAll('input[name="nurse_id[]"]:not(#selectAllNurses)');

    // Event listener for "All Nurses" checkbox
    selectAllNurses.addEventListener('change', function() {
        // Check or uncheck all individual nurse checkboxes based on "All Nurses" checkbox
        nurseCheckboxes.forEach(function(checkbox) {
            checkbox.checked = selectAllNurses.checked;
        });
    });

    // Optional: Add event listeners for individual nurse checkboxes
    nurseCheckboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            // If any individual checkbox is unchecked, uncheck "All Nurses"
            if (!checkbox.checked) {
                selectAllNurses.checked = false;
            }
            // If all individual checkboxes are checked, check "All Nurses"
            if (Array.from(nurseCheckboxes).every(checkbox => checkbox.checked)) {
                selectAllNurses.checked = true;
            }
        });
    });
});


// Add Schedule Modal
var addScheduleModal = document.getElementById("addScheduleModal");
var addScheduleBtn = document.getElementById("addScheduleBtn");
var addScheduleClose = addScheduleModal.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal
addScheduleBtn.addEventListener("click", function() {
    addScheduleModal.style.display = "block";
});

// When the user clicks on <span> (x), close the modal
addScheduleClose.addEventListener("click", function() {
    addScheduleModal.style.display = "none";
});

// When the user clicks anywhere outside of the modal, close it
window.addEventListener("click", function(event) {
    if (event.target == addScheduleModal) {
        addScheduleModal.style.display = "none";
    }
});

// Generate Schedule Modal
var generateScheduleModal = document.getElementById("generateScheduleModal");
var generateScheduleBtn = document.getElementById("generateScheduleBtn"); // Add a button with this ID to trigger the modal
var generateScheduleClose = generateScheduleModal.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal
generateScheduleBtn.addEventListener("click", function() {
    generateScheduleModal.style.display = "block";
});

// When the user clicks on <span> (x), close the modal
generateScheduleClose.addEventListener("click", function() {
    generateScheduleModal.style.display = "none";
});

// When the user clicks anywhere outside of the modal, close it
window.addEventListener("click", function(event) {
    if (event.target == generateScheduleModal) {
        generateScheduleModal.style.display = "none";
    }
});

// Checkbox behavior for 'Select All Nurses' in generateSchedule
document.addEventListener('DOMContentLoaded', function() {
    const selectAllNursesGenerate = document.getElementById('selectAllNursesGenerate');
    const nurseCheckboxesGenerate = document.querySelectorAll('input[name="nurse_id[]"]:not(#selectAllNursesGenerate)');

    // Event listener for "All Nurses" checkbox
    selectAllNursesGenerate.addEventListener('change', function() {
        // Check or uncheck all individual nurse checkboxes based on "All Nurses" checkbox
        nurseCheckboxesGenerate.forEach(function(checkbox) {
            checkbox.checked = selectAllNursesGenerate.checked;
        });
    });

    // Optional: Add event listeners for individual nurse checkboxes
    nurseCheckboxesGenerate.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            // If any individual checkbox is unchecked, uncheck "All Nurses"
            if (!checkbox.checked) {
                selectAllNursesGenerate.checked = false;
            }
            // If all individual checkboxes are checked, check "All Nurses"
            if (Array.from(nurseCheckboxesGenerate).every(checkbox => checkbox.checked)) {
                selectAllNursesGenerate.checked = true;
            }
        });
    });
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

var message = "This function is not allowed here.";
$(document).on("contextmenu", function(e) {
    alert(message);
    return false; 
});

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


