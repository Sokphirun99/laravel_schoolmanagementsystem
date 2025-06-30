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
                            <i class="voyager-dashboard"></i> Parent Dashboard
                        </h4>
                        <div>
                            <span class="badge badge-primary">{{ now()->format('F d, Y') }}</span>
                        </div>
                    </div>
                    <div class="voyager-card-body">
                        <h2>Welcome to the School Portal!</h2>
                        <p class="text-muted">Here you can monitor your children's academic progress, attendance, and communicate with teachers.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mb-4">
            <div class="col-12">
                <div class="voyager-card">
                    <div class="voyager-card-header border-bottom">
                        <h4 class="voyager-card-title mb-0">
                            <i class="voyager-group"></i> Your Children
                        </h4>
                    </div>
                    <div class="voyager-card-body">
                        <div class="row">
                            @forelse($students as $student)
                            <div class="col-md-4 col-sm-6 mb-4">
                                <div class="voyager-card h-100 student-card">
                                    <div class="voyager-card-body text-center">
                                        <div class="mb-3">
                                            @if($student->photo)
                                                <img src="{{ asset('storage/' . $student->photo) }}" 
                                                    class="avatar rounded-circle" 
                                                    style="width: 80px; height: 80px;">
                                            @else
                                                <div class="avatar-circle bg-primary rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                                    <span class="avatar-text text-white" style="font-size: 32px;">{{ substr($student->first_name, 0, 1) }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        <h4>{{ $student->first_name }} {{ $student->last_name }}</h4>
                                        <p class="text-muted mb-4">{{ $student->schoolClass->name ?? 'No Class Assigned' }}</p>
                                        
                                        <div class="d-flex justify-content-center flex-wrap">
                                            <a href="{{ route('portal.grades.report', $student->id) }}" 
                                               class="btn btn-primary btn-sm me-2 mb-2">
                                                <i class="voyager-study"></i> Grades
                                            </a>
                                            <a href="{{ route('portal.attendance.history', $student->id) }}" 
                                               class="btn btn-warning btn-sm me-2 mb-2">
                                                <i class="voyager-calendar"></i> Attendance
                                            </a>
                                            <a href="{{ route('portal.communication.teachers', ['student' => $student->id]) }}" 
                                               class="btn btn-success btn-sm mb-2">
                                                <i class="voyager-chat"></i> Contact
                                            </a>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="alert alert-info">
                                <strong><i class="voyager-info-circled"></i> Information:</strong> No children associated with your account. Please contact the school administration.
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity and Information -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="voyager-card h-100">
                <div class="voyager-card-header border-bottom">
                    <h4 class="voyager-card-title mb-0">
                        <i class="voyager-calendar"></i> Upcoming Events
                    </h4>
                </div>
                <div class="voyager-card-body">
                    @php
                        $events = App\Models\Event::where('date', '>=', now()->format('Y-m-d'))
                            ->orderBy('date')
                            ->take(5)
                            ->get();
                    @endphp
                    
                    @forelse($events as $event)
                    <div class="d-flex mb-3 align-items-center event-item">
                        <div class="event-date me-3">
                            <span class="date-badge bg-primary text-white px-2 py-1 rounded">
                                {{ date('M d', strtotime($event->date)) }}
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-1">{{ $event->title }}</h5>
                            <div class="text-muted mb-1">{{ $event->location ?? 'School' }}</div>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($event->date)->diffForHumans() }}</small>
                        </div>
                    </div>
                    @empty
                    <div class="alert alert-light">
                        <i class="voyager-info-circled"></i> No upcoming events at this time.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="voyager-card h-100">
                <div class="voyager-card-header border-bottom">
                    <h4 class="voyager-card-title mb-0">
                        <i class="voyager-news"></i> Recent Announcements
                    </h4>
                </div>
                <div class="voyager-card-body">
                    @forelse($recentAnnouncements as $announcement)
                    <div class="d-flex mb-3 align-items-start announcement-item">
                        <div class="announcement-icon me-3">
                            <span class="icon-badge bg-success text-white p-2 rounded-circle">
                                <i class="voyager-megaphone"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="mb-1">{{ $announcement->title }}</h5>
                            <p class="mb-1">{{ Str::limit($announcement->content, 100) }}</p>
                            <small class="text-muted">{{ $announcement->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                    @empty
                    <div class="alert alert-light">
                        <i class="voyager-info-circled"></i> No recent announcements at this time.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    
    <!-- Fee Payments Panel -->
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="voyager-card">
                <div class="voyager-card-header border-bottom d-flex justify-content-between align-items-center">
                    <h4 class="voyager-card-title mb-0">
                        <i class="voyager-dollar"></i> Pending Fee Payments
                    </h4>
                </div>
                <div class="voyager-card-body">
                    @php
                        $studentIds = $students->pluck('id')->toArray();
                        
                        try {
                            $fees = App\Models\Fee::whereIn('student_id', $studentIds)
                                ->where('status', '=', 'pending')
                                ->orderBy('due_date')
                                ->get();
                        } catch (\Exception $e) {
                            $fees = collect();
                        }
                    @endphp
                    
                    <div class="table-responsive">
                        <table class="table voyager-table table-hover">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Fee Type</th>
                                    <th>Amount</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                    <th class="actions text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($fees as $fee)
                                <tr>
                                    <td>{{ $fee->student->first_name ?? 'Unknown' }} {{ $fee->student->last_name ?? 'Student' }}</td>
                                    <td>{{ $fee->fee_type }}</td>
                                    <td>${{ $fee->amount }}</td>
                                    <td>
                                        {{ date('M d, Y', strtotime($fee->due_date)) }}
                                        @if(strtotime($fee->due_date) < time())
                                            <span class="badge bg-danger ms-2">Overdue</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $fee->status === 'paid' ? 'success' : 'warning' }}">
                                            {{ ucfirst($fee->status) }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('portal.fees.pay', $fee->id) }}" class="btn btn-sm btn-primary">
                                            <i class="voyager-credit-cards"></i> Pay Now
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-3">
                                        <div class="alert alert-light mb-0">
                                            <i class="voyager-check"></i> No pending fee payments at this time.
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($fees->count() > 0)
                    <div class="mt-3">
                        <a href="{{ route('portal.fees.index') }}" class="btn btn-warning">
                            <i class="voyager-list"></i> View All Fee Records
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
