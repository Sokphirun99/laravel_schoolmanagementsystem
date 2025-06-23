@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Student Dashboard') }}</div>

                <div class="card-body">
                    <div class="row">
                        <!-- Student Information -->
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">Student Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="text-center mb-3">
                                        <img src="{{ asset('storage/'.$student->photo ?? 'img/default-avatar.png') }}" alt="Profile Picture" class="img-thumbnail rounded-circle" style="width: 150px; height: 150px;">
                                    </div>
                                    <h5 class="card-title">{{ $student->first_name }} {{ $student->last_name }}</h5>
                                    <p class="card-text">Student ID: {{ $student->student_id }}</p>
                                    <p class="card-text">Class: {{ $student->class_name }}</p>
                                    <p class="card-text">Section: {{ $student->section_name }}</p>
                                    <a href="{{ route('profile') }}" class="btn btn-sm btn-primary">View Profile</a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Class & Schedule Information -->
                        <div class="col-md-8 mb-4">
                            <div class="card">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0">My Subjects</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Subject Name</th>
                                                    <th>Teacher</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($subjects as $subject)
                                                <tr>
                                                    <td>{{ $subject->name }}</td>
                                                    <td>
                                                        @if($subject->teachers->isNotEmpty())
                                                            {{ $subject->teachers->first()->first_name }} {{ $subject->teachers->first()->last_name }}
                                                        @else
                                                            Not assigned
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="#" class="btn btn-sm btn-info">View</a>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="3" class="text-center">No subjects available</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Recent Notices -->
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-warning text-dark">
                                    <h5 class="mb-0">Recent Notices</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Title</th>
                                                    <th>Type</th>
                                                    <th>Date</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($recent_notices as $notice)
                                                <tr>
                                                    <td>{{ $notice->title }}</td>
                                                    <td>{{ ucfirst($notice->notice_type) }}</td>
                                                    <td>{{ $notice->created_at->format('M d, Y') }}</td>
                                                    <td>
                                                        <a href="{{ route('notices.show', $notice) }}" class="btn btn-sm btn-info">View</a>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="4" class="text-center">No notices available</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="text-center mt-3">
                                        <a href="{{ route('notices.index') }}" class="btn btn-sm btn-primary">View All Notices</a>
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
