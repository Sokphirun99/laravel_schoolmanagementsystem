@extends('voyager::master')

@section('page_title', 'Student Course Report')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-person"></i> Student Report: {{ $gradeReport['student']->user->name ?? $gradeReport['student']->name }}
    </h1>
    <a href="{{ route('grades.course.report', $gradeReport['course']->id) }}" class="btn btn-warning">
        <i class="voyager-bar-chart"></i> Back to Course Report
    </a>
@stop

@section('content')
    <div class="page-content container-fluid">
        <!-- Student Info -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">Course Report Card</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Student Name:</th>
                                        <td>{{ $gradeReport['student']->user->name ?? $gradeReport['student']->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Course:</th>
                                        <td>{{ $gradeReport['course']->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Teacher:</th>
                                        <td>{{ $gradeReport['course']->teacher->name ?? 'Not Assigned' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <div class="card text-center">
                                    <div class="card-header">Final Grade</div>
                                    <div class="card-body">
                                        <h1 style="font-size: 60px; margin: 10px 0;">{{ $gradeReport['letter_grade'] }}</h1>
                                        <h3>{{ $gradeReport['final_score'] }}%</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Assignment Details -->
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-heading">
                        <h3 class="panel-title">Assignment Details</h3>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Assignment</th>
                                        <th>Type</th>
                                        <th>Due Date</th>
                                        <th>Score</th>
                                        <th>Percentage</th>
                                        <th>Weighted Score</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($gradeReport['assignments'] as $assignment)
                                        <tr>
                                            <td>{{ $assignment['assignment']->title }}</td>
                                            <td>{{ ucfirst($assignment['assignment']->assignment_type) }}</td>
                                            <td>{{ $assignment['assignment']->due_date->format('M d, Y') }}</td>
                                            <td>{{ $assignment['grade']->points_earned }} / {{ $assignment['assignment']->max_points }}</td>
                                            <td>{{ $assignment['percentage'] }}%</td>
                                            <td>{{ number_format($assignment['weighted_score'], 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No graded assignments found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4">Final Weighted Score</th>
                                        <th colspan="2">{{ $gradeReport['final_score'] }}%</th>
                                    </tr>
                                </tfoot>
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
                        <h3 class="panel-title">Performance Chart</h3>
                    </div>
                    <div class="panel-body">
                        <canvas id="performanceChart" height="100"></canvas>
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
            const assignments = @json($gradeReport['assignments']);
            const labels = assignments.map(a => a.assignment.title);
            const scores = assignments.map(a => a.percentage);
            
            const ctx = document.getElementById('performanceChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Score (%)',
                        data: scores,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 2,
                        fill: false,
                        tension: 0.1
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
