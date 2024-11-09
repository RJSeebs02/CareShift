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

$(document).on("contextmenu", function(e) {
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

function navigateWeek(offset) {
    const url = new URL(window.location.href);
    url.searchParams.set('weekOffset', offset);
    window.location.href = url;
}





// Global variable to store scanned data
let scanData = '';

// Function to listen for the keydown event globally
document.addEventListener('keydown', function(event) {
    // Get the focused element (if any)
    const activeElement = document.activeElement;

    // Check if the key pressed is a valid character and if we're not focusing on an input field
    if (event.key.length === 1) {
        // If any input field is focused, prevent the character from being typed into that field
        if (activeElement && (activeElement.tagName.toLowerCase() === 'input' || activeElement.tagName.toLowerCase() === 'textarea')) {
            event.preventDefault();  // Prevent the scanner input from entering the focused field
        }

        // Capture the QR code input data
        scanData += event.key;
    } 
    else if (event.key === 'Enter') {
        // When the scanner sends the "Enter" key, process the scan data
        try {
            let data = JSON.parse(scanData);  // Attempt to parse the scan data as JSON
            if (data.nurse_id) {
                // Redirect to the page with the nurse_id
                const nurseId = data.nurse_id;
                window.location.href = `index.php?page=nurses&subpage=profile&id=${nurseId}`;
            } else {
                console.error("nurse_id not found in scanned data");
            }
        } catch (e) {
            console.error("Invalid JSON format", e);
        } finally {
            // Clear the scan data after processing
            scanData = '';
        }
    }
});



