@extends('voyager::master')

@section('page_title', 'Assignment Grades - ' . $assignment->title)

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-edit"></i> Grade Entry: {{ $assignment->title }}
    </h1>
    <a href="{{ route('grades.assignments', $course->id) }}" class="btn btn-warning">
        <i class="voyager-double-left"></i> Back to Assignments
    </a>
@stop

@section('content')
    <div class="page-content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-heading">
                        <h3 class="panel-title">Assignment Details</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-4">
                                <strong>Course:</strong> {{ $course->name }}
                            </div>
                            <div class="col-md-4">
                                <strong>Due Date:</strong> {{ $assignment->due_date->format('M d, Y') }}
                            </div>
                            <div class="col-md-4">
                                <strong>Max Points:</strong> {{ $assignment->max_points }}
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <strong>Description:</strong> {{ $assignment->description }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('grades.store', $assignment->id) }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-bordered">
                        <div class="panel-heading">
                            <h3 class="panel-title">Student Grades</h3>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Student ID</th>
                                            <th>Student Name</th>
                                            <th>Points Earned (Max: {{ $assignment->max_points }})</th>
                                            <th>Feedback (Optional)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($students as $student)
                                            @php
                                                $existingGrade = $existingGrades[$student->id] ?? null;
                                                $pointsEarned = $existingGrade ? $existingGrade->points_earned : '';
                                                $feedback = $existingGrade ? $existingGrade->feedback : '';
                                            @endphp
                                            <tr>
                                                <td>{{ $student->id }}</td>
                                                <td>{{ $student->user->name ?? $student->name }}</td>
                                                <td>
                                                    <input 
                                                        type="number" 
                                                        class="form-control" 
                                                        name="grades[{{ $student->id }}][points_earned]" 
                                                        value="{{ old('grades.' . $student->id . '.points_earned', $pointsEarned) }}" 
                                                        step="0.01" 
                                                        min="0" 
                                                        max="{{ $assignment->max_points }}"
                                                        required
                                                    >
                                                    <input type="hidden" name="grades[{{ $student->id }}][student_id]" value="{{ $student->id }}">
                                                </td>
                                                <td>
                                                    <textarea 
                                                        class="form-control" 
                                                        name="grades[{{ $student->id }}][feedback]" 
                                                        rows="2"
                                                    >{{ old('grades.' . $student->id . '.feedback', $feedback) }}</textarea>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <button type="submit" class="btn btn-primary">Save All Grades</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@stop
