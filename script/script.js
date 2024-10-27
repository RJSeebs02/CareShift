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
            right: 'month,agendaWeek'
        },
        selectable: true,
        editable: false,
        buttonText: {
            today: 'Today',
            month: 'Month',
            week: 'Week',
            day: 'Day'
        },
        events: 'schedule-module/fetch_schedule.php?nurse_id=' + nurse_id, // Load events based on nurse_id from the URL
        eventRender: function(event, element) {
            element.attr('title', event.title);
        },
        eventClick: function(event, jsEvent, view) {
            var start = event.start.format('YYYY-MM-DD HH:mm');
            var end = event.end ? event.end.format('YYYY-MM-DD HH:mm') : 'N/A';
            alert(event.title + '\nStart Time: ' + start + '\nEnd Time: ' + end);
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

// Multiple Schedule Modal
var multipleScheduleModal = document.getElementById("multipleScheduleModal");
var multipleScheduleBtn = document.getElementById("multipleScheduleBtn");
var multipleScheduleClose = multipleScheduleModal.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal
multipleScheduleBtn.addEventListener("click", function() {
    multipleScheduleModal.style.display = "block";
});

// When the user clicks on <span> (x), close the modal
multipleScheduleClose.addEventListener("click", function() {
    multipleScheduleModal.style.display = "none";
});

// When the user clicks anywhere outside of the modal, close it
window.addEventListener("click", function(event) {
    if (event.target == multipleScheduleModal) {
        multipleScheduleModal.style.display = "none";
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


// Prevent context menu from appearing
/*var message = "This function is not allowed here.";
$(document).on("contextmenu", function(e) {
    alert(message);
    return false; 
});*/