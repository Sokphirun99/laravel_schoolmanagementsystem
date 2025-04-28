@extends('voyager::master')

@section('page_title', 'View Teacher')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-study"></i> Viewing Teacher &nbsp;
        <a href="{{ route('voyager.teachers.index') }}" class="btn btn-warning">
            <i class="glyphicon glyphicon-list"></i> <span class="hidden-xs hidden-sm">Return to List</span>
        </a>
        <a href="{{ route('voyager.teachers.edit', $teacher->id) }}" class="btn btn-info">
            <i class="glyphicon glyphicon-pencil"></i> <span class="hidden-xs hidden-sm">Edit</span>
        </a>
    </h1>
@stop

@section('content')
    <div class="page-content read container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered" style="padding-bottom:5px;">
                    <!-- Teacher Profile Overview -->
                    <div class="row">
                        <div class="col-md-3">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Profile Photo</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <img class="img-responsive" src="{{ $teacher->user && $teacher->user->avatar ? Voyager::image($teacher->user->avatar) : asset('images/default-teacher.png') }}" alt="{{ $teacher->first_name }}">
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Basic Information</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h4>{{ $teacher->first_name }} {{ $teacher->last_name }}</h4>
                                        <p><strong>Employee ID:</strong> {{ $teacher->employee_id }}</p>
                                        <p><strong>Date of Birth:</strong> {{ $teacher->date_of_birth }}</p>
                                        <p><strong>Gender:</strong> {{ ucfirst($teacher->gender) }}</p>
                                        <p><strong>Status:</strong> <span class="label label-{{ $teacher->status == 'active' ? 'success' : 'warning' }}">{{ ucfirst($teacher->status) }}</span></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Department:</strong> {{ $teacher->department }}</p>
                                        <p><strong>Designation:</strong> {{ $teacher->designation }}</p>
                                        <p><strong>Join Date:</strong> {{ $teacher->join_date }}</p>
                                        <p><strong>Qualification:</strong> {{ $teacher->qualification }}</p>
                                        <p><strong>Experience:</strong> {{ $teacher->experience }} years</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Contact Information</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Address:</strong> {{ $teacher->address }}</p>
                                        <p><strong>Phone:</strong> {{ $teacher->phone }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Email:</strong> {{ $teacher->user ? $teacher->user->email : 'Not Available' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabs for Additional Information -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel-body" style="padding-top:0;">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a data-toggle="tab" href="#classes">Assigned Classes</a></li>
                                    <li><a data-toggle="tab" href="#subjects">Assigned Subjects</a></li>
                                    <li><a data-toggle="tab" href="#timetable">Timetable</a></li>
                                </ul>

                                <div class="tab-content">
                                    <div id="classes" class="tab-pane fade in active">
                                        <h3>Assigned Classes</h3>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Class</th>
                                                    <th>Section</th>
                                                    <th>Role</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($teacher->sections as $section)
                                                    <tr>
                                                        <td>{{ $section->class->name }}</td>
                                                        <td>{{ $section->name }}</td>
                                                        <td>Class Teacher</td>
                                                        <td>
                                                            <a href="{{ route('voyager.sections.show', $section->id) }}" class="btn btn-sm btn-info">
                                                                <i class="voyager-eye"></i> View
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center">No classes assigned</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>

                                    <div id="subjects" class="tab-pane fade">
                                        <h3>Assigned Subjects</h3>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Subject</th>
                                                    <th>Class</th>
                                                    <th>Section</th>
                                                    <th>Academic Year</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($teacher->subjects as $subject)
                                                    <tr>
                                                        <td>{{ $subject->name }}</td>
                                                        <td>{{ $subject->pivot->section->class->name }}</td>
                                                        <td>{{ $subject->pivot->section->name }}</td>
                                                        <td>{{ $subject->pivot->academicYear->name }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center">No subjects assigned</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>

                                    <div id="timetable" class="tab-pane fade">
                                        <h3>Teacher's Timetable</h3>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Day</th>
                                                    <th>Period</th>
                                                    <th>Time</th>
                                                    <th>Subject</th>
                                                    <th>Class</th>
                                                    <th>Section</th>
                                                    <th>Room</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($teacher->timetables as $timetable)
                                                    <tr>
                                                        <td>{{ ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'][$timetable->day_of_week - 1] }}</td>
                                                        <td>{{ $timetable->period_number }}</td>
                                                        <td>{{ $timetable->start_time }} - {{ $timetable->end_time }}</td>
                                                        <td>{{ $timetable->subject->name }}</td>
                                                        <td>{{ $timetable->section->class->name }}</td>
                                                        <td>{{ $timetable->section->name }}</td>
                                                        <td>{{ $timetable->room_number }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="7" class="text-center">No timetable entries found</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
