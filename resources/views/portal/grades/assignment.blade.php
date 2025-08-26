@extends('portal.layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Assignment Details: {{ $assignment->title }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Course:</th>
                                    <td>{{ $assignment->course->name }}</td>
                                </tr>
                                <tr>
                                    <th>Type:</th>
                                    <td>{{ ucfirst($assignment->assignment_type) }}</td>
                                </tr>
                                <tr>
                                    <th>Due Date:</th>
                                    <td>{{ $assignment->due_date->format('M d, Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Max Points:</th>
                                    <td>{{ $assignment->max_points }}</td>
                                </tr>
                                <tr>
                                    <th>Weight:</th>
                                    <td>{{ $assignment->weight }}x</td>
                                </tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <div class="p-3 bg-light rounded">
                                    {{ $assignment->description ?? 'No description provided.' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @php
                $user = Auth::guard('portal')->user();
                
                if ($user->user_type === 'parent') {
                    $studentIds = $user->parent->students->pluck('id');
                    $relevantGrades = $assignment->grades->whereIn('student_id', $studentIds);
                } else {
                    $relevantGrades = $assignment->grades->where('student_id', $user->student->id);
                }
            @endphp

            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Grade Details</h5>
                </div>
                <div class="card-body">
                    @if($user->user_type === 'parent' && count($relevantGrades) > 1)
                        <!-- For parents with multiple children -->
                        @foreach($relevantGrades as $grade)
                            <div class="mb-4">
                                <h5>{{ $grade->student->name }}</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card border-0 mb-3">
                                            <div class="card-body text-center">
                                                <h2 class="mb-0">{{ $grade->points_earned }} / {{ $assignment->max_points }}</h2>
                                                <p class="text-muted">Points Earned</p>
                                                
                                                <div class="my-3">
                                                    @php
                                                        $percentage = ($grade->points_earned / $assignment->max_points) * 100;
                                                        $gradeColor = $percentage >= 90 ? 'success' : 
                                                                    ($percentage >= 80 ? 'info' : 
                                                                    ($percentage >= 70 ? 'warning' : 'danger'));
                                                    @endphp
                                                    <div class="progress" style="height: 20px">
                                                        <div class="progress-bar bg-{{ $gradeColor }}" 
                                                            role="progressbar" 
                                                            style="width: {{ $percentage }}%" 
                                                            aria-valuenow="{{ $percentage }}" 
                                                            aria-valuemin="0" 
                                                            aria-valuemax="100">
                                                            {{ number_format($percentage, 1) }}%
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card border-0">
                                            <div class="card-body">
                                                <h6>Feedback from Teacher</h6>
                                                <div class="p-3 bg-light rounded">
                                                    {{ $grade->feedback ?? 'No feedback provided.' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if(!$loop->last)
                                <hr>
                            @endif
                        @endforeach
                    @elseif(count($relevantGrades) == 1)
                        <!-- For students or parents with one child -->
                        @php
                            $grade = $relevantGrades->first();
                        @endphp
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card border-0 mb-3">
                                    <div class="card-body text-center">
                                        <h1 class="display-4 mb-0">{{ $grade->points_earned }} / {{ $assignment->max_points }}</h1>
                                        <p class="text-muted">Points Earned</p>
                                        
                                        @php
                                            $percentage = ($grade->points_earned / $assignment->max_points) * 100;
                                            $gradeColor = $percentage >= 90 ? 'success' : 
                                                        ($percentage >= 80 ? 'info' : 
                                                        ($percentage >= 70 ? 'warning' : 'danger'));
                                            $letterGrade = $percentage >= 90 ? 'A' : 
                                                        ($percentage >= 80 ? 'B' : 
                                                        ($percentage >= 70 ? 'C' : 
                                                        ($percentage >= 60 ? 'D' : 'F')));
                                        @endphp
                                        
                                        <div class="my-3">
                                            <div class="progress" style="height: 25px">
                                                <div class="progress-bar bg-{{ $gradeColor }}" 
                                                    role="progressbar" 
                                                    style="width: {{ $percentage }}%" 
                                                    aria-valuenow="{{ $percentage }}" 
                                                    aria-valuemin="0" 
                                                    aria-valuemax="100">
                                                    {{ number_format($percentage, 1) }}%
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="badge bg-{{ $gradeColor }} p-2 mt-2 fs-5">
                                            Grade: {{ $letterGrade }}
                                        </div>
                                        
                                        <p class="mt-3">
                                            <small class="text-muted">
                                                Graded on {{ $grade->updated_at->format('M d, Y') }}
                                            </small>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-0">
                                    <div class="card-body">
                                        <h6>Feedback from Teacher</h6>
                                        <div class="p-3 bg-light rounded">
                                            {{ $grade->feedback ?? 'No feedback provided.' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <p class="mb-0">This assignment has not been graded yet.</p>
                        </div>
                    @endif
                </div>
                <div class="card-footer">
                    <a href="{{ route('portal.grades.report') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left"></i> Back to Grade Report
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
