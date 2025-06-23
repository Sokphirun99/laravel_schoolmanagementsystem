@extends('portal.layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Student Profile</h5>
                </div>
                <div class="card-body text-center">
                    @if($student->photo)
                        <img src="{{ Voyager::image($student->photo) }}" 
                             class="rounded-circle mb-3" 
                             width="120" 
                             height="120">
                    @else
                        <div class="bg-secondary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 120px; height: 120px;">
                            <span class="text-white" style="font-size: 48px;">{{ substr($student->name, 0, 1) }}</span>
                        </div>
                    @endif
                    <h4>{{ $student->name }}</h4>
                    <p class="text-muted">{{ $student->schoolClass->name ?? 'No Class Assigned' }}</p>
                    <p>ID: {{ $student->admission_id ?? $student->id }}</p>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Upcoming Events</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @forelse($events as $event)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">{{ $event->title }}</h6>
                                <small class="text-muted">
                                    {{ $event->date->format('M d, Y') }} • {{ $event->location ?? 'School' }}
                                </small>
                            </div>
                            <span class="badge bg-primary rounded-pill">
                                {{ $event->date->diffForHumans() }}
                            </span>
                        </li>
                        @empty
                        <li class="list-group-item">No upcoming events</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Recent Grades</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Assignment</th>
                                    <th>Course</th>
                                    <th>Score</th>
                                    <th>Grade</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentGrades as $grade)
                                <tr>
                                    <td>
                                        <a href="{{ route('portal.grades.assignment', $grade->assignment_id) }}">
                                            {{ $grade->assignment->title }}
                                        </a>
                                    </td>
                                    <td>{{ $grade->assignment->course->name }}</td>
                                    <td>{{ $grade->points_earned }} / {{ $grade->assignment->max_points }}</td>
                                    <td>
                                        @php
                                            $percentage = ($grade->points_earned / $grade->assignment->max_points) * 100;
                                            $badgeClass = $percentage >= 90 ? 'success' : 
                                                        ($percentage >= 80 ? 'info' : 
                                                        ($percentage >= 70 ? 'warning' : 'danger'));
                                        @endphp
                                        <span class="badge bg-{{ $badgeClass }}">
                                            {{ number_format($percentage, 1) }}%
                                        </span>
                                    </td>
                                    <td>{{ $grade->created_at->format('M d') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No recent grades found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <a href="{{ route('portal.grades.report') }}" class="btn btn-sm btn-outline-success">
                        View Full Grade Report
                    </a>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">Upcoming Assignments</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @forelse($upcomingAssignments as $assignment)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">{{ $assignment->title }}</h6>
                                <small class="text-muted">
                                    {{ $assignment->course->name ?? 'Unknown Course' }} • Due: {{ $assignment->due_date->format('M d, Y') }}
                                </small>
                            </div>
                            @php
                                $daysLeft = $assignment->due_date->diffInDays(now());
                                $badgeClass = $daysLeft < 3 ? 'danger' : 'warning';
                            @endphp
                            <span class="badge bg-{{ $badgeClass }} rounded-pill">
                                {{ $assignment->due_date->diffForHumans() }}
                            </span>
                        </li>
                        @empty
                        <li class="list-group-item">No upcoming assignments</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Recent Announcements</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @forelse($recentAnnouncements as $announcement)
                        <li class="list-group-item">
                            <h6 class="mb-1">{{ $announcement->title }}</h6>
                            <p class="mb-1">{{ Str::limit($announcement->content, 100) }}</p>
                            <small class="text-muted">
                                Posted {{ $announcement->created_at->diffForHumans() }}
                            </small>
                        </li>
                        @empty
                        <li class="list-group-item">No recent announcements</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
