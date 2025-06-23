@extends('voyager::master')

@section('page_title', 'My Courses')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-study"></i> My Courses
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
                                        <th>Course Name</th>
                                        <th>Teacher</th>
                                        <th>Final Grade</th>
                                        <th class="actions">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($courses as $course)
                                        <tr>
                                            <td>{{ $course->name }}</td>
                                            <td>{{ $course->teacher->name ?? 'Not Assigned' }}</td>
                                            <td>
                                                <span class="label label-primary">View Report to See Grade</span>
                                            </td>
                                            <td class="no-sort no-click bread-actions">
                                                <a href="{{ route('grades.student.course.report', ['course' => $course->id, 'student' => auth()->user()->student->id]) }}" 
                                                   title="View Report" class="btn btn-sm btn-info">
                                                    <i class="voyager-file-text"></i> <span class="hidden-xs hidden-sm">View Report</span>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">You are not enrolled in any courses.</td>
                                        </tr>
                                    @endforelse
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
                "order": [[0, "asc"]],
                "language": {
                    "sEmptyTable": "No courses found",
                    "sInfo": "Showing _START_ to _END_ of _TOTAL_ courses",
                    "sInfoEmpty": "Showing 0 to 0 of 0 courses",
                }
            });
        });
    </script>
@stop
