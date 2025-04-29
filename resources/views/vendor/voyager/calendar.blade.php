@extends('voyager::master')

@section('page_title', 'Academic Calendar')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-calendar"></i> Academic Calendar
        @if(isset($currentAcademicYear))
            <span class="text-muted">{{ $currentAcademicYear->name }}</span>
        @endif
    </h1>
@stop

@section('css')
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css' />
    <style>
        .fc-event {
            cursor: pointer;
        }
    </style>
@stop

@section('content')
    <div class="page-content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Calendar Event Modal -->
    <div class="modal fade" id="eventModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Event Details</h4>
                </div>
                <div class="modal-body">
                    <h4 id="eventTitle"></h4>
                    <p><strong>Start:</strong> <span id="eventStart"></span></p>
                    <p><strong>End:</strong> <span id="eventEnd"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js'></script>
    <script>
        $(document).ready(function() {
            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay,listMonth'
                },
                events: {!! json_encode($events) !!},
                eventClick: function(calEvent, jsEvent, view) {
                    $('#eventTitle').text(calEvent.title);
                    $('#eventStart').text(moment(calEvent.start).format('MMMM D, YYYY'));

                    if (calEvent.end) {
                        $('#eventEnd').text(moment(calEvent.end).subtract(1, 'days').format('MMMM D, YYYY'));
                        $('#eventEnd').parent().show();
                    } else {
                        $('#eventEnd').parent().hide();
                    }

                    $('#eventModal').modal();
                }
            });
        });
    </script>
@stop
