@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Admin Dashboard') }}</div>

                <div class="card-body">
                    <div class="row">
                        <!-- Statistics Cards -->
                        <div class="col-md-3 mb-4">
                            <div class="card bg-primary text-white h-100">
                                <div class="card-body">
                                    <h5 class="card-title">Students</h5>
                                    <p class="card-text display-4">{{ $total_students }}</p>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-white stretched-link" href="#">View Details</a>
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 mb-4">
                            <div class="card bg-success text-white h-100">
                                <div class="card-body">
                                    <h5 class="card-title">Teachers</h5>
                                    <p class="card-text display-4">{{ $total_teachers }}</p>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-white stretched-link" href="#">View Details</a>
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 mb-4">
                            <div class="card bg-warning text-white h-100">
                                <div class="card-body">
                                    <h5 class="card-title">Parents</h5>
                                    <p class="card-text display-4">{{ $total_parents }}</p>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-white stretched-link" href="#">View Details</a>
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 mb-4">
                            <div class="card bg-danger text-white h-100">
                                <div class="card-body">
                                    <h5 class="card-title">Classes</h5>
                                    <p class="card-text display-4">{{ $total_classes }}</p>
                                </div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-white stretched-link" href="#">View Details</a>
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <!-- Recent Notices -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Recent Notices</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Title</th>
                                                    <th>Type</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($recent_notices as $notice)
                                                <tr>
                                                    <td>
                                                        <a href="{{ route('notices.show', $notice) }}">{{ $notice->title }}</a>
                                                    </td>
                                                    <td>{{ ucfirst($notice->notice_type) }}</td>
                                                    <td>{{ $notice->created_at->format('M d, Y') }}</td>
                                                </tr>
                                                @endforeach
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

                        <!-- Recent User Registrations -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Recent User Registrations</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Role</th>
                                                    <th>Registration Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($recent_users as $user)
                                                <tr>
                                                    <td>{{ $user->name }}</td>
                                                    <td>{{ $user->role_label }}</td>
                                                    <td>{{ $user->created_at->format('M d, Y') }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="text-center mt-3">
                                        <a href="#" class="btn btn-sm btn-primary">View All Users</a>
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
