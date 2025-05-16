@extends('voyager::master')

@section('page_title', 'School Reports')

@section('page_header')
    <div class="container-fluid">
        <h1 class="page-title">
            <i class="voyager-bar-chart"></i> School Reports
        </h1>
    </div>
@stop

@section('content')
    <div class="page-content browse container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-heading">
                        <h3 class="panel-title">Available Reports</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            @if(auth()->user()->hasRoleName('admin'))
                            <div class="col-md-4">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Financial Reports</h3>
                                    </div>
                                    <div class="panel-body">
                                        <p>View financial summaries, income statements, and expense reports.</p>
                                        <a href="#" class="btn btn-info">View Reports</a>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if(auth()->user()->hasRoleName('teacher') || auth()->user()->hasRoleName('admin'))
                            <div class="col-md-4">
                                <div class="panel panel-success">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Academic Reports</h3>
                                    </div>
                                    <div class="panel-body">
                                        <p>View student performance, attendance, and class statistics.</p>
                                        <a href="#" class="btn btn-info">View Reports</a>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if(auth()->user()->hasRoleName('parent') || auth()->user()->hasRoleName('admin'))
                            <div class="col-md-4">
                                <div class="panel panel-warning">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Student Progress Reports</h3>
                                    </div>
                                    <div class="panel-body">
                                        <p>View detailed progress reports for students including grades and attendance.</p>
                                        <a href="#" class="btn btn-info">View Reports</a>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <p><strong>Note:</strong> The reports displayed above are based on your assigned roles.</p>
                                    <p>Current roles:
                                        @foreach(auth()->user()->getAllRoles() as $role)
                                            <span class="label label-info">{{ $role->display_name }}</span>
                                        @endforeach
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
