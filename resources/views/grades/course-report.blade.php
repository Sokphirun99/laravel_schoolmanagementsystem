@extends('voyager::master')

@section('page_title', 'Course Report - ' . $course->name)

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-bar-chart"></i> Course Report: {{ $course->name }}
    </h1>
    <a href="{{ route('grades.assignments', $course->id) }}" class="btn btn-warning">
        <i class="voyager-list"></i> Assignments
    </a>
    <a href="{{ route('grades.index') }}" class="btn btn-primary">
        <i class="voyager-book"></i> All Courses
    </a>
@stop

@section('content')
    <div class="page-content container-fluid">
        <!-- Summary Statistics -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="voyager-pirate"></i> Course Summary Statistics</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="card text-center">
                                    <div class="card-header">Students Enrolled</div>
                                    <div class="card-body">
                                        <h3>{{ $summary['student_count'] }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-center">
                                    <div class="card-header">Average Score</div>
                                    <div class="card-body">
                                        <h3>{{ $summary['average_score'] }}%</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-center">
                                    <div class="card-header">Highest Score</div>
                                    <div class="card-body">
                                        <h3>{{ $summary['highest_score'] }}%</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-center">
                                    <div class="card-header">Lowest Score</div>
                                    <div class="card-body">
                                        <h3>{{ $summary['lowest_score'] }}%</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row" style="margin-top: 20px;">
                            <div class="col-md-6">
                                <h4>Grade Distribution</h4>
                                <canvas id="gradeDistribution" width="100%" height="50"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Students List -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="voyager-people"></i> Student Performance</h3>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="dataTable" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Student ID</th>
                                        <th>Name</th>
                                        <th>Final Score</th>
                                        <th>Letter Grade</th>
                                        <th class="actions">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($summary['students'] ?? [] as $gradeInfo)
                                        <tr>
                                            <td>{{ $gradeInfo['student']->id }}</td>
                                            <td>{{ $gradeInfo['student']->user->name ?? $gradeInfo['student']->name }}</td>
                                            <td>{{ $gradeInfo['final_score'] }}%</td>
                                            <td>
                                                <span class="label label-{{ $gradeInfo['letter_grade'] === 'A' ? 'success' : ($gradeInfo['letter_grade'] === 'F' ? 'danger' : 'primary') }}">
                                                    {{ $gradeInfo['letter_grade'] }}
                                                </span>
                                            </td>
                                            <td class="no-sort no-click bread-actions">
                                                <a href="{{ route('grades.student.course.report', ['course' => $course->id, 'student' => $gradeInfo['student']->id]) }}" title="View Report" class="btn btn-sm btn-info">
                                                    <i class="voyager-file-text"></i> <span class="hidden-xs hidden-sm">View Report</span>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
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
            $('#dataTable').DataTable({
                "order": [[2, "desc"]]
            });
            
            // Grade distribution chart
            const ctx = document.getElementById('gradeDistribution').getContext('2d');
            const distribution = @json($summary['grade_distribution']);
            
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: Object.keys(distribution),
                    datasets: [{
                        label: 'Number of Students',
                        data: Object.values(distribution),
                        backgroundColor: [
                            'rgba(75, 192, 192, 0.6)', // A
                            'rgba(54, 162, 235, 0.6)', // B
                            'rgba(255, 206, 86, 0.6)', // C
                            'rgba(255, 159, 64, 0.6)', // D
                            'rgba(255, 99, 132, 0.6)',  // F
                        ],
                        borderColor: [
                            'rgba(75, 192, 192, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(255, 159, 64, 1)',
                            'rgba(255, 99, 132, 1)',
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        });
    </script>
@stop
