@extends('voyager::master')

@section('page_title', 'View Student')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-person"></i> Viewing Student &nbsp;
        <a href="{{ route('voyager.students.index') }}" class="btn btn-warning">
            <i class="glyphicon glyphicon-list"></i> <span class="hidden-xs hidden-sm">Return to List</span>
        </a>
        <a href="{{ route('voyager.students.edit', $student->id) }}" class="btn btn-info">
            <i class="glyphicon glyphicon-pencil"></i> <span class="hidden-xs hidden-sm">Edit</span>
        </a>
    </h1>
@stop

@section('content')
    <div class="page-content read container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered" style="padding-bottom:5px;">
                    <!-- Student Profile Overview -->
                    <div class="row">
                        <div class="col-md-3">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Profile Photo</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <img class="img-responsive" src="{{ $student->user && $student->user->avatar ? Voyager::image($student->user->avatar) : asset('images/default-student.png') }}" alt="{{ $student->first_name }}">
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Basic Information</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h4>{{ $student->first_name }} {{ $student->last_name }}</h4>
                                        <p><strong>Admission Number:</strong> {{ $student->admission_number }}</p>
                                        <p><strong>Roll Number:</strong> {{ $student->roll_number }}</p>
                                        <p><strong>Date of Birth:</strong> {{ $student->date_of_birth }}</p>
                                        <p><strong>Gender:</strong> {{ ucfirst($student->gender) }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Class:</strong> {{ $student->section ? $student->section->class->name : 'Not Assigned' }}</p>
                                        <p><strong>Section:</strong> {{ $student->section ? $student->section->name : 'Not Assigned' }}</p>
                                        <p><strong>Admission Date:</strong> {{ $student->admission_date }}</p>
                                        <p><strong>Status:</strong> <span class="label label-{{ $student->status == 'active' ? 'success' : 'warning' }}">{{ ucfirst($student->status) }}</span></p>
                                        <p><strong>Blood Group:</strong> {{ $student->blood_group }}</p>
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
                                        <p><strong>Address:</strong> {{ $student->address }}</p>
                                        <p><strong>Phone:</strong> {{ $student->phone }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Email:</strong> {{ $student->user ? $student->user->email : 'Not Available' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Parent Information -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Parent Information</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Father:</strong> {{ $student->parent ? $student->parent->father_name : 'Not Available' }}</p>
                                        <p><strong>Father's Phone:</strong> {{ $student->parent ? $student->parent->father_phone : 'Not Available' }}</p>
                                        <p><strong>Father's Email:</strong> {{ $student->parent ? $student->parent->father_email : 'Not Available' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Mother:</strong> {{ $student->parent ? $student->parent->mother_name : 'Not Available' }}</p>
                                        <p><strong>Mother's Phone:</strong> {{ $student->parent ? $student->parent->mother_phone : 'Not Available' }}</p>
                                        <p><strong>Mother's Email:</strong> {{ $student->parent ? $student->parent->mother_email : 'Not Available' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabs for Additional Information -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel-body" style="padding-top:0;">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a data-toggle="tab" href="#attendance">Attendance History</a></li>
                                    <li><a data-toggle="tab" href="#exams">Exam Results</a></li>
                                    <li><a data-toggle="tab" href="#fees">Fee History</a></li>
                                </ul>

                                <div class="tab-content">
                                    <div id="attendance" class="tab-pane fade in active">
                                        <h3>Attendance History</h3>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Status</th>
                                                    <th>Remarks</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($student->attendances as $attendance)
                                                    <tr>
                                                        <td>{{ $attendance->date }}</td>
                                                        <td>
                                                            <span class="label label-{{ $attendance->status == 'present' ? 'success' : ($attendance->status == 'absent' ? 'danger' : 'warning') }}">
                                                                {{ ucfirst($attendance->status) }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $attendance->remarks }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="3" class="text-center">No attendance records found</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>

                                    <div id="exams" class="tab-pane fade">
                                        <h3>Exam Results</h3>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Exam</th>
                                                    <th>Subject</th>
                                                    <th>Marks</th>
                                                    <th>Grade</th>
                                                    <th>Remarks</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($student->results as $result)
                                                    <tr>
                                                        <td>{{ $result->exam->name }}</td>
                                                        <td>{{ $result->subject->name }}</td>
                                                        <td>{{ $result->marks_obtained }}</td>
                                                        <td>{{ $result->grade }}</td>
                                                        <td>{{ $result->remarks }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center">No exam results found</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>

                                    <div id="fees" class="tab-pane fade">
                                        <h3>Fee History</h3>
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Fee Type</th>
                                                    <th>Amount</th>
                                                    <th>Due Date</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($student->fees as $fee)
                                                    <tr>
                                                        <td>{{ $fee->feeStructure->feeType->name }}</td>
                                                        <td>${{ number_format($fee->amount, 2) }}</td>
                                                        <td>{{ $fee->due_date }}</td>
                                                        <td>
                                                            <span class="label label-{{ $fee->status == 'paid' ? 'success' : ($fee->status == 'unpaid' ? 'danger' : 'warning') }}">
                                                                {{ ucfirst($fee->status) }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            @if($fee->status != 'paid')
                                                                <a href="{{ route('voyager.payments.create') }}?student_fee_id={{ $fee->id }}" class="btn btn-sm btn-success">
                                                                    <i class="voyager-dollar"></i> Record Payment
                                                                </a>
                                                            @else
                                                                <a href="{{ route('voyager.payments.show', $fee->payments->first()->id) }}" class="btn btn-sm btn-info">
                                                                    <i class="voyager-documentation"></i> View Receipt
                                                                </a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center">No fee records found</td>
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
            </div>
        </div>
    </div>
@stop
