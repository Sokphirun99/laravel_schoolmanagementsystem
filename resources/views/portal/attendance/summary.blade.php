@extends('portal.layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Attendance Summary: {{ $student->name }}</h5>
                    @if(Auth::guard('portal')->user()->user_type === 'parent')
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light dropdown-toggle" type="button" id="studentDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            Select Student
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="studentDropdown">
                            @foreach(Auth::guard('portal')->user()->parent->students as $child)
                                <li>
                                    <a class="dropdown-item {{ $child->id === $student->id ? 'active' : '' }}" 
                                       href="{{ route('portal.attendance.summary', $child->id) }}">
                                        {{ $child->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
                <div class="card-body">
                    <div class="mb-4 text-end">
                        <a href="{{ route('portal.attendance.history', $student->id) }}" class="btn btn-outline-primary">
                            <i class="fas fa-history me-2"></i> View Full History
                        </a>
                    </div>
                    
                    <h5 class="text-center mb-4">Monthly Attendance Summary: {{ $attendanceStats['month'] }}</h5>
                    
                    <div class="row">
                        <div class="col-md-8 mx-auto">
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-body">
                                    <!-- Progress bar -->
                                    <div class="progress mb-3" style="height: 25px;">
                                        <div class="progress-bar bg-success" role="progressbar" 
                                             style="width: {{ $attendanceStats['present_percentage'] }}%;" 
                                             aria-valuenow="{{ $attendanceStats['present_percentage'] }}" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100">
                                            {{ $attendanceStats['present_percentage'] }}% Present
                                        </div>
                                    </div>
                                    
                                    <!-- Stats -->
                                    <div class="row text-center">
                                        <div class="col-md-3">
                                            <div class="card border-0 bg-light mb-3">
                                                <div class="card-body">
                                                    <h3>{{ $attendanceStats['total'] }}</h3>
                                                    <small class="text-muted">Total Days</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card border-0 bg-success text-white mb-3">
                                                <div class="card-body">
                                                    <h3>{{ $attendanceStats['present'] }}</h3>
                                                    <small>Present</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card border-0 bg-danger text-white mb-3">
                                                <div class="card-body">
                                                    <h3>{{ $attendanceStats['absent'] }}</h3>
                                                    <small>Absent</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="card border-0 bg-warning mb-3">
                                                <div class="card-body">
                                                    <h3>{{ $attendanceStats['late'] }}</h3>
                                                    <small>Late</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Calendar view -->
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title">Monthly Calendar</h5>
                                    <div class="table-responsive">
                                        <table class="table table-bordered text-center">
                                            <thead>
                                                <tr>
                                                    <th>Mon</th>
                                                    <th>Tue</th>
                                                    <th>Wed</th>
                                                    <th>Thu</th>
                                                    <th>Fri</th>
                                                    <th class="table-secondary">Sat</th>
                                                    <th class="table-secondary">Sun</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $date = \Carbon\Carbon::now()->startOfMonth();
                                                    $daysInMonth = $date->daysInMonth;
                                                    $dayOfWeek = $date->dayOfWeek;
                                                    $dayOfWeek = $dayOfWeek === 0 ? 7 : $dayOfWeek; // Adjust Sunday to be 7
                                                    $attendanceByDate = $attendances->groupBy(function($attendance) {
                                                        return $attendance->date->format('Y-m-d');
                                                    });
                                                @endphp
                                                <tr>
                                                    @for($i = 1; $i < $dayOfWeek; $i++)
                                                        <td></td>
                                                    @endfor
                                                    @for($day = 1; $day <= $daysInMonth; $day++)
                                                        @php
                                                            $currentDate = \Carbon\Carbon::now()->startOfMonth()->addDays($day - 1);
                                                            $attendance = isset($attendanceByDate[$currentDate->format('Y-m-d')]) ? 
                                                                         $attendanceByDate[$currentDate->format('Y-m-d')][0] : null;
                                                            $cellClass = '';
                                                            if ($attendance) {
                                                                if ($attendance->status == 'present') {
                                                                    $cellClass = 'table-success';
                                                                } elseif ($attendance->status == 'absent') {
                                                                    $cellClass = 'table-danger';
                                                                } elseif ($attendance->status == 'late') {
                                                                    $cellClass = 'table-warning';
                                                                }
                                                            }
                                                            $isWeekend = $currentDate->isWeekend();
                                                            if ($isWeekend) {
                                                                $cellClass = $cellClass ?: 'table-secondary';
                                                            }
                                                        @endphp
                                                        
                                                        <td class="{{ $cellClass }}">
                                                            <div class="day-number">{{ $day }}</div>
                                                            @if($attendance)
                                                                <div class="status-indicator">
                                                                    @if($attendance->status == 'present')
                                                                        <i class="fas fa-check-circle text-success" title="Present"></i>
                                                                    @elseif($attendance->status == 'absent')
                                                                        <i class="fas fa-times-circle text-danger" title="Absent"></i>
                                                                    @elseif($attendance->status == 'late')
                                                                        <i class="fas fa-clock text-warning" title="Late"></i>
                                                                    @endif
                                                                </div>
                                                            @endif
                                                        </td>
                                                        
                                                        @if(($day + $dayOfWeek - 1) % 7 === 0)
                                                            </tr><tr>
                                                        @endif
                                                    @endfor
                                                    
                                                    @php
                                                        $remainingDays = 7 - (($daysInMonth + $dayOfWeek - 1) % 7);
                                                        if ($remainingDays < 7) {
                                                            for ($i = 0; $i < $remainingDays; $i++) {
                                                                echo '<td></td>';
                                                            }
                                                        }
                                                    @endphp
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .day-number {
        font-weight: bold;
    }
    .status-indicator {
        margin-top: 5px;
    }
    .table td {
        height: 50px;
        vertical-align: middle;
    }
</style>
@endpush
