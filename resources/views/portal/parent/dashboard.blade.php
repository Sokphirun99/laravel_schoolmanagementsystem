@extends('portal.layouts.app')

@section('content')
<div class="page-content">
    <div class="analytics-sparkle"></div>
    <div class="analytics-sparkle-2"></div>
    
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-heading" style="background: linear-gradient(60deg, #26c6da, #00acc1); color: white;">
                        <h3 class="panel-title">
                            <i class="voyager-group"></i> Your Children
                        </h3>
                    </div>
                <div class="panel-body">
                    <div class="row">
                        @forelse($students as $student)
                        <div class="col-md-4 col-sm-6 mb-4">
                            <div class="panel widget center bgimage" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); background-size: cover;">
                                <div class="dimmer"></div>
                                <div class="panel-content">
                                    @if($student->photo)
                                        <img src="{{ asset('storage/' . $student->photo) }}" 
                                             class="avatar border-grey" 
                                             style="width: 80px; height: 80px; border-radius: 50%; margin-bottom: 15px;">
                                    @else
                                        <div class="avatar border-grey" style="background: linear-gradient(60deg, #26c6da, #00acc1); width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; font-size: 32px; color: white;">
                                            {{ substr($student->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <h4 style="color: white; text-shadow: 1px 1px 2px rgba(0,0,0,0.7);">{{ $student->name }}</h4>
                                    <p style="color: #e0e0e0; margin-bottom: 20px;">{{ $student->schoolClass->name ?? 'No Class Assigned' }}</p>
                                    
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('portal.grades.report', $student->id) }}" 
                                           class="btn btn-primary btn-sm">
                                            <i class="voyager-study"></i> Grades
                                        </a>
                                        <a href="{{ route('portal.attendance.history', $student->id) }}" 
                                           class="btn btn-warning btn-sm">
                                            <i class="voyager-calendar"></i> Attendance
                                        </a>
                                        <a href="{{ route('portal.communication.teachers', ['student' => $student->id]) }}" 
                                           class="btn btn-success btn-sm">
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
        <div class="col-md-6">
            <div class="panel panel-bordered">
                <div class="panel-heading" style="background: linear-gradient(60deg, #42a5f5, #1e88e5); color: white;">
                    <h3 class="panel-title">
                        <i class="voyager-calendar"></i> Upcoming Events
                    </h3>
                </div>
                <div class="panel-body">
                    @php
                        $events = App\Models\Event::where('date', '>=', now())
                            ->orderBy('date')
                            ->take(5)
                            ->get();
                    @endphp
                    
                    @forelse($events as $event)
                    <div class="media" style="margin-bottom: 15px;">
                        <div class="media-left">
                            <div class="btn btn-primary btn-sm">
                                {{ $event->date->format('M d') }}
                            </div>
                        </div>
                        <div class="media-body" style="padding-left: 15px;">
                            <h5 class="media-heading" style="margin-bottom: 5px;">{{ $event->title }}</h5>
                            <p class="text-muted" style="margin-bottom: 0;">{{ $event->location ?? 'School' }}</p>
                            <small class="text-muted">{{ $event->date->diffForHumans() }}</small>
                        </div>
                    </div>
                    @empty
                    <p class="text-muted">No upcoming events</p>
                    @endforelse
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="panel panel-bordered">
                <div class="panel-heading" style="background: linear-gradient(60deg, #66bb6a, #4caf50); color: white;">
                    <h3 class="panel-title">
                        <i class="voyager-news"></i> Recent Announcements
                    </h3>
                </div>
                <div class="panel-body">
                    @forelse($recentAnnouncements as $announcement)
                    <div class="media" style="margin-bottom: 15px;">
                        <div class="media-left">
                            <div class="btn btn-success btn-sm">
                                <i class="voyager-megaphone"></i>
                            </div>
                        </div>
                        <div class="media-body" style="padding-left: 15px;">
                            <h5 class="media-heading" style="margin-bottom: 5px;">{{ $announcement->title }}</h5>
                            <p style="margin-bottom: 5px;">{{ Str::limit($announcement->content, 100) }}</p>
                            <small class="text-muted">{{ $announcement->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                    @empty
                    <p class="text-muted">No recent announcements</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    
    <!-- Fee Payments Panel -->
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-bordered">
                <div class="panel-heading" style="background: linear-gradient(60deg, #ffa726, #ff9800); color: white;">
                    <h3 class="panel-title">
                        <i class="voyager-dollar"></i> Pending Fee Payments
                    </h3>
                </div>
                <div class="panel-body">
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
                        <table id="dataTable" class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Fee Type</th>
                                    <th>Amount</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                    <th class="actions text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($fees as $fee)
                                <tr>
                                    <td>{{ $fee->student->name ?? 'Unknown Student' }}</td>
                                    <td>{{ $fee->fee_type }}</td>
                                    <td>${{ number_format($fee->amount, 2) }}</td>
                                    <td>
                                        {{ $fee->due_date->format('M d, Y') }}
                                        @if($fee->due_date->isPast())
                                            <span class="label label-danger">Overdue</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="label label-{{ $fee->status === 'paid' ? 'success' : 'warning' }}">
                                            {{ ucfirst($fee->status) }}
                                        </span>
                                    </td>
                                    <td class="text-right">
                                        <a href="{{ route('portal.fees.pay', $fee->id) }}" class="btn btn-sm btn-primary">
                                            <i class="voyager-credit-cards"></i> Pay Now
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No pending fees</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($fees->count() > 0)
                    <div style="margin-top: 15px;">
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
