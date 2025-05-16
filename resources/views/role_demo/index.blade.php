@extends('voyager::master')

@section('page_title', 'Role Management Demo')

@section('page_header')
    <div class="container-fluid">
        <h1 class="page-title">
            <i class="voyager-people"></i> Role Management Demo
        </h1>
    </div>
@stop

@section('content')
    <div class="page-content browse container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-heading">
                        <h3 class="panel-title">User Roles Overview</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">User Information</h3>
                                    </div>
                                    <div class="panel-body">
                                        <p><strong>Name:</strong> {{ Auth::user()->name }}</p>
                                        <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                                        <p><strong>Primary Role ID:</strong> {{ Auth::user()->role_id }}</p>
                                        <p>
                                            <strong>All Roles:</strong>
                                            @foreach(Auth::user()->getAllRoles() as $role)
                                                <span class="label label-info">{{ $role->display_name }}</span>
                                            @endforeach
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="panel panel-success">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Role-based Content Display</h3>
                                    </div>
                                    <div class="panel-body">
                                        <p>
                                            <a href="{{ route('admin.role-demo.details') }}" class="btn btn-info">
                                                <i class="voyager-list"></i> View Role Details
                                            </a>

                                            @if(Auth::user()->hasRoleName('admin'))
                                                <a href="{{ route('admin.role-demo.admin-only') }}" class="btn btn-warning">
                                                    <i class="voyager-lock"></i> Admin Only Area
                                                </a>
                                            @endif

                                            @if(Auth::user()->hasRoleName('teacher'))
                                                <a href="{{ route('admin.role-demo.teacher-only') }}" class="btn btn-success">
                                                    <i class="voyager-study"></i> Teacher Only Area
                                                </a>
                                            @endif
                                        </p>

                                        <h4>Features Available to You:</h4>
                                        <ul>
                                            @if(Auth::user()->hasRoleName('admin'))
                                                <li>
                                                    <i class="voyager-lock"></i>
                                                    <strong>System Settings</strong>: Configure system-wide settings
                                                </li>
                                                <li>
                                                    <i class="voyager-people"></i>
                                                    <strong>User Management</strong>: Add, edit, and delete users
                                                </li>
                                                <li>
                                                    <i class="voyager-data"></i>
                                                    <strong>Database Access</strong>: Perform database operations
                                                </li>
                                            @endif

                                            @if(Auth::user()->hasRoleName('teacher'))
                                                <li>
                                                    <i class="voyager-study"></i>
                                                    <strong>Classroom Management</strong>: Manage your classrooms
                                                </li>
                                                <li>
                                                    <i class="voyager-edit"></i>
                                                    <strong>Grades Management</strong>: Enter and edit grades
                                                </li>
                                                <li>
                                                    <i class="voyager-file-text"></i>
                                                    <strong>Attendance Records</strong>: Track student attendance
                                                </li>
                                            @endif

                                            @if(Auth::user()->hasRoleName('student'))
                                                <li>
                                                    <i class="voyager-book"></i>
                                                    <strong>Course Materials</strong>: Access your course materials
                                                </li>
                                                <li>
                                                    <i class="voyager-bar-chart"></i>
                                                    <strong>View Grades</strong>: Check your grades and progress
                                                </li>
                                                <li>
                                                    <i class="voyager-calendar"></i>
                                                    <strong>Class Schedule</strong>: View your class schedule
                                                </li>
                                            @endif

                                            @if(Auth::user()->hasRoleName('parent'))
                                                <li>
                                                    <i class="voyager-people"></i>
                                                    <strong>Child Records</strong>: View your child's academic records
                                                </li>
                                                <li>
                                                    <i class="voyager-bar-chart"></i>
                                                    <strong>Progress Reports</strong>: Monitor academic progress
                                                </li>
                                                <li>
                                                    <i class="voyager-chat"></i>
                                                    <strong>Teacher Communication</strong>: Message teachers
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-info">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Role Management System</h3>
                                    </div>
                                    <div class="panel-body">
                                        <h4>Technical Details:</h4>
                                        <p>This page demonstrates the enhanced role management system implemented in the Laravel School Management System.</p>
                                        <p><strong>Key Features:</strong></p>
                                        <ul>
                                            <li><strong>Many-to-many Relationship:</strong> Users can have multiple roles</li>
                                            <li><strong>Backward Compatibility:</strong> Legacy <code>role_id</code> is maintained</li>
                                            <li><strong>Role Checking Methods:</strong> <code>hasRole()</code>, <code>hasRoleName()</code>, <code>hasAnyRole()</code></li>
                                            <li><strong>Role-based Middleware:</strong> <code>check.role</code> for route protection</li>
                                        </ul>

                                        <h4>Code Example:</h4>
<pre>
// Check if a user has a specific role
@if(Auth::user()->hasRoleName('admin'))
    // Show admin-only content
@endif

// Check if a user has any of multiple roles
@if(Auth::user()->hasAnyRole([1, 3]))
    // Show content for roles with IDs 1 or 3
@endif

// Get all user roles
$roles = Auth::user()->getAllRoles();
</pre>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
