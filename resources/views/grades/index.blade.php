@extends('voyager::master')

@section('page_title', 'Courses for Grades')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-book"></i> Courses for Grade Management
    </h1>
@stop

@section('content')
    <div class="page-content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="dataTable" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Course Name</th>
                                        <th>Teacher</th>
                                        <th>Students Enrolled</th>
                                        <th class="actions">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($courses as $course)
                                        <tr>
                                            <td>{{ $course->id }}</td>
                                            <td>{{ $course->name }}</td>
                                            <td>{{ $course->teacher->name ?? 'Not Assigned' }}</td>
                                            <td>{{ $course->students->count() }}</td>
                                            <td class="no-sort no-click bread-actions">
                                                <a href="{{ route('grades.assignments', $course->id) }}" title="View Assignments" class="btn btn-sm btn-info">
                                                    <i class="voyager-list"></i> <span class="hidden-xs hidden-sm">Assignments</span>
                                                </a>
                                                <a href="{{ route('grades.course.report', $course->id) }}" title="View Course Report" class="btn btn-sm btn-success">
                                                    <i class="voyager-bar-chart"></i> <span class="hidden-xs hidden-sm">Course Report</span>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script>
        $(document).ready(function () {
            $('#dataTable').DataTable({
                "order": [[1, "asc"]],
                "language": {
                    "sEmptyTable": "No courses found",
                    "sInfo": "Showing _START_ to _END_ of _TOTAL_ courses",
                    "sInfoEmpty": "Showing 0 to 0 of 0 courses",
                },
                "autoWidth": true,
            });
        });
    </script>
@stop
