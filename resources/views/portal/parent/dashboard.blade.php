@extends('portal.layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Your Children</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @forelse($students as $student)
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    @if($student->photo)
                                        <img src="{{ Voyager::image($student->photo) }}" 
                                             class="rounded-circle mb-3" 
                                             width="100" 
                                             height="100">
                                    @else
                                        <div class="bg-secondary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 100px; height: 100px;">
                                            <span class="text-white" style="font-size: 36px;">{{ substr($student->name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                    <h5>{{ $student->name }}</h5>
                                    <p class="text-muted">{{ $student->schoolClass->name ?? 'No Class Assigned' }}</p>
                                    
                                    <div class="d-flex flex-wrap justify-content-center">
                                        <a href="{{ route('portal.grades.report', $student->id) }}" 
                                           class="btn btn-sm btn-outline-primary m-1">
                                            <i class="fas fa-graduation-cap"></i> Grades
                                        </a>
                                        <a href="{{ route('portal.attendance.history', $student->id) }}" 
                                           class="btn btn-sm btn-outline-info m-1">
                                            <i class="fas fa-calendar-check"></i> Attendance
                                        </a>
                                        <a href="{{ route('portal.communication.teachers', ['student' => $student->id]) }}" 
                                           class="btn btn-sm btn-outline-success m-1">
                                            <i class="fas fa-envelope"></i> Contact Teacher
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="alert alert-info text-center">
                                <i class="fas fa-info-circle mr-2"></i> No children associated with your account. Please contact the school administration.
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">Upcoming School Events</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                @php
                                    $events = App\Models\Event::where('date', '>=', now())
                                        ->orderBy('date')
                                        ->take(5)
                                        ->get();
                                @endphp
                                
                                @forelse($events as $event)
                                <li class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">{{ $event->title }}</h6>
                                            <small class="text-muted">
                                                {{ $event->date->format('M d, Y') }} • {{ $event->location ?? 'School' }}
                                            </small>
                                        </div>
                                        <span class="badge bg-primary rounded-pill">
                                            {{ $event->date->diffForHumans() }}
                                        </span>
                                    </div>
                                </li>
                                @empty
                                <li class="list-group-item">No upcoming events</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card mb-4">
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
            
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">Fee Payments</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Fee Type</th>
                                    <th>Amount</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $studentIds = $students->pluck('id')->toArray();
                                    
                                    // Check if the fees table exists before querying
                                    try {
                                        $fees = App\Models\Fee::whereIn('student_id', $studentIds)
                                            ->where('status', '=', 'pending')
                                            ->orderBy('due_date')
                                            ->get();
                                    } catch (\Exception $e) {
                                        $fees = collect(); // Empty collection if table doesn't exist or any other error
                                    }
                                @endphp
                                
                                @forelse($fees as $fee)
                                <tr>
                                    <td>{{ $fee->student->name ?? 'Unknown Student' }}</td>
                                    <td>{{ $fee->fee_type }}</td>
                                    <td>{{ number_format($fee->amount, 2) }}</td>
                                    <td>
                                        {{ $fee->due_date->format('M d, Y') }}
                                        @if($fee->due_date->isPast())
                                            <span class="badge bg-danger">Overdue</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $fee->status === 'paid' ? 'success' : 'warning' }}">
                                            {{ ucfirst($fee->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('portal.fees.pay', $fee->id) }}" class="btn btn-sm btn-outline-primary">
                                            Pay Now
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No pending fees</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <a href="{{ route('portal.fees.index') }}" class="btn btn-sm btn-outline-warning">
                        View All Fee Records
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
