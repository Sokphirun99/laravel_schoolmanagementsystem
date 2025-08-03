@extends('portal.layouts.modern')

@section('content')
<!-- Welcome Section -->
<div class="portal-card mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                Welcome back, {{ $student->first_name }}! 👋
            </h2>
            <p class="text-gray-600 dark:text-gray-300">
                Here's what's happening in your academic journey today.
            </p>
        </div>
        <div class="flex items-center space-x-4">
            @if($student->photo)
                <img src="{{ asset('storage/' . $student->photo) }}" 
                     class="w-16 h-16 rounded-full border-4 border-white shadow-lg" 
                     alt="Student Photo">
            @else
                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-content-center border-4 border-white shadow-lg">
                    <span class="text-white text-xl font-bold">{{ substr($student->first_name, 0, 1) }}</span>
                </div>
            @endif
            <div class="text-right">
                <div class="font-semibold text-gray-900 dark:text-white">{{ $student->first_name }} {{ $student->last_name }}</div>
                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $student->schoolClass->name ?? 'No Class Assigned' }}</div>
                <div class="text-xs text-gray-400 dark:text-gray-500">{{ now()->format('F d, Y') }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Grid -->
<div class="portal-stats-grid mb-8">
    <x-portal.stats-card 
        title="Total Assignments" 
        value="{{ $upcomingAssignments->count() }}" 
        icon="voyager-file-text" 
        color="blue"
        subtitle="Due this week"
    />
    
    <x-portal.stats-card 
        title="Recent Grades" 
        value="{{ $recentGrades->count() }}" 
        icon="voyager-certificate" 
        color="green"
        subtitle="Latest results"
    />
    
    <x-portal.stats-card 
        title="Upcoming Events" 
        value="{{ $events->count() }}" 
        icon="voyager-calendar" 
        color="purple"
        subtitle="This month"
        href="{{ route('portal.events') }}"
    />
    
    <x-portal.stats-card 
        title="Announcements" 
        value="{{ $recentAnnouncements->count() }}" 
        icon="voyager-sound" 
        color="indigo"
        subtitle="New updates"
        href="{{ route('portal.announcements.index') }}"
    />
</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Upcoming Assignments -->
    <div class="portal-card">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                <i class="voyager-file-text mr-3 text-blue-500"></i>
                Upcoming Assignments
            </h3>
            <a href="{{ route('portal.announcements.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                View All →
            </a>
        </div>
        
        @if($upcomingAssignments->count() > 0)
            <div class="space-y-4">
                @foreach($upcomingAssignments->take(5) as $assignment)
                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900 dark:text-white">{{ $assignment->title }}</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-300">{{ Str::limit($assignment->description, 60) }}</p>
                            <div class="flex items-center mt-2 text-xs text-gray-500 dark:text-gray-400">
                                <i class="voyager-calendar mr-1"></i>
                                Due: {{ $assignment->due_date ? $assignment->due_date->format('M d, Y') : 'No due date' }}
                            </div>
                        </div>
                        <div class="flex items-center">
                            @php
                                $daysLeft = $assignment->due_date ? now()->diffInDays($assignment->due_date, false) : 0;
                                $urgencyClass = $daysLeft <= 1 ? 'text-red-500' : ($daysLeft <= 3 ? 'text-yellow-500' : 'text-green-500');
                            @endphp
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $urgencyClass }} bg-current bg-opacity-10">
                                {{ $daysLeft <= 0 ? 'Overdue' : $daysLeft . ' days left' }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                <i class="voyager-file-text text-4xl mb-3 opacity-30"></i>
                <p>No upcoming assignments</p>
            </div>
        @endif
    </div>

    <!-- Recent Grades -->
    <div class="portal-card">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                <i class="voyager-certificate mr-3 text-green-500"></i>
                Recent Grades
            </h3>
            <a href="{{ route('portal.grades.report') }}" class="text-sm text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300">
                View All →
            </a>
        </div>
        
        @if($recentGrades->count() > 0)
            <div class="space-y-4">
                @foreach($recentGrades->take(5) as $grade)
                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900 dark:text-white">{{ $grade->assignment->title ?? 'Assignment' }}</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-300">{{ $grade->assignment->subject->name ?? 'Subject' }}</p>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                <i class="voyager-calendar mr-1"></i>
                                {{ $grade->created_at->format('M d, Y') }}
                            </div>
                        </div>
                        <div class="text-right">
                            @php
                                $percentage = ($grade->score / $grade->total_marks) * 100;
                                $gradeClass = $percentage >= 80 ? 'text-green-600' : ($percentage >= 60 ? 'text-yellow-600' : 'text-red-600');
                            @endphp
                            <div class="text-xl font-bold {{ $gradeClass }}">
                                {{ $grade->score }}/{{ $grade->total_marks }}
                            </div>
                            <div class="text-sm {{ $gradeClass }}">
                                {{ number_format($percentage, 1) }}%
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                <i class="voyager-certificate text-4xl mb-3 opacity-30"></i>
                <p>No grades available</p>
            </div>
        @endif
    </div>

    <!-- Upcoming Events -->
    <div class="portal-card">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                <i class="voyager-calendar mr-3 text-purple-500"></i>
                Upcoming Events
            </h3>
            <a href="{{ route('portal.events') }}" class="text-sm text-purple-600 dark:text-purple-400 hover:text-purple-800 dark:hover:text-purple-300">
                View All →
            </a>
        </div>
        
        @if($events->count() > 0)
            <div class="space-y-4">
                @foreach($events->take(5) as $event)
                    <div class="flex items-start space-x-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        <div class="flex-shrink-0 text-center">
                            <div class="bg-purple-100 dark:bg-purple-900/50 text-purple-600 dark:text-purple-400 rounded-lg p-2 min-w-[50px]">
                                <div class="text-xs font-medium">{{ \Carbon\Carbon::parse($event->date)->format('M') }}</div>
                                <div class="text-lg font-bold">{{ \Carbon\Carbon::parse($event->date)->format('d') }}</div>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-medium text-gray-900 dark:text-white truncate">{{ $event->title }}</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">{{ Str::limit($event->description, 60) }}</p>
                            @if($event->time)
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                    <i class="voyager-clock mr-1"></i>
                                    {{ $event->time }}
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                <i class="voyager-calendar text-4xl mb-3 opacity-30"></i>
                <p>No upcoming events</p>
            </div>
        @endif
    </div>

    <!-- Recent Announcements -->
    <div class="portal-card">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                <i class="voyager-sound mr-3 text-indigo-500"></i>
                Recent Announcements
            </h3>
            <a href="{{ route('portal.announcements.index') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">
                View All →
            </a>
        </div>
        
        @if($recentAnnouncements->count() > 0)
            <div class="space-y-4">
                @foreach($recentAnnouncements->take(3) as $announcement)
                    <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900 dark:text-white">{{ $announcement->title }}</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300 mt-2">{{ Str::limit($announcement->content, 100) }}</p>
                                <div class="flex items-center mt-3 text-xs text-gray-500 dark:text-gray-400">
                                    <i class="voyager-calendar mr-1"></i>
                                    {{ $announcement->created_at->format('M d, Y') }}
                                    <span class="mx-2">•</span>
                                    <i class="voyager-person mr-1"></i>
                                    {{ $announcement->author ?? 'Administration' }}
                                </div>
                            </div>
                            @if($announcement->priority === 'high')
                                <span class="flex-shrink-0 ml-3">
                                    <span class="px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">
                                        Priority
                                    </span>
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                <i class="voyager-sound text-4xl mb-3 opacity-30"></i>
                <p>No recent announcements</p>
            </div>
        @endif
    </div>
</div>

<!-- Quick Actions -->
<div class="mt-8">
    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h3>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="{{ route('portal.grades.report') }}" class="portal-card text-center hover:scale-105 transition-transform">
            <i class="voyager-file-text text-3xl text-blue-500 mb-3"></i>
            <div class="font-medium text-gray-900 dark:text-white">Assignments</div>
        </a>
        
        <a href="{{ route('portal.grades.report') }}" class="portal-card text-center hover:scale-105 transition-transform">
            <i class="voyager-certificate text-3xl text-green-500 mb-3"></i>
            <div class="font-medium text-gray-900 dark:text-white">Grades</div>
        </a>
        
        <a href="{{ route('portal.timetable') }}" class="portal-card text-center hover:scale-105 transition-transform">
            <i class="voyager-calendar text-3xl text-purple-500 mb-3"></i>
            <div class="font-medium text-gray-900 dark:text-white">Timetable</div>
        </a>
        
        <a href="{{ route('portal.fees.index') }}" class="portal-card text-center hover:scale-105 transition-transform">
            <i class="voyager-dollar text-3xl text-yellow-500 mb-3"></i>
            <div class="font-medium text-gray-900 dark:text-white">Fees</div>
        </a>
    </div>
</div>
@endsection

@section('css')
<style>
    .grid {
        display: grid;
    }
    
    .grid-cols-1 {
        grid-template-columns: repeat(1, minmax(0, 1fr));
    }
    
    .grid-cols-2 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
    
    .gap-4 {
        gap: 1rem;
    }
    
    .gap-6 {
        gap: 1.5rem;
    }
    
    .space-y-4 > * + * {
        margin-top: 1rem;
    }
    
    .space-x-4 > * + * {
        margin-left: 1rem;
    }
    
    .min-w-0 {
        min-width: 0;
    }
    
    .truncate {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    .flex-shrink-0 {
        flex-shrink: 0;
    }
    
    @media (min-width: 768px) {
        .md\:grid-cols-4 {
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }
    }
    
    @media (min-width: 1024px) {
        .lg\:grid-cols-2 {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }
</style>
@endsection
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row">
        <div class="col-md-3 col-sm-6 mb-4">
            <x-portal.stats-card 
                title="Recent Grades" 
                :value="$recentGrades->count()" 
                icon="voyager-study" 
                color="success" 
                url="{{ route('portal.grades.report') }}" />
        </div>
        <div class="col-md-3 col-sm-6 mb-4">
            <x-portal.stats-card 
                title="Assignments Due" 
                :value="$upcomingAssignments->count()" 
                icon="voyager-book" 
                color="warning" 
                url="#" />
        </div>
        <div class="col-md-3 col-sm-6 mb-4">
            <x-portal.stats-card 
                title="Upcoming Events" 
                :value="$events->count()" 
                icon="voyager-calendar" 
                color="info" 
                url="{{ route('portal.events') }}" />
        </div>
        <div class="col-md-3 col-sm-6 mb-4">
            <x-portal.stats-card 
                title="Announcements" 
                :value="$recentAnnouncements->count()" 
                icon="voyager-megaphone" 
                color="danger" 
                url="{{ route('portal.announcements.index') }}" />
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-8">
            <!-- Recent Grades -->
            <div class="panel panel-bordered">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <i class="voyager-study"></i> Recent Grades
                    </h3>
                </div>
                <div class="panel-body">
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
