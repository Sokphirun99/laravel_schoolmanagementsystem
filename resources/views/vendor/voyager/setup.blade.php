@extends('voyager::master')

@section('page_title', 'School Setup')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-ship"></i> School Management System Setup
    </h1>
@stop

@section('content')
    <div class="page-content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-heading">
                        <h3 class="panel-title">Initialize School Management System</h3>
                    </div>
                    <div class="panel-body">
                        <form class="form-horizontal" method="POST" action="{{ route('school.initialize') }}">
                            @csrf

                            <div class="form-group">
                                <h4>School Information</h4>
                                <hr>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">School Name</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="school_name" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">School Code</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="school_code" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Principal Name</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="principal_name" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <h4>Academic Year Information</h4>
                                <hr>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Academic Year Name</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="academic_year_name" value="{{ date('Y') }}-{{ date('Y')+1 }}" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Start Date</label>
                                <div class="col-sm-9">
                                    <input type="date" class="form-control" name="academic_year_start" value="{{ date('Y-m-d') }}" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">End Date</label>
                                <div class="col-sm-9">
                                    <input type="date" class="form-control" name="academic_year_end" value="{{ date('Y-m-d', strtotime('+1 year')) }}" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-9">
                                    <p class="text-muted">
                                        This will create your school, academic year, and default classes (Grades 1-12) with sections (A, B, C).
                                    </p>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-9">
                                    <button type="submit" class="btn btn-primary">Initialize System</button>
                                    <a href="{{ route('school.migrate') }}" class="btn btn-warning">Run Migrations First</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
