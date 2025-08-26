@extends('portal.layouts.app')

@section('page_title', 'Parent Dashboard')
@section('page_header')
    <div class="container-fluid">
        <h1 class="page-title">
            <i class="voyager-dashboard"></i> Parent Dashboard
        </h1>
        <div class="page-actions">
            <span class="badge badge-primary">{{ now()->format('F d, Y') }}</span>
        </div>
    </div>
@endsection

@section('content')
<div class="page-content browse container-fluid">
    @include('voyager::alerts')
    
    <!-- Welcome Banner -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="panel panel-bordered">
                <div class="panel-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-2">Welcome to the School Portal!</h2>
                            <p class="text-muted mb-0">
                                Monitor your children's academic progress, attendance, and communicate with teachers.
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="welcome-stats">
                                <span class="badge badge-success">{{ $students->count() }} {{ Str::plural('Child', $students->count()) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <x-portal.stats-card 
                title="Children" 
                :value="$students->count()" 
                icon="voyager-group" 
                color="primary" 
                url="#children-section" />
        </div>
        <div class="col-md-3">
            @php
                $totalPendingFees = 0;
                try {
                    $studentIds = $students->pluck('id')->toArray();
                    $totalPendingFees = App\Models\Fee::whereIn('student_id', $studentIds)
                        ->where('status', '=', 'pending')
                        ->count();
                } catch (\Exception $e) {
                    $totalPendingFees = 0;
                }
            @endphp
            <x-portal.stats-card 
                title="Pending Fees" 
                :value="$totalPendingFees" 
                icon="voyager-dollar" 
                color="warning" 
                url="#fees-section" />
        </div>
        <div class="col-md-3">
            @php
                $upcomingEvents = 0;
                try {
                    $upcomingEvents = App\Models\Event::where('date', '>=', now()->format('Y-m-d'))
                        ->count();
                } catch (\Exception $e) {
                    $upcomingEvents = 0;
                }
            @endphp
            <x-portal.stats-card 
                title="Upcoming Events" 
                :value="$upcomingEvents" 
                icon="voyager-calendar" 
                color="info" 
                url="#events-section" />
        </div>
        <div class="col-md-3">
            <x-portal.stats-card 
                title="Announcements" 
                :value="$recentAnnouncements->count()" 
                icon="voyager-megaphone" 
                color="success" 
                url="#announcements-section" />
        </div>
    </div>
    <!-- Your Children Section -->
    <div class="row mb-4" id="children-section">
        <div class="col-12">
            <div class="panel panel-bordered">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <i class="voyager-group"></i> Your Children
                    </h3>
                </div>
                <div class="panel-body">
                    @forelse($students as $student)
                        <div class="row mb-4">
                            <div class="col-md-4 col-sm-6 mb-4">
                                <div class="panel panel-bordered student-card h-100">
                                    <div class="panel-body text-center">
                                        <div class="mb-3">
                                            @if($student->photo)
                                                <img src="{{ asset('storage/' . $student->photo) }}" 
                                                     class="rounded-circle mb-2" 
                                                     style="width: 80px; height: 80px; object-fit: cover;">
                                            @else
                                                <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-2" 
                                                     style="width: 80px; height: 80px;">
                                                    <span class="text-white" style="font-size: 32px;">{{ substr($student->first_name, 0, 1) }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        <h4 class="mb-2">{{ $student->first_name }} {{ $student->last_name }}</h4>
                                        <p class="text-muted mb-3">
                                            <i class="voyager-study"></i> {{ $student->schoolClass->name ?? 'No Class Assigned' }}
                                        </p>
                                        
                                        <div class="student-info mb-3">
                                            <small class="text-muted d-block">Student ID: {{ $student->admission_no ?? $student->id }}</small>
                                            @if($student->section)
                                                <small class="text-muted d-block">Section: {{ $student->section->name }}</small>
                                            @endif
                                        </div>
                                        
                                        <div class="d-flex justify-content-center flex-wrap gap-2">
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
                        </div>
                    @empty
                        <x-portal.alert 
                            type="info" 
                            title="No Children Found" 
                            icon="voyager-info-circled">
                            No children are associated with your account. Please contact the school administration to add your children to your account.
                        </x-portal.alert>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- Recent Activity and Information -->
    <div class="row" id="events-section">
        <div class="col-md-6 mb-4">
            @php
                $events = [];
                try {
                    $events = App\Models\Event::where('date', '>=', now()->format('Y-m-d'))
                        ->orderBy('date')
                        ->take(5)
                        ->get();
                } catch (\Exception $e) {
                    $events = collect();
                }
            @endphp
            
            <x-portal.list 
                title="Upcoming Events" 
                :items="$events" 
                :showViewAll="true" 
                viewAllUrl="{{ route('portal.events') }}" 
                viewAllText="View All Events" 
                emptyMessage="No upcoming events at this time.">
                @foreach($events as $event)
                    <div class="portal-list-item d-flex align-items-center">
                        <div class="event-date me-3">
                            <span class="badge badge-primary px-2 py-1">
                                {{ date('M d', strtotime($event->date)) }}
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ $event->title }}</h6>
                            <div class="text-muted mb-1">
                                <i class="voyager-location"></i> {{ $event->location ?? 'School' }}
                            </div>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($event->date)->diffForHumans() }}</small>
                        </div>
                    </div>
                @endforeach
            </x-portal.list>
        </div>
        
        <div class="col-md-6 mb-4" id="announcements-section">
            <x-portal.list 
                title="Recent Announcements" 
                :items="$recentAnnouncements" 
                :showViewAll="true" 
                viewAllUrl="{{ route('portal.announcements.index') }}" 
                viewAllText="View All Announcements" 
                emptyMessage="No recent announcements at this time.">
                @foreach($recentAnnouncements as $announcement)
                    <div class="portal-list-item d-flex align-items-start">
                        <div class="announcement-icon me-3">
                            <span class="badge badge-success p-2 rounded-circle">
                                <i class="voyager-megaphone"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ $announcement->title }}</h6>
                            <p class="mb-1 text-muted">{{ Str::limit($announcement->content, 80) }}</p>
                            <small class="text-muted">
                                <i class="voyager-clock"></i> {{ $announcement->created_at->diffForHumans() }}
                            </small>
                        </div>
                    </div>
                @endforeach
            </x-portal.list>
        </div>
    </div>
    
    <!-- Fee Payments Panel -->
    <div class="row" id="fees-section">
        <div class="col-md-12 mb-4">
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
            
            <x-portal.table 
                title="Pending Fee Payments" 
                :headers="['Student', 'Fee Type', 'Amount', 'Due Date', 'Status', 'Actions']" 
                :data="$fees" 
                :showViewAll="$fees->count() > 0" 
                viewAllUrl="{{ route('portal.fees.index') }}" 
                viewAllText="View All Fee Records" 
                emptyMessage="No pending fee payments at this time.">
                @foreach($fees as $fee)
                    <tr>
                        <td>
                            <div class="fw-bold">{{ $fee->student->first_name ?? 'Unknown' }} {{ $fee->student->last_name ?? 'Student' }}</div>
                            <small class="text-muted">ID: {{ $fee->student->admission_no ?? $fee->student->id ?? 'N/A' }}</small>
                        </td>
                        <td>{{ $fee->fee_type }}</td>
                        <td>
                            <span class="fw-bold">${{ number_format($fee->amount, 2) }}</span>
                        </td>
                        <td>
                            <div>{{ date('M d, Y', strtotime($fee->due_date)) }}</div>
                            @if(strtotime($fee->due_date) < time())
                                <span class="badge badge-danger">Overdue</span>
                            @elseif(strtotime($fee->due_date) < strtotime('+7 days'))
                                <span class="badge badge-warning">Due Soon</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-{{ $fee->status === 'paid' ? 'success' : 'warning' }}">
                                {{ ucfirst($fee->status) }}
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('portal.fees.pay', $fee->id) }}" class="btn btn-sm btn-primary">
                                <i class="voyager-credit-cards"></i> Pay Now
                            </a>
                        </td>
                    </tr>
                @endforeach
            </x-portal.table>
        </div>
    </div>
</div>
@endsection
