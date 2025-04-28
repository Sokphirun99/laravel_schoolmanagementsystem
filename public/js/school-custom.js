/* filepath: /Users/phirun/Projects/laravel_schoolmanagementsystem/public/js/school-custom.js */
$(document).ready(function() {
    // Initialize datepickers with school year format
    $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
    });

    // Custom confirmation for attendance submission
    $('.submit-attendance').on('click', function(e) {
        e.preventDefault();
        var form = $(this).closest('form');

        swal({
            title: "Confirm Attendance",
            text: "Are you sure you want to submit this attendance record?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willSubmit) => {
            if (willSubmit) {
                form.submit();
            }
        });
    });

    // Custom charts for school dashboard
    if ($('#enrollmentChart').length) {
        var ctx = document.getElementById('enrollmentChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5', 'Grade 6'],
                datasets: [{
                    label: 'Number of Students',
                    data: [45, 37, 60, 53, 42, 38],
                    backgroundColor: '#3498db'
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // Calendar for academic events
    if ($('#schoolCalendar').length) {
        $('#schoolCalendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            events: '/api/academic-events',
            editable: true,
            eventLimit: true
        });
    }
});
