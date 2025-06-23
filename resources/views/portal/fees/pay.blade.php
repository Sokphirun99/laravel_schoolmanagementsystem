@extends('portal.layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Payment Details</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Fee Information</h5>
                            <p><strong>Student:</strong> {{ $fee->student->first_name ?? '' }} {{ $fee->student->last_name ?? '' }}</p>
                            <p><strong>Fee Type:</strong> {{ $fee->fee_type }}</p>
                            <p><strong>Amount:</strong> ${{ number_format($fee->amount, 2) }}</p>
                            <p><strong>Due Date:</strong> {{ $fee->due_date ? $fee->due_date->format('M d, Y') : 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5>Payment Details</h5>
                            <form action="{{ route('portal.fees.process', $fee->id) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="payment_method" class="form-label">Payment Method</label>
                                    <select name="payment_method" id="payment_method" class="form-select" required>
                                        <option value="">Select Method</option>
                                        <option value="Credit Card">Credit Card</option>
                                        <option value="Debit Card">Debit Card</option>
                                        <option value="Bank Transfer">Bank Transfer</option>
                                        <option value="Cash">Cash</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="transaction_id" class="form-label">Transaction ID (optional)</label>
                                    <input type="text" name="transaction_id" id="transaction_id" class="form-control">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="remarks" class="form-label">Remarks (optional)</label>
                                    <textarea name="remarks" id="remarks" class="form-control" rows="3"></textarea>
                                </div>
                                
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">Complete Payment</button>
                                    <a href="{{ route('portal.fees.index') }}" class="btn btn-secondary">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <h5>Payment Instructions:</h5>
                        <p>This is a simulated payment system for demonstration purposes. In a production environment, this would integrate with a payment gateway.</p>
                        <p>Complete the form to simulate payment processing.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
