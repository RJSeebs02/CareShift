const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
let currentMonth = new Date().getMonth();
let currentYear = new Date().getFullYear();

function loadCalendar(month, year) {
    const daysContainer = document.getElementById('calendar-days');
    const monthYear = document.getElementById('month-year');
    daysContainer.innerHTML = '';

    // Set month-year display
    monthYear.innerHTML = `${monthNames[month]} ${year}`;

    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();

    // Blank spaces for days before the first day of the month
    for (let i = 0; i < firstDay; i++) {
        const blankDay = document.createElement('div');
        daysContainer.appendChild(blankDay);
    }

    // Add days of the month
    for (let i = 1; i <= daysInMonth; i++) {
        const dayElement = document.createElement('div');
        dayElement.innerText = i;
        if (i === new Date().getDate() && month === new Date().getMonth() && year === new Date().getFullYear()) {
            dayElement.classList.add('active'); // Highlight current day
        }
        daysContainer.appendChild(dayElement);
    }
}

function prevMonth() {
    currentMonth = (currentMonth === 0) ? 11 : currentMonth - 1;
    currentYear = (currentMonth === 11) ? currentYear - 1 : currentYear;
    loadCalendar(currentMonth, currentYear);
}

function nextMonth() {
    currentMonth = (currentMonth === 11) ? 0 : currentMonth + 1;
    currentYear = (currentMonth === 0) ? currentYear + 1 : currentYear;
    loadCalendar(currentMonth, currentYear);
}

// Load current month on page load
window.onload = function() {
    loadCalendar(currentMonth, currentYear);
}
