@extends('portal.layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Student Fees</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif
                    
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Fee Type</th>
                                    <th>Amount</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($fees as $fee)
                                <tr>
                                    <td>{{ $fee->student->first_name ?? '' }} {{ $fee->student->last_name ?? '' }}</td>
                                    <td>{{ $fee->fee_type }}</td>
                                    <td>${{ number_format($fee->amount, 2) }}</td>
                                    <td>{{ $fee->due_date ? $fee->due_date->format('M d, Y') : 'N/A' }}</td>
                                    <td>
                                        @if($fee->status == 'paid')
                                            <span class="badge bg-success">Paid</span>
                                        @elseif($fee->status == 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($fee->status == 'overdue')
                                            <span class="badge bg-danger">Overdue</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($fee->status) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($fee->status != 'paid')
                                            <a href="{{ route('portal.fees.pay', $fee->id) }}" class="btn btn-sm btn-primary">
                                                Pay Now
                                            </a>
                                        @else
                                            <button class="btn btn-sm btn-success" disabled>Paid</button>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No fees found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $fees->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
