@extends('portal.layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">My Children</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($students as $student)
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    @if($student->photo)
                                        <img src="{{ Voyager::image($student->photo) }}" 
                                             class="rounded-circle mb-3" 
                                             width="120" 
                                             height="120">
                                    @else
                                        <div class="bg-secondary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 120px; height: 120px;">
                                            <span class="text-white" style="font-size: 48px;">{{ substr($student->first_name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                    
                                    <h4>{{ $student->first_name }} {{ $student->last_name }}</h4>
                                    <p class="text-muted mb-3">{{ $student->schoolClass->name }} - {{ $student->section->name ?? 'No Section' }}</p>
                                    <p><strong>Student ID:</strong> {{ $student->student_id }}</p>
                                    <hr>
                                    
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="d-grid">
                                                <a href="{{ route('portal.grades.report', $student->id) }}" class="btn btn-outline-primary">
                                                    <i class="fas fa-chart-line me-2"></i> Grades
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="d-grid">
                                                <a href="{{ route('portal.attendance.history', $student->id) }}" class="btn btn-outline-info">
                                                    <i class="fas fa-calendar-check me-2"></i> Attendance
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-3">
                                        <div class="d-grid">
                                            @php
                                                // Get teacher ID - assuming each class has one main teacher
                                                $teacherId = $student->schoolClass->teacher_id ?? null;
                                            @endphp
                                            @if($teacherId)
                                                <a href="{{ route('portal.communication.teachers') }}" class="btn btn-outline-success">
                                                    <i class="fas fa-comment-alt me-2"></i> Contact Teacher
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
