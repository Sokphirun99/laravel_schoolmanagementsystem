@extends('voyager::master')

@section('content')
    <div class="page-content">
        @include('voyager::alerts')
        <div class="analytics-container">
            <!-- School Info Summary -->
            <div class="panel panel-bordered panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="voyager-school"></i> School Overview</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <h1>{{ $studentCount }}</h1>
                            <p>Students</p>
                        </div>
                        <div class="col-md-3 text-center">
                            <h1>{{ $teacherCount }}</h1>
                            <p>Teachers</p>
                        </div>
                        <div class="col-md-3 text-center">
                            <h1>{{ $classCount }}</h1>
                            <p>Classes</p>
                        </div>
                        <div class="col-md-3 text-center">
                            <h1>{{ $sectionCount }}</h1>
                            <p>Sections</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Academic Year Info -->
            @if(isset($currentAcademicYear))
            <div class="panel panel-bordered panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="voyager-calendar"></i> Current Academic Year</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-4">
                            <p><strong>Name:</strong> {{ $currentAcademicYear->name }}</p>
                        </div>
                        <div class="col-md-4">
                            <p><strong>Start Date:</strong> {{ $currentAcademicYear->start_date->format('M d, Y') }}</p>
                        </div>
                        <div class="col-md-4">
                            <p><strong>End Date:</strong> {{ $currentAcademicYear->end_date->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Widget Container -->
            <div class="row widget-container">
                @if(isset($widgets))
                    @foreach($widgets as $widget)
                        @if($widget->shouldBeDisplayed())
                            <div class="col-md-4">
                                {!! $widget->run() !!}
                            </div>
                        @endif
                    @endforeach
                @endif
            </div>

            <!-- Recent Activity -->
            <div class="row">
                <!-- Recent Students -->
                <div class="col-md-6">
                    <div class="panel panel-bordered panel-success">
                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="voyager-people"></i> Recent Students</h3>
                        </div>
                        <div class="panel-body">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Admission #</th>
                                        <th>Class</th>
                                        <th>Joined</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentStudents as $student)
                                        <tr>
                                            <td>{{ $student->full_name }}</td>
                                            <td>{{ $student->admission_number }}</td>
                                            <td>
                                                @if($student->section)
                                                    {{ $student->section->class->name }} - {{ $student->section->name }}
                                                @else
                                                    Not Assigned
                                                @endif
                                            </td>
                                            <td>{{ $student->created_at->diffForHumans() }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">No students found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Recent Teachers -->
                <div class="col-md-6">
                    <div class="panel panel-bordered panel-warning">
                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="voyager-study"></i> Recent Teachers</h3>
                        </div>
                        <div class="panel-body">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Employee #</th>
                                        <th>Department</th>
                                        <th>Joined</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentTeachers as $teacher)
                                        <tr>
                                            <td>{{ $teacher->first_name }} {{ $teacher->last_name }}</td>
                                            <td>{{ $teacher->employee_id }}</td>
                                            <td>{{ $teacher->department }}</td>
                                            <td>{{ $teacher->created_at->diffForHumans() }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">No teachers found</td>
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
@stop
