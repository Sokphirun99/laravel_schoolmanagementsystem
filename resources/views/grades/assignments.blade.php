@extends('voyager::master')

@section('page_title', 'Assignments - ' . $course->name)

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-receipt"></i> Assignments for {{ $course->name }}
    </h1>
    <a href="{{ route('grades.index') }}" class="btn btn-warning">
        <i class="voyager-double-left"></i> Back to Courses
    </a>
    <a href="{{ route('voyager.assignments.create') }}?course_id={{ $course->id }}" class="btn btn-success">
        <i class="voyager-plus"></i> Add New Assignment
    </a>
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
                                        <th>Title</th>
                                        <th>Type</th>
                                        <th>Due Date</th>
                                        <th>Max Points</th>
                                        <th>Weight</th>
                                        <th class="actions">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($assignments as $assignment)
                                        <tr>
                                            <td>{{ $assignment->title }}</td>
                                            <td>
                                                <span class="label label-{{ strtolower($assignment->assignment_type) === 'exam' ? 'danger' : 'primary' }}">
                                                    {{ ucfirst($assignment->assignment_type) }}
                                                </span>
                                            </td>
                                            <td>{{ $assignment->due_date->format('M d, Y') }}</td>
                                            <td>{{ $assignment->max_points }}</td>
                                            <td>{{ $assignment->weight }}</td>
                                            <td class="no-sort no-click bread-actions">
                                                <a href="{{ route('grades.assignment.form', $assignment->id) }}" title="Enter Grades" class="btn btn-sm btn-primary">
                                                    <i class="voyager-edit"></i> <span class="hidden-xs hidden-sm">Enter Grades</span>
                                                </a>
                                                <a href="{{ route('voyager.assignments.edit', $assignment->id) }}" title="Edit" class="btn btn-sm btn-info">
                                                    <i class="voyager-edit"></i> <span class="hidden-xs hidden-sm">Edit</span>
                                                </a>
                                                <a href="{{ route('voyager.assignments.show', $assignment->id) }}" title="View" class="btn btn-sm btn-warning">
                                                    <i class="voyager-eye"></i> <span class="hidden-xs hidden-sm">View</span>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No assignments found for this course.</td>
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
                "order": [[2, "asc"]],
                "language": {
                    "sEmptyTable": "No assignments found",
                    "sInfo": "Showing _START_ to _END_ of _TOTAL_ assignments",
                    "sInfoEmpty": "Showing 0 to 0 of 0 assignments",
                }
            });
        });
    </script>
@stop
