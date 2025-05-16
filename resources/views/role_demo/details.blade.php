@extends('voyager::master')

@section('page_title', 'Role Management Details')

@section('page_header')
    <div class="container-fluid">
        <h1 class="page-title">
            <i class="voyager-key"></i> Role Management Details
        </h1>
        <a href="{{ route('admin.role-demo') }}" class="btn btn-success btn-add-new">
            <i class="voyager-angle-left"></i> <span>Back to Role Demo</span>
        </a>
    </div>
@stop

@section('content')
    <div class="page-content browse container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-heading">
                        <h3 class="panel-title">Current User Roles</h3>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Role ID</th>
                                        <th>Name</th>
                                        <th>Display Name</th>
                                        <th>Description</th>
                                        <th>Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($userRoles as $userRole)
                                        <tr>
                                            <td>{{ $userRole->role->id }}</td>
                                            <td>{{ $userRole->role->name }}</td>
                                            <td>{{ $userRole->role->display_name }}</td>
                                            <td>{{ $userRole->role->description }}</td>
                                            <td>{{ $userRole->created_at ? $userRole->created_at->format('Y-m-d H:i:s') : 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">Role Methods</h3>
                    </div>
                    <div class="panel-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>Method</th>
                                <th>Result</th>
                            </tr>
                            <tr>
                                <td><code>hasRoleName('admin')</code></td>
                                <td>
                                    @if(Auth::user()->hasRoleName('admin'))
                                        <span class="label label-success">True</span>
                                    @else
                                        <span class="label label-danger">False</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><code>hasRoleName('teacher')</code></td>
                                <td>
                                    @if(Auth::user()->hasRoleName('teacher'))
                                        <span class="label label-success">True</span>
                                    @else
                                        <span class="label label-danger">False</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><code>hasRoleName('student')</code></td>
                                <td>
                                    @if(Auth::user()->hasRoleName('student'))
                                        <span class="label label-success">True</span>
                                    @else
                                        <span class="label label-danger">False</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><code>hasRoleName('parent')</code></td>
                                <td>
                                    @if(Auth::user()->hasRoleName('parent'))
                                        <span class="label label-success">True</span>
                                    @else
                                        <span class="label label-danger">False</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">Implementation Details</h3>
                    </div>
                    <div class="panel-body">
                        <h4>System Structure:</h4>
                        <ul>
                            <li><strong>User Model:</strong> Uses <code>UserRolesTrait</code></li>
                            <li><strong>Relationships:</strong> Many-to-many between users and roles</li>
                            <li><strong>Trait Methods:</strong> <code>hasRoleName()</code>, <code>hasAnyRole()</code>, etc.</li>
                            <li><strong>Legacy Support:</strong> Maintains compatibility with <code>role_id</code></li>
                        </ul>

                        <h4>Key Classes:</h4>
                        <ul>
                            <li><code>User</code> - Core user model with trait inclusion</li>
                            <li><code>UserRole</code> - Pivot model for the many-to-many relationship</li>
                            <li><code>Role</code> - Role definition model</li>
                            <li><code>UserRolesTrait</code> - Contains all role-related functionality</li>
                        </ul>

                        <h4>Database Tables:</h4>
                        <ul>
                            <li><code>users</code> - Main users table (with legacy <code>role_id</code> field)</li>
                            <li><code>roles</code> - Defines available roles</li>
                            <li><code>user_roles</code> - Pivot table for many-to-many relationship</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
