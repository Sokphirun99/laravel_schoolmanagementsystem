@extends('portal.layouts.app')

@section('content')
<!-- Welcome Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="voyager-card">
            <div class="voyager-card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h2 class="mb-2">Welcome back, {{ $student->first_name }}!</h2>
                        <p class="text-muted mb-0">Here's what's happening in your academic journey today.</p>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="d-flex align-items-center justify-content-end">
                            @if($student->photo)
                                <img src="{{ Voyager::image($student->photo) }}" 
                                     class="rounded-circle me-3" 
                                     width="60" 
                                     height="60"
                                     alt="Student Photo">
                            @else
                                <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                                    <span class="text-white fs-4">{{ substr($student->first_name, 0, 1) }}</span>
                                </div>
                            @endif
                            <div>
                                <div class="fw-bold">{{ $student->full_name }}</div>
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
    <div class="col-md-3">
        <div class="voyager-stats-card" style="background: linear-gradient(135deg, var(--voyager-success), #66BB6A);">
            <div class="voyager-stats-number">{{ $recentGrades->count() }}</div>
            <div class="voyager-stats-label">Recent Grades</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="voyager-stats-card" style="background: linear-gradient(135deg, var(--voyager-warning), #FFA726);">
            <div class="voyager-stats-number">{{ $upcomingAssignments->count() }}</div>
            <div class="voyager-stats-label">Assignments Due</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="voyager-stats-card" style="background: linear-gradient(135deg, var(--voyager-accent), #42A5F5);">
            <div class="voyager-stats-number">{{ $events->count() }}</div>
            <div class="voyager-stats-label">Upcoming Events</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="voyager-stats-card" style="background: linear-gradient(135deg, #9C27B0, #BA68C8);">
            <div class="voyager-stats-number">{{ $recentAnnouncements->count() }}</div>
            <div class="voyager-stats-label">Announcements</div>
        </div>
    </div>
</div>

<!-- Main Content Grid -->
<div class="row">
    <!-- Left Column -->
    <div class="col-lg-8">
        <!-- Recent Grades -->
        <div class="voyager-card mb-4">
            <div class="voyager-card-header">
                <i class="fas fa-star me-2"></i>Recent Grades
            </div>
            <div class="voyager-card-body">
                @if($recentGrades->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-voyager">
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
                        <a href="{{ route('portal.grades.report') }}" class="btn btn-voyager">
                            <i class="fas fa-chart-line me-2"></i>View All Grades
                        </a>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-graduation-cap text-muted" style="font-size: 3rem;"></i>
                        <h5 class="mt-3 text-muted">No Recent Grades</h5>
                        <p class="text-muted">Your grades will appear here once assignments are graded.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Upcoming Assignments -->
        <div class="voyager-card mb-4">
            <div class="voyager-card-header">
                <i class="fas fa-tasks me-2"></i>Upcoming Assignments
            </div>
            <div class="voyager-card-body">
                @if($upcomingAssignments->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($upcomingAssignments as $assignment)
                        <div class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $assignment->title }}</h6>
                                <p class="mb-1 text-muted">{{ $assignment->description ?? 'No description' }}</p>
                                <small class="text-muted">{{ $assignment->course->name ?? 'N/A' }}</small>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-warning text-dark">
                                    Due {{ $assignment->due_date->format('M d') }}
                                </span>
                                <div class="mt-1">
                                    <small class="text-muted">{{ $assignment->due_date->diffForHumans() }}</small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 3rem;"></i>
                        <h5 class="mt-3 text-muted">All Caught Up!</h5>
                        <p class="text-muted">No upcoming assignments at the moment.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Right Column -->
    <div class="col-lg-4">
        <!-- Student Information -->
        <div class="voyager-card mb-4">
            <div class="voyager-card-header">
                <i class="fas fa-user me-2"></i>Student Information
            </div>
            <div class="voyager-card-body text-center">
                @if($student->photo)
                    <img src="{{ Voyager::image($student->photo) }}" 
                         class="rounded-circle mb-3 border border-3 border-primary" 
                         width="120" 
                         height="120"
                         alt="Student Photo">
                @else
                    <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3 border border-3 border-white" style="width: 120px; height: 120px;">
                        <span class="text-white" style="font-size: 48px;">{{ substr($student->first_name, 0, 1) }}</span>
                    </div>
                @endif
                <h4 class="mb-2">{{ $student->full_name }}</h4>
                <div class="mb-3">
                    <span class="badge bg-primary fs-6">{{ $student->schoolClass->name ?? 'No Class Assigned' }}</span>
                </div>
                <div class="text-start">
                    <div class="row mb-2">
                        <div class="col-5"><strong>Student ID:</strong></div>
                        <div class="col-7">{{ $student->student_id ?? $student->id }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5"><strong>Email:</strong></div>
                        <div class="col-7">{{ $student->email ?? 'N/A' }}</div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5"><strong>Class:</strong></div>
                        <div class="col-7">{{ $student->schoolClass->name ?? 'N/A' }}</div>
                    </div>
                    @if($student->section)
                    <div class="row mb-2">
                        <div class="col-5"><strong>Section:</strong></div>
                        <div class="col-7">{{ $student->section->name }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Events -->
        <div class="voyager-card mb-4">
            <div class="voyager-card-header">
                <i class="fas fa-calendar-alt me-2"></i>Upcoming Events
            </div>
            <div class="voyager-card-body">
                @if($events->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($events as $event)
                        <div class="list-group-item px-0">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">{{ $event->title }}</h6>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>{{ $event->date->format('M d, Y') }}
                                        @if($event->location)
                                            <br><i class="fas fa-map-marker-alt me-1"></i>{{ $event->location }}
                                        @endif
                                    </small>
                                </div>
                                <span class="badge bg-info">
                                    {{ $event->date->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('portal.events') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-calendar me-1"></i>View All Events
                        </a>
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="fas fa-calendar-times text-muted" style="font-size: 2rem;"></i>
                        <p class="mt-2 text-muted mb-0">No upcoming events</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Announcements -->
        <div class="voyager-card">
            <div class="voyager-card-header">
                <i class="fas fa-bullhorn me-2"></i>Recent Announcements
            </div>
            <div class="voyager-card-body">
                @if($recentAnnouncements->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recentAnnouncements as $announcement)
                        <div class="list-group-item px-0">
                            <h6 class="mb-1">{{ $announcement->title }}</h6>
                            <p class="mb-1 text-muted">{{ Str::limit($announcement->content, 100) }}</p>
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>{{ $announcement->created_at->diffForHumans() }}
                            </small>
                        </div>
                        @endforeach
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('portal.announcements.index') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-bullhorn me-1"></i>View All Announcements
                        </a>
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="fas fa-bullhorn text-muted" style="font-size: 2rem;"></i>
                        <p class="mt-2 text-muted mb-0">No recent announcements</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
