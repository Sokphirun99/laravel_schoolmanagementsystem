@extends('portal.layouts.app')

@section('content')
<div class="page-content">
    <div class="analytics-sparkle"></div>
    <div class="analytics-sparkle-2"></div>
    
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-heading" style="background: linear-gradient(60deg, #42a5f5, #1e88e5); color: white;">
                        <h3 class="panel-title">
                            <i class="voyager-calendar"></i> Attendance History: {{ $student->name }}
                        </h3>
                        @if(Auth::guard('portal')->user()->user_type === 'parent')
                        <div class="panel-actions" style="margin-top: -5px;">
                            <div class="dropdown">
                                <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="studentDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="voyager-group"></i> Select Student
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="studentDropdown">
                                    @foreach(Auth::guard('portal')->user()->parent->students as $child)
                                        <li>
                                            <a class="dropdown-item {{ $child->id === $student->id ? 'active' : '' }}" 
                                               href="{{ route('portal.attendance.history', $child->id) }}">
                                                {{ $child->name }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="panel-body">
                        <div class="text-right" style="margin-bottom: 20px;">
                            <a href="{{ route('portal.attendance.summary', $student->id) }}" class="btn btn-primary">
                                <i class="voyager-pie-chart"></i> View Summary
                            </a>
                        </div>
                        
                        <div class="table-responsive">
                            <table id="dataTable" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th><i class="voyager-calendar"></i> Date</th>
                                        <th><i class="voyager-clock"></i> Day</th>
                                        <th><i class="voyager-pulse"></i> Status</th>
                                        <th><i class="voyager-edit"></i> Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($attendances as $attendance)
                                    <tr>
                                        <td><strong>{{ $attendance->date->format('M d, Y') }}</strong></td>
                                        <td>{{ $attendance->date->format('l') }}</td>
                                        <td>
                                            @if($attendance->status == 'present')
                                                <span class="label label-success"><i class="voyager-check"></i> Present</span>
                                            @elseif($attendance->status == 'absent')
                                                <span class="label label-danger"><i class="voyager-x"></i> Absent</span>
                                            @elseif($attendance->status == 'late')
                                                <span class="label label-warning"><i class="voyager-clock"></i> Late</span>
                                            @else
                                                <span class="label label-default">{{ ucfirst($attendance->status) }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $attendance->remark ?? '-' }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">
                                            <i class="voyager-info-circled"></i> No attendance records found
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        @if(isset($attendances) && method_exists($attendances, 'hasPages') && $attendances->hasPages())
                        <div style="margin-top: 20px; text-center;">
                            {{ $attendances->links() }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
                        {{ $attendances->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
