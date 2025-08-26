@extends('voyager::master')

@section('page_title', 'Overall Student Report')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-graduation-cap"></i> Overall Report: {{ $student->user->name ?? $student->name }}
    </h1>
    <a href="{{ route('grades.index') }}" class="btn btn-warning">
        <i class="voyager-book"></i> Courses
    </a>
@stop

@section('content')
    <div class="page-content container-fluid">
        <!-- Student Info -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">Student Summary</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-4">
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Student ID:</th>
                                        <td>{{ $student->id }}</td>
                                    </tr>
                                    <tr>
                                        <th>Name:</th>
                                        <td>{{ $student->user->name ?? $student->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email:</th>
                                        <td>{{ $student->user->email ?? 'N/A' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-4">
                                <div class="card text-center">
                                    <div class="card-header">Courses Enrolled</div>
                                    <div class="card-body">
                                        <h3>{{ count($courses) }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card text-center">
                                    <div class="card-header">Overall GPA</div>
                                    <div class="card-body">
                                        <h3>{{ $overallGpa ?? 'N/A' }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Course List -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-heading">
                        <h3 class="panel-title">Course Grades</h3>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Course</th>
                                        <th>Teacher</th>
                                        <th>Final Score</th>
                                        <th>Letter Grade</th>
                                        <th class="actions">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($courses as $course)
                                        <tr>
                                            <td>{{ $course->name }}</td>
                                            <td>{{ $course->teacher->name ?? 'Not Assigned' }}</td>
                                            <td>{{ $courseGrades[$course->id]['final_score'] ?? 'N/A' }}%</td>
                                            <td>
                                                <span class="label label-{{ 
                                                    isset($courseGrades[$course->id]['letter_grade']) && $courseGrades[$course->id]['letter_grade'] === 'A' ? 
                                                    'success' : (isset($courseGrades[$course->id]['letter_grade']) && $courseGrades[$course->id]['letter_grade'] === 'F' ? 
                                                    'danger' : 'primary') 
                                                }}">
                                                    {{ $courseGrades[$course->id]['letter_grade'] ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td class="no-sort no-click bread-actions">
                                                <a href="{{ route('grades.student.course.report', ['course' => $course->id, 'student' => $student->id]) }}" 
                                                   title="View Detailed Report" class="btn btn-sm btn-info">
                                                    <i class="voyager-file-text"></i> <span class="hidden-xs hidden-sm">View Details</span>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">No courses found for this student.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Grade Chart -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-heading">
                        <h3 class="panel-title">Performance Across Courses</h3>
                    </div>
                    <div class="panel-body">
                        <canvas id="coursePerformanceChart" height="100"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(document).ready(function () {
            const courses = @json($courses);
            const courseGrades = @json($courseGrades);
            
            const labels = courses.map(c => c.name);
            const scores = courses.map(c => courseGrades[c.id] ? courseGrades[c.id].final_score : 0);
            
            const ctx = document.getElementById('coursePerformanceChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Course Score (%)',
                        data: scores,
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100
                        }
                    }
                }
            });
        });
    </script>
@stop
