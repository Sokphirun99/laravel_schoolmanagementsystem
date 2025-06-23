@extends('portal.layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Attendance History: {{ $student->name }}</h5>
                    @if(Auth::guard('portal')->user()->user_type === 'parent')
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light dropdown-toggle" type="button" id="studentDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            Select Student
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
                    @endif
                </div>
                <div class="card-body">
                    <div class="mb-4 text-end">
                        <a href="{{ route('portal.attendance.summary', $student->id) }}" class="btn btn-outline-primary">
                            <i class="fas fa-chart-pie me-2"></i> Monthly Summary
                        </a>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Day</th>
                                    <th>Status</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attendances as $attendance)
                                <tr>
                                    <td>{{ $attendance->date->format('M d, Y') }}</td>
                                    <td>{{ $attendance->date->format('l') }}</td>
                                    <td>
                                        @if($attendance->status == 'present')
                                            <span class="badge bg-success">Present</span>
                                        @elseif($attendance->status == 'absent')
                                            <span class="badge bg-danger">Absent</span>
                                        @elseif($attendance->status == 'late')
                                            <span class="badge bg-warning text-dark">Late</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($attendance->status) }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $attendance->remark ?? '-' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">No attendance records found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-center mt-4">
                        {{ $attendances->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
