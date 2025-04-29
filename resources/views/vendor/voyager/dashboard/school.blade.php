@extends('voyager::master')

@section('content')
    <div class="page-content">
        @include('voyager::alerts')

        <div class="analytics-container">
            <!-- Quick Stats Row -->
            <div class="row">
                <div class="col-md-3">
                    <div class="panel widget center bgimage" style="background-image:url({{ asset('images/widget-backgrounds/students.jpg') }});">
                        <div class="dimmer"></div>
                        <div class="panel-content">
                            <i class="voyager-people"></i>
                            <h4>{{ \App\Models\Student::count() }} Students</h4>
                            <p>Total enrolled students</p>
                            <a href="{{ route('voyager.students.index') }}" class="btn btn-primary">View All</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="panel widget center bgimage" style="background-image:url({{ asset('images/widget-backgrounds/teachers.jpg') }});">
                        <div class="dimmer"></div>
                        <div class="panel-content">
                            <i class="voyager-study"></i>
                            <h4>{{ \App\Models\Teacher::count() }} Teachers</h4>
                            <p>Total active teachers</p>
                            <a href="{{ route('voyager.teachers.index') }}" class="btn btn-primary">View All</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="panel widget center bgimage" style="background-image:url({{ asset('images/widget-backgrounds/classes.jpg') }});">
                        <div class="dimmer"></div>
                        <div class="panel-content">
                            <i class="voyager-book"></i>
                            <h4>{{ \App\Models\ClassRoom::count() }} Classes</h4>
                            <p>Active class sections</p>
                            <a href="{{ route('voyager.classes.index') }}" class="btn btn-primary">View All</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="panel widget center bgimage" style="background-image:url({{ asset('images/widget-backgrounds/exams.jpg') }});">
                        <div class="dimmer"></div>
                        <div class="panel-content">
                            <i class="voyager-file-text"></i>
                            <h4>{{ \App\Models\Exam::where('exam_date', '>=', \Carbon\Carbon::now())->count() }} Exams</h4>
                            <p>Upcoming examinations</p>
                            <a href="{{ route('voyager.exams.index') }}" class="btn btn-primary">View All</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-bordered">
                        <div class="panel-header">
                            <h3 class="panel-title">Student Enrollment by Class</h3>
                        </div>
                        <div class="panel-body">
                            <canvas id="enrollmentChart" width="100%" height="45"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="panel panel-bordered">
                        <div class="panel-header">
                            <h3 class="panel-title">Attendance Overview (Last 7 days)</h3>
                        </div>
                        <div class="panel-body">
                            <canvas id="attendanceChart" width="100%" height="45"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Tables Row -->
            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-bordered">
                        <div class="panel-header">
                            <h3 class="panel-title">Upcoming Exams</h3>
                        </div>
                        <div class="panel-body">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Exam</th>
                                        <th>Class</th>
                                        <th>Subject</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($upcomingExams as $exam)
                                    <tr>
                                        <td>{{ $exam->name }}</td>
                                        <td>{{ $exam->class->name }}</td>
                                        <td>{{ $exam->subject->name }}</td>
                                        <td>{{ $exam->exam_date->format('M d, Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="panel panel-bordered">
                        <div class="panel-header">
                            <h3 class="panel-title">Recent Fee Payments</h3>
                        </div>
                        <div class="panel-body">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Student</th>
                                        <th>Amount</th>
                                        <th>Payment Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentPayments as $payment)
                                    <tr>
                                        <td>{{ $payment->student->name }}</td>
                                        <td>${{ number_format($payment->amount, 2) }}</td>
                                        <td>{{ $payment->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <span class="badge badge-success">{{ $payment->status }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- School Calendar -->
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-bordered">
                        <div class="panel-header">
                            <h3 class="panel-title">Academic Calendar</h3>
                        </div>
                        <div class="panel-body">
                            <div id="schoolCalendar"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script>
        $(document).ready(function() {
            // Enrollment Chart
            var enrollmentCtx = document.getElementById('enrollmentChart').getContext('2d');
            var enrollmentChart = new Chart(enrollmentCtx, {
                type: 'bar',
                data: {
                    labels: {!! $classCounts->pluck('name') !!},
                    datasets: [{
                        label: 'Number of Students',
                        data: {!! $classCounts->pluck('students_count') !!},
                        backgroundColor: '#3498db'
                    }]
                }
            });

            // Attendance Chart
            var attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
            var attendanceChart = new Chart(attendanceCtx, {
                type: 'pie',
                data: {
                    labels: {!! $attendanceStats->pluck('status') !!},
                    datasets: [{
                        data: {!! $attendanceStats->pluck('count') !!},
                        backgroundColor: ['#2ecc71', '#e74c3c', '#f39c12']
                    }]
                }
            });
        });
    </script>
@stop
