$(document).ready(function() {
    $('#calendar').fullCalendar({
        header: {
            left: 'prev,today,next',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
        selectable: true,
        editable: false,
        buttonText: {
            today: 'Today',
            month: 'Month',
            week: 'Week',
            day: 'Day'
        },
        events: [
            {
                title: 'Event 1',
                start: '2024-10-15',
                end: '2024-10-17'
            },
            {
                title: 'Event 2',
                start: '2024-10-18'
            }
        ]
    });
});