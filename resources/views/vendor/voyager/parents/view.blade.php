@extends('voyager::master')

@section('page_title', 'View Parent')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-people"></i> Viewing Parent &nbsp;
        <a href="{{ route('voyager.parents.index') }}" class="btn btn-warning">
            <i class="glyphicon glyphicon-list"></i> <span class="hidden-xs hidden-sm">Return to List</span>
        </a>
        <a href="{{ route('voyager.parents.edit', $parent->id) }}" class="btn btn-info">
            <i class="glyphicon glyphicon-pencil"></i> <span class="hidden-xs hidden-sm">Edit</span>
        </a>
    </h1>
@stop

@section('content')
    <div class="page-content read container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered" style="padding-bottom:5px;">
                    <!-- Parent Profile Overview -->
                    <div class="row">
                        <div class="col-md-3">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Profile Photo</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <img class="img-responsive" src="{{ $parent->user && $parent->user->avatar ? Voyager::image($parent->user->avatar) : asset('images/default-parent.png') }}" alt="{{ $parent->father_name }}">
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Basic Information</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h4>Parent Information</h4>
                                        <p><strong>Father's Name:</strong> {{ $parent->father_name }}</p>
                                        <p><strong>Mother's Name:</strong> {{ $parent->mother_name }}</p>
                                        <p><strong>Address:</strong> {{ $parent->address }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <h4>Professional Information</h4>
                                        <p><strong>Father's Occupation:</strong> {{ $parent->father_occupation }}</p>
                                        <p><strong>Mother's Occupation:</strong> {{ $parent->mother_occupation }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Contact Information</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Father's Phone:</strong> {{ $parent->father_phone }}</p>
                                        <p><strong>Father's Email:</strong> {{ $parent->father_email }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Mother's Phone:</strong> {{ $parent->mother_phone }}</p>
                                        <p><strong>Mother's Email:</strong> {{ $parent->mother_email }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Children Information -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Children Information</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Admission Number</th>
                                            <th>Class</th>
                                            <th>Section</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($parent->students as $student)
                                            <tr>
                                                <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                                                <td>{{ $student->admission_number }}</td>
                                                <td>{{ $student->section ? $student->section->class->name : 'Not Assigned' }}</td>
                                                <td>{{ $student->section ? $student->section->name : 'Not Assigned' }}</td>
                                                <td>
                                                    <span class="label label-{{ $student->status == 'active' ? 'success' : 'warning' }}">
                                                        {{ ucfirst($student->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('voyager.students.show', $student->id) }}" class="btn btn-sm btn-info">
                                                        <i class="voyager-eye"></i> View
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">No children found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Add Child Button -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel-body text-center">
                                <a href="{{ route('voyager.students.create') }}?parent_id={{ $parent->id }}" class="btn btn-success">
                                    <i class="voyager-plus"></i> Add Child
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
