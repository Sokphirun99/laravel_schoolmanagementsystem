@extends('portal.layouts.app')

@section('head')
    <link href="{{ asset('css/voyager-ui/portal-updated.css') }}" rel="stylesheet">
@endsection

@section('javascript')
    <script src="{{ asset('js/portal-enhanced.js') }}"></script>
@endsection

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <!-- Welcome Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="voyager-card welcome-card">
                    <div class="voyager-card-header border-bottom d-flex justify-content-between align-items-center">
                        <h4 class="voyager-card-title mb-0">
                            <i class="voyager-dashboard"></i> Student Dashboard
                        </h4>
                        <div>
                            <span class="badge badge-primary">{{ now()->format('F d, Y') }}</span>
                        </div>
                    </div>
                    <div class="voyager-card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h2 class="mb-2 text-primary">Welcome back, {{ $student->first_name }}!</h2>
                                <p class="text-muted mb-0">Here's what's happening in your academic journey today.</p>
                            </div>
                            <div class="col-md-4 text-end">
                                <div class="d-flex align-items-center justify-content-end">
                                    @if($student->photo)
                                        <img src="{{ asset('storage/' . $student->photo) }}" 
                                             class="rounded-circle me-3 shadow-sm" 
                                             width="60" 
                                             height="60"
                                             alt="Student Photo">
                                    @else
                                        <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center me-3 shadow-sm" style="width: 60px; height: 60px;">
                                            <span class="text-white fs-4">{{ substr($student->first_name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-bold">{{ $student->first_name }} {{ $student->last_name }}</div>
                                        <small class="text-muted">{{ $student->schoolClass->name ?? 'No Class Assigned' }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="voyager-card voyager-stats-card bg-gradient-success h-100">
                    <div class="voyager-card-body p-3 text-center text-white">
                        <div class="voyager-stats-icon mb-2">
                            <i class="voyager-study"></i>
                        </div>
                        <div class="voyager-stats-number display-4 fw-bold">{{ $recentGrades->count() }}</div>
                        <div class="voyager-stats-label">Recent Grades</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="voyager-card voyager-stats-card bg-gradient-warning h-100">
                    <div class="voyager-card-body p-3 text-center text-white">
                        <div class="voyager-stats-icon mb-2">
                            <i class="voyager-book"></i>
                        </div>
                        <div class="voyager-stats-number display-4 fw-bold">{{ $upcomingAssignments->count() }}</div>
                        <div class="voyager-stats-label">Assignments Due</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="voyager-card voyager-stats-card bg-gradient-info h-100">
                    <div class="voyager-card-body p-3 text-center text-white">
                        <div class="voyager-stats-icon mb-2">
                            <i class="voyager-calendar"></i>
                        </div>
                        <div class="voyager-stats-number display-4 fw-bold">{{ $events->count() }}</div>
                        <div class="voyager-stats-label">Upcoming Events</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="voyager-card voyager-stats-card bg-gradient-secondary h-100">
                    <div class="voyager-card-body p-3 text-center text-white">
                        <div class="voyager-stats-icon mb-2">
                            <i class="voyager-megaphone"></i>
                        </div>
                        <div class="voyager-stats-number display-4 fw-bold">{{ $recentAnnouncements->count() }}</div>
                        <div class="voyager-stats-label">Announcements</div>
                    </div>
                </div>
            </div>
        </div>

<!-- Main Content Grid -->
<div class="row">
    <!-- Left Column -->
    <div class="col-lg-8">
        <!-- Recent Grades -->
        <div class="voyager-card mb-4">
            <div class="voyager-card-header border-bottom">
                <h4 class="voyager-card-title mb-0">
                    <i class="voyager-study"></i> Recent Grades
                </h4>
            </div>
            <div class="voyager-card-body">
                @if($recentGrades->count() > 0)
                    <div class="table-responsive">
                        <table class="table voyager-table table-hover">
                            <thead>
                                <tr>
                                    <th>Assignment</th>
                                    <th>Score</th>
                                    <th>Grade</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentGrades as $grade)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $grade->assignment->title ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $grade->assignment->course->name ?? 'N/A' }}</small>
                                    </td>
                                    <td>{{ $grade->points_earned ?? 0 }}/{{ $grade->assignment->max_points ?? 100 }}</td>
                                    <td>
                                        <span class="badge bg-{{ $grade->percentage >= 90 ? 'success' : ($grade->percentage >= 80 ? 'warning' : 'danger') }}">
                                            {{ number_format($grade->percentage, 1) }}%
                                        </span>
                                    </td>
                                    <td>{{ $grade->created_at->format('M d, Y') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('portal.grades.report') }}" class="btn btn-primary">
                            <i class="voyager-bar-chart"></i> View All Grades
                        </a>
                    </div>
                @else
                    <div class="alert alert-light text-center">
                        <i class="voyager-study text-muted mb-2 d-block" style="font-size: 2.5rem;"></i>
                        <p class="mb-0">No recent grades to display.</p>
                        <p class="text-muted">Your grades will appear here once assignments are graded.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Upcoming Assignments -->
        <div class="voyager-card mb-4">
            <div class="voyager-card-header border-bottom">
                <h4 class="voyager-card-title mb-0">
                    <i class="voyager-book"></i> Upcoming Assignments
                </h4>
            </div>
            <div class="voyager-card-body">
                @if($upcomingAssignments->count() > 0)
                    <div class="assignment-list">
                        @foreach($upcomingAssignments as $assignment)
                        <div class="assignment-item d-flex py-2 border-bottom">
                            <div class="assignment-icon me-3">
                                <span class="assignment-indicator bg-warning rounded-circle d-inline-block" style="width: 10px; height: 10px;"></span>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $assignment->title }}</h6>
                                <p class="mb-1 small text-muted">{{ Str::limit($assignment->description, 60) ?? 'No description' }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">{{ $assignment->course->name ?? 'N/A' }}</small>
                                    <span class="badge bg-warning text-dark">
                                        @if($assignment->due_date)
                                            Due {{ $assignment->due_date }}
                                        @else
                                            Due Soon
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="text-center mt-3">
                        <a href="#" class="btn btn-sm btn-warning">
                            <i class="voyager-list"></i> View All Assignments
                        </a>
                    </div>
                @else
                    <div class="alert alert-light text-center">
                        <i class="voyager-check text-success mb-2 d-block" style="font-size: 2.5rem;"></i>
                        <p class="mb-0">All caught up! No upcoming assignments at the moment.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Right Column -->
    <div class="col-lg-4">
        <!-- Student Information -->
        <div class="voyager-card mb-4">
            <div class="voyager-card-header border-bottom">
                <h4 class="voyager-card-title mb-0">
                    <i class="voyager-person"></i> Student Profile
                </h4>
            </div>
            <div class="voyager-card-body text-center">
                @if($student->photo)
                    <img src="{{ asset('storage/' . $student->photo) }}" 
                         class="rounded-circle mb-3 border border-3 border-light shadow-sm" 
                         width="100" 
                         height="100"
                         alt="Student Photo">
                @else
                    <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3 border border-3 border-light shadow-sm" style="width: 100px; height: 100px;">
                        <span class="text-white" style="font-size: 40px;">{{ substr($student->first_name, 0, 1) }}</span>
                    </div>
                @endif
                <h5 class="mb-2">{{ $student->first_name }} {{ $student->last_name }}</h5>
                <div class="mb-3">
                    <span class="badge bg-primary">{{ $student->schoolClass->name ?? 'No Class Assigned' }}</span>
                </div>
                <div class="student-details">
                    <div class="student-detail-item border-bottom py-2">
                        <div class="row">
                            <div class="col-5 text-muted text-start">Student ID:</div>
                            <div class="col-7 text-end fw-bold">{{ $student->admission_no ?? $student->id }}</div>
                        </div>
                    </div>
                    <div class="student-detail-item border-bottom py-2">
                        <div class="row">
                            <div class="col-5 text-muted text-start">Email:</div>
                            <div class="col-7 text-end">{{ $student->email ?? 'N/A' }}</div>
                        </div>
                    </div>
                    <div class="student-detail-item border-bottom py-2">
                        <div class="row">
                            <div class="col-5 text-muted text-start">Class:</div>
                            <div class="col-7 text-end">{{ $student->schoolClass->name ?? 'N/A' }}</div>
                        </div>
                    </div>
                    @if($student->section)
                    <div class="student-detail-item border-bottom py-2">
                        <div class="row">
                            <div class="col-5 text-muted text-start">Section:</div>
                            <div class="col-7 text-end">{{ $student->section->name }}</div>
                        </div>
                    </div>
                    @endif
                </div>
                <a href="{{ route('portal.profile') }}" class="btn btn-sm btn-primary mt-3">
                    <i class="voyager-edit"></i> Edit Profile
                </a>
            </div>
        </div>

        <!-- Events -->
        <div class="voyager-card mb-4">
            <div class="voyager-card-header border-bottom">
                <h4 class="voyager-card-title mb-0">
                    <i class="voyager-calendar"></i> Upcoming Events
                </h4>
            </div>
            <div class="voyager-card-body">
                @if($events->count() > 0)
                    <div class="event-list">
                        @foreach($events as $event)
                        <div class="event-item mb-3">
                            <div class="d-flex">
                                <div class="event-date me-3">
                                    <span class="date-badge bg-primary text-white px-2 py-1 rounded">
                                        {{ $event->date ?? 'TBD' }}
                                    </span>
                                </div>
                                <div class="event-details">
                                    <h6 class="mb-1">{{ $event->title }}</h6>
                                    <div class="text-muted small d-flex align-items-center mb-1">
                                        <i class="voyager-location me-1"></i>
                                        <span>{{ $event->location ?? 'School' }}</span>
                                    </div>
                                    <span class="badge bg-info">Coming up</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('portal.events') }}" class="btn btn-sm btn-info">
                            <i class="voyager-calendar"></i> View Calendar
                        </a>
                    </div>
                @else
                    <div class="alert alert-light text-center">
                        <i class="voyager-calendar text-muted mb-2 d-block" style="font-size: 2rem;"></i>
                        <p class="mb-0">No upcoming events scheduled.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Announcements -->
        <div class="voyager-card">
            <div class="voyager-card-header border-bottom">
                <h4 class="voyager-card-title mb-0">
                    <i class="voyager-megaphone"></i> Recent Announcements
                </h4>
            </div>
            <div class="voyager-card-body">
                @if($recentAnnouncements->count() > 0)
                    <div class="announcement-list">
                        @foreach($recentAnnouncements as $announcement)
                        <div class="d-flex mb-3 align-items-start announcement-item">
                            <div class="announcement-icon me-3">
                                <span class="icon-badge bg-success text-white p-2 rounded-circle">
                                    <i class="voyager-megaphone"></i>
                                </span>
                            </div>
                            <div>
                                <h6 class="mb-1">{{ $announcement->title }}</h6>
                                <p class="mb-1 text-muted">{{ Str::limit($announcement->content, 100) }}</p>
                                <small class="text-muted">
                                    <i class="voyager-alarm-clock me-1"></i>{{ $announcement->created_at->diffForHumans() }}
                                </small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('portal.announcements.index') }}" class="btn btn-sm btn-primary">
                            <i class="voyager-megaphone"></i> View All Announcements
                        </a>
                    </div>
                @else
                    <div class="alert alert-light text-center">
                        <i class="voyager-megaphone text-muted mb-2 d-block" style="font-size: 2rem;"></i>
                        <p class="mb-0">No recent announcements at this time.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
