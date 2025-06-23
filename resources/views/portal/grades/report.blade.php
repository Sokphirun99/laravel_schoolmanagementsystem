@extends('portal.layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Grade Report: {{ $student->name }}</h5>
                    @if(Auth::guard('portal')->user()->user_type === 'parent')
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light dropdown-toggle" type="button" id="studentDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            Select Student
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="studentDropdown">
                            @foreach(Auth::guard('portal')->user()->parent->students as $child)
                                <li>
                                    <a class="dropdown-item {{ $child->id === $student->id ? 'active' : '' }}" 
                                       href="{{ route('portal.grades.report', $child->id) }}">
                                        {{ $child->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
            </div>
            
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Course Grades</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @forelse($courses as $course)
                            @php
                                $gradeData = $reportData[$course->id] ?? null;
                            @endphp
                            
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card h-100">
                                    <div class="card-header">
                                        <h5 class="mb-0">{{ $course->name }}</h5>
                                        <small class="text-muted">Teacher: {{ $course->teacher->name ?? 'Not Assigned' }}</small>
                                    </div>
                                    <div class="card-body">
                                        @if($gradeData)
                                            <div class="text-center mb-3">
                                                <h1 class="display-4" style="color: 
                                                    {{ $gradeData['letter_grade'] === 'A' ? '#28a745' : 
                                                      ($gradeData['letter_grade'] === 'B' ? '#17a2b8' : 
                                                      ($gradeData['letter_grade'] === 'C' ? '#ffc107' : 
                                                      ($gradeData['letter_grade'] === 'D' ? '#fd7e14' : '#dc3545'))) }}">
                                                    {{ $gradeData['letter_grade'] }}
                                                </h1>
                                                <p class="lead">{{ $gradeData['final_score'] }}%</p>
                                            </div>
                                            
                                            <div class="progress mb-3" style="height: 10px;">
                                                <div class="progress-bar bg-{{ 
                                                    $gradeData['final_score'] >= 90 ? 'success' : 
                                                    ($gradeData['final_score'] >= 80 ? 'info' : 
                                                    ($gradeData['final_score'] >= 70 ? 'warning' : 'danger')) 
                                                }}" 
                                                     role="progressbar" 
                                                     style="width: {{ $gradeData['final_score'] }}%" 
                                                     aria-valuenow="{{ $gradeData['final_score'] }}" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100">
                                                </div>
                                            </div>
                                            
                                            <h6 class="mt-3">Assignment Summary</h6>
                                            @if(count($gradeData['assignments']) > 0)
                                                <ul class="list-group list-group-flush small">
                                                    @foreach(array_slice($gradeData['assignments'], 0, 3) as $assignment)
                                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                                        <span class="text-truncate" style="max-width: 70%;" title="{{ $assignment['assignment']->title }}">
                                                            {{ $assignment['assignment']->title }}
                                                        </span>
                                                        <span class="badge bg-{{ 
                                                            $assignment['percentage'] >= 90 ? 'success' : 
                                                            ($assignment['percentage'] >= 80 ? 'info' : 
                                                            ($assignment['percentage'] >= 70 ? 'warning' : 'danger')) 
                                                        }} rounded-pill">
                                                            {{ number_format($assignment['percentage'], 1) }}%
                                                        </span>
                                                    </li>
                                                    @endforeach
                                                </ul>
                                                
                                                @if(count($gradeData['assignments']) > 3)
                                                    <div class="text-center mt-2">
                                                        <p class="text-muted">
                                                            + {{ count($gradeData['assignments']) - 3 }} more assignments
                                                        </p>
                                                    </div>
                                                @endif
                                            @else
                                                <p class="text-muted text-center">No graded assignments yet</p>
                                            @endif
                                        @else
                                            <div class="text-center">
                                                <p class="text-muted">No grade data available</p>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="card-footer bg-white">
                                        <a href="{{ route('portal.grades.course.detail', ['student' => $student->id, 'course' => $course->id]) }}" 
                                           class="btn btn-sm btn-outline-primary w-100">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-info text-center">
                                    No courses found for this student
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // You can add JavaScript for interactive grade charts here
</script>
@endsection
