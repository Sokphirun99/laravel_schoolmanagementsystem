@extends('portal.layouts.app')

@section('content')
<div class="page-content">
    <div class="analytics-sparkle"></div>
    <div class="analytics-sparkle-2"></div>
    
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-heading" style="background: linear-gradient(60deg, #ffa726, #ff9800); color: white;">
                        <h3 class="panel-title">
                            <i class="voyager-dollar"></i> Student Fees
                        </h3>
                    </div>
                    <div class="panel-body">
                        @if(session('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <strong><i class="voyager-check"></i> Success!</strong> {{ session('success') }}
                        </div>
                        @endif
                        
                        <div class="table-responsive">
                            <table id="dataTable" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th><i class="voyager-person"></i> Student</th>
                                        <th><i class="voyager-tag"></i> Fee Type</th>
                                        <th><i class="voyager-dollar"></i> Amount</th>
                                        <th><i class="voyager-calendar"></i> Due Date</th>
                                        <th><i class="voyager-pulse"></i> Status</th>
                                        <th class="actions text-right"><i class="voyager-activity"></i> Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @forelse($fees as $fee)
                                <tr>
                                    <td>{{ $fee->student->first_name ?? '' }} {{ $fee->student->last_name ?? '' }}</td>
                                    <td><span class="label label-info">{{ $fee->fee_type }}</span></td>
                                    <td><strong>${{ number_format($fee->amount, 2) }}</strong></td>
                                    <td>{{ $fee->due_date ? $fee->due_date->format('M d, Y') : 'N/A' }}</td>
                                    <td>
                                        @if($fee->status == 'paid')
                                            <span class="label label-success"><i class="voyager-check"></i> Paid</span>
                                        @elseif($fee->status == 'pending')
                                            <span class="label label-warning"><i class="voyager-clock"></i> Pending</span>
                                        @elseif($fee->status == 'overdue')
                                            <span class="label label-danger"><i class="voyager-warning"></i> Overdue</span>
                                        @else
                                            <span class="label label-default">{{ ucfirst($fee->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        @if($fee->status != 'paid')
                                            <a href="{{ route('portal.fees.pay', $fee->id) }}" class="btn btn-sm btn-primary">
                                                <i class="voyager-credit-cards"></i> Pay Now
                                            </a>
                                        @else
                                            <button class="btn btn-sm btn-success" disabled>
                                                <i class="voyager-check"></i> Paid
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        <i class="voyager-info-circled"></i> No fees found
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if($fees->hasPages())
                    <div style="margin-top: 30px;">
                        {{ $fees->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
