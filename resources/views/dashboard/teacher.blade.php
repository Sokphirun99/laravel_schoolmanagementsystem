@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Teacher Dashboard') }}</div>

                <div class="card-body">
                    <div class="row">
                        <!-- Teacher Information -->
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">Teacher Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="text-center mb-3">
                                        <img src="{{ asset('storage/'.$teacher->photo ?? 'img/default-avatar.png') }}" alt="Profile Picture" class="img-thumbnail rounded-circle" style="width: 150px; height: 150px;">
                                    </div>
                                    <h5 class="card-title">{{ $teacher->first_name }} {{ $teacher->last_name }}</h5>
                                    <p class="card-text">Teacher ID: {{ $teacher->teacher_id }}</p>
                                    <p class="card-text">Email: {{ $teacher->email }}</p>
                                    <p class="card-text">Phone: {{ $teacher->phone }}</p>
                                    <a href="{{ route('profile') }}" class="btn btn-sm btn-primary">View Profile</a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Subjects Card -->
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
                                                    <th>Class</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($subjects as $subject)
                                                <tr>
                                                    <td>{{ $subject->name }}</td>
                                                    <td>{{ $subject->schoolClass->name ?? 'N/A' }}</td>
                                                    <td>
                                                        <a href="#" class="btn btn-sm btn-info">View</a>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="3" class="text-center">No subjects assigned</td>
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
                                        <a href="{{ route('notices.create') }}" class="btn btn-sm btn-success">Create Notice</a>
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
