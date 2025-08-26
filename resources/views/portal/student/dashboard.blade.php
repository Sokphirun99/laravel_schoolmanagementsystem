@extends('portal.layouts.app')

@section('content')
<div class="page-content">
    <div class="analytics-sparkle"></div>
    <div class="analytics-sparkle-2"></div>
    
    <div class="container-fluid">
        <!-- Welcome Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="voyager-card">
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
                                <h2 class="mb-2">Welcome back, {{ $student->first_name }}!</h2>
                                <p class="text-muted mb-0">Here's what's happening in your academic journey today.</p>
                            </div>
                            <div class="col-md-4 text-end">
                                <div class="d-flex align-items-center justify-content-end">
                                    @if($student->photo)
                                        <img src="{{ asset('storage/' . $student->photo) }}" 
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
            <div class="col-md-3 col-sm-6 mb-4 mb-md-0">
                <div class="voyager-card voyager-stats-card bg-success-gradient h-100">
                    <div class="voyager-card-body p-3 text-center text-white">
                        <div class="voyager-stats-icon mb-2">
                            <i class="voyager-study"></i>
                        </div>
                        <div class="voyager-stats-number">{{ $recentGrades->count() }}</div>
                        <div class="voyager-stats-label">Recent Grades</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-4 mb-md-0">
                <div class="voyager-card voyager-stats-card bg-warning-gradient h-100">
                    <div class="voyager-card-body p-3 text-center text-white">
                        <div class="voyager-stats-icon mb-2">
                            <i class="voyager-book"></i>
                        </div>
                        <div class="voyager-stats-number">{{ $upcomingAssignments->count() }}</div>
                        <div class="voyager-stats-label">Assignments Due</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-4 mb-md-0">
                <div class="voyager-card voyager-stats-card bg-info-gradient h-100">
                    <div class="voyager-card-body p-3 text-center text-white">
                        <div class="voyager-stats-icon mb-2">
                            <i class="voyager-calendar"></i>
                        </div>
                        <div class="voyager-stats-number">{{ $events->count() }}</div>
                        <div class="voyager-stats-label">Upcoming Events</div>
                    </div>
                @endforeach
            </div>
            <div class="col-md-3 col-sm-6 mb-4 mb-md-0">
                <div class="voyager-card voyager-stats-card bg-purple-gradient h-100">
                    <div class="voyager-card-body p-3 text-center text-white">
                        <div class="voyager-stats-icon mb-2">
                            <i class="voyager-megaphone"></i>
                        </div>
                        <div class="voyager-stats-number">{{ $recentAnnouncements->count() }}</div>
                        <div class="voyager-stats-label">Announcements</div>
                    </div>
                </div>
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
