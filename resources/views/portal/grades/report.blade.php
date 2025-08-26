@extends('portal.layouts.app')

@php
    $title = 'Grade Report';
    $breadcrumbs = [
        ['title' => 'Dashboard', 'url' => route('portal.dashboard')],
        ['title' => 'Grades', 'url' => '#'],
        ['title' => 'Grade Report', 'url' => '#']
    ];
@endphp

@section('content')
<!-- Page Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="voyager-card">
            <div class="voyager-card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h2 class="mb-2">
                            <i class="fas fa-chart-line me-2 text-primary"></i>
                            Grade Report: {{ $student->full_name }}
                        </h2>
                        <p class="text-muted mb-0">
                            Academic performance overview for {{ $student->schoolClass->name ?? 'No Class Assigned' }}
                        </p>
                    </div>
                    <div class="col-md-4 text-end">
                        @if(Auth::guard('portal')->user()->user_type === 'parent')
                        <div class="dropdown">
                            <button class="btn btn-voyager dropdown-toggle" type="button" id="studentDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-graduate me-2"></i>Select Student
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="studentDropdown">
                                @foreach(Auth::guard('portal')->user()->parent->students as $child)
                                    <li>
                                        <a class="dropdown-item {{ $child->id === $student->id ? 'active' : '' }}" 
                                           href="{{ route('portal.grades.report', $child->id) }}">
                                            <i class="fas fa-user me-2"></i>{{ $child->full_name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Grade Overview Stats -->
<div class="row mb-4">
    @php
        $totalGrades = collect($reportData)->sum('total_assignments');
        $completedGrades = collect($reportData)->sum('completed_assignments');
        $averageGrade = collect($reportData)->avg('overall_percentage');
        $coursesCount = count($courses);
    @endphp
    
    <div class="col-md-3">
        <div class="voyager-stats-card" style="background: linear-gradient(135deg, var(--voyager-primary), var(--voyager-accent));">
            <div class="voyager-stats-number">{{ $coursesCount }}</div>
            <div class="voyager-stats-label">Total Courses</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="voyager-stats-card" style="background: linear-gradient(135deg, var(--voyager-success), #66BB6A);">
            <div class="voyager-stats-number">{{ number_format($averageGrade, 1) }}%</div>
            <div class="voyager-stats-label">Overall Average</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="voyager-stats-card" style="background: linear-gradient(135deg, var(--voyager-warning), #FFA726);">
            <div class="voyager-stats-number">{{ $completedGrades }}</div>
            <div class="voyager-stats-label">Completed</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="voyager-stats-card" style="background: linear-gradient(135deg, #9C27B0, #BA68C8);">
            <div class="voyager-stats-number">{{ $totalGrades }}</div>
            <div class="voyager-stats-label">Total Assignments</div>
        </div>
    </div>
</div>

<!-- Course Grades Grid -->
<div class="row">
    @forelse($courses as $course)
        @php
            $gradeData = $reportData[$course->id] ?? null;
        @endphp
        
        <div class="col-lg-6 col-xl-4 mb-4">
            <div class="voyager-card h-100">
                <div class="voyager-card-header">
                    <i class="fas fa-book me-2"></i>{{ $course->name }}
                </div>
                <div class="voyager-card-body">
                    @if($gradeData)
                        <!-- Overall Grade Display -->
                        <div class="text-center mb-4">
                            <div class="position-relative d-inline-block">
                                <svg width="120" height="120" class="grade-circle">
                                    <circle cx="60" cy="60" r="50" fill="none" stroke="#e9ecef" stroke-width="8"/>
                                    <circle cx="60" cy="60" r="50" fill="none" stroke="{{ $gradeData['overall_percentage'] >= 90 ? '#4CAF50' : ($gradeData['overall_percentage'] >= 80 ? '#FF9800' : ($gradeData['overall_percentage'] >= 70 ? '#2196F3' : '#F44336')) }}" 
                                            stroke-width="8" stroke-linecap="round"
                                            stroke-dasharray="314" 
                                            stroke-dashoffset="{{ 314 - (314 * $gradeData['overall_percentage'] / 100) }}"
                                            transform="rotate(-90 60 60)"/>
                                </svg>
                                <div class="position-absolute top-50 start-50 translate-middle text-center">
                                    <div class="h3 mb-0 fw-bold" style="color: {{ $gradeData['overall_percentage'] >= 90 ? '#4CAF50' : ($gradeData['overall_percentage'] >= 80 ? '#FF9800' : ($gradeData['overall_percentage'] >= 70 ? '#2196F3' : '#F44336')) }}">
                                        {{ number_format($gradeData['overall_percentage'], 1) }}%
                                    </div>
                                    <small class="text-muted">Overall</small>
                                </div>
                            </div>
                        </div>

                        <!-- Course Info -->
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-6">
                                    <div class="text-center">
                                        <div class="h5 mb-1 text-primary">{{ $gradeData['completed_assignments'] }}</div>
                                        <small class="text-muted">Completed</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center">
                                        <div class="h5 mb-1 text-info">{{ $gradeData['total_assignments'] }}</div>
                                        <small class="text-muted">Total</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Letter Grade -->
                        <div class="text-center mb-3">
                            @php
                                $letterGrade = $gradeData['overall_percentage'] >= 90 ? 'A' : 
                                              ($gradeData['overall_percentage'] >= 80 ? 'B' : 
                                              ($gradeData['overall_percentage'] >= 70 ? 'C' : 
                                              ($gradeData['overall_percentage'] >= 60 ? 'D' : 'F')));
                                $gradeColor = $gradeData['overall_percentage'] >= 90 ? 'success' : 
                                             ($gradeData['overall_percentage'] >= 80 ? 'warning' : 
                                             ($gradeData['overall_percentage'] >= 70 ? 'info' : 'danger'));
                            @endphp
                            <span class="badge bg-{{ $gradeColor }} fs-5 px-3 py-2">{{ $letterGrade }}</span>
                        </div>

                        <!-- Teacher Info -->
                        <div class="text-center text-muted">
                            <small>
                                <i class="fas fa-chalkboard-teacher me-1"></i>
                                {{ $course->teacher->name ?? 'Not Assigned' }}
                            </small>
                        </div>

                        <!-- View Details Button -->
                        <div class="text-center mt-3">
                            <a href="{{ route('portal.grades.assignment', $course->id) }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-eye me-1"></i>View Details
                            </a>
                        </div>
                    @else
                        <!-- No Grades Available -->
                        <div class="text-center py-4">
                            <i class="fas fa-chart-line text-muted" style="font-size: 3rem;"></i>
                            <h5 class="mt-3 text-muted">No Grades Yet</h5>
                            <p class="text-muted">Grades will appear here once assignments are completed and graded.</p>
                            <div class="text-center text-muted mt-3">
                                <small>
                                    <i class="fas fa-chalkboard-teacher me-1"></i>
                                    {{ $course->teacher->name ?? 'Not Assigned' }}
                                </small>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <!-- No Courses -->
        <div class="col-12">
            <div class="voyager-card">
                <div class="voyager-card-body text-center py-5">
                    <i class="fas fa-graduation-cap text-muted" style="font-size: 4rem;"></i>
                    <h3 class="mt-4 text-muted">No Courses Found</h3>
                    <p class="text-muted">The student is not enrolled in any courses yet.</p>
                </div>
            </div>
        </div>
    @endforelse
</div>

@if(!empty($courses))
<!-- Download Report Section -->
<div class="row mt-4">
    <div class="col-12">
        <div class="voyager-card">
            <div class="voyager-card-body text-center">
                <h5 class="mb-3">Generate Report</h5>
                <p class="text-muted mb-4">Download a detailed grade report for {{ $student->full_name }}</p>
                <div class="d-flex justify-content-center gap-3 flex-wrap">
                    <button class="btn btn-voyager" onclick="window.print()">
                        <i class="fas fa-print me-2"></i>Print Report
                    </button>
                    <a href="#" class="btn btn-outline-primary">
                        <i class="fas fa-download me-2"></i>Download PDF
                    </a>
                    <a href="#" class="btn btn-outline-success">
                        <i class="fas fa-file-excel me-2"></i>Export to Excel
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<style>
    .grade-circle {
        transform: rotate(-90deg);
    }
    
    @media print {
        .voyager-sidebar,
        .voyager-header,
        .btn,
        .dropdown {
            display: none !important;
        }
        
        .main-content {
            margin-left: 0 !important;
        }
        
        .voyager-card {
            box-shadow: none !important;
            border: 1px solid #ddd !important;
        }
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // You can add JavaScript for interactive grade charts here
</script>
@endpush
