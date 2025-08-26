@extends('portal.layouts.app')

@section('content')
<div class="page-content">
    <div class="analytics-sparkle"></div>
    <div class="analytics-sparkle-2"></div>
    
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-bordered">
                    <div class="panel-heading" style="background: linear-gradient(60deg, #ffa726, #ff9800); color: white;">
                        <h3 class="panel-title">
                            <i class="voyager-credit-cards"></i> Payment Details
                        </h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="panel widget center bgimage" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); background-size: cover;">
                                    <div class="dimmer"></div>
                                    <div class="panel-content">
                                        <h4 style="color: white; text-shadow: 1px 1px 2px rgba(0,0,0,0.7); margin-bottom: 20px;">
                                            <i class="voyager-info-circled"></i> Fee Information
                                        </h4>
                                        <div style="text-align: left; color: #e0e0e0;">
                                            <p style="margin-bottom: 10px;">
                                                <strong>Student:</strong> {{ $fee->student->first_name ?? '' }} {{ $fee->student->last_name ?? '' }}
                                            </p>
                                            <p style="margin-bottom: 10px;">
                                                <strong>Fee Type:</strong> {{ $fee->fee_type }}
                                            </p>
                                            <p style="margin-bottom: 10px;">
                                                <strong>Amount:</strong> <span style="font-size: 18px; color: #ffeb3b;">${{ number_format($fee->amount, 2) }}</span>
                                            </p>
                                            <p style="margin-bottom: 0;">
                                                <strong>Due Date:</strong> {{ $fee->due_date ? $fee->due_date->format('M d, Y') : 'N/A' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h4 style="color: #62a8ea; margin-bottom: 20px;">
                                    <i class="voyager-credit-cards"></i> Payment Details
                                </h4>
                                <form action="{{ route('portal.fees.process', $fee->id) }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="payment_method" style="color: #62a8ea; font-weight: bold;">
                                            <i class="voyager-wallet"></i> Payment Method
                                        </label>
                                        <select name="payment_method" id="payment_method" class="form-control" required style="border-radius: 5px; border: 2px solid #e3f2fd;">
                                            <option value="">Select Method</option>
                                            <option value="Credit Card">Credit Card</option>
                                            <option value="Debit Card">Debit Card</option>
                                            <option value="Bank Transfer">Bank Transfer</option>
                                            <option value="Cash">Cash</option>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="transaction_id" style="color: #62a8ea; font-weight: bold;">
                                            <i class="voyager-receipt"></i> Transaction ID (optional)
                                        </label>
                                        <input type="text" 
                                               name="transaction_id" 
                                               id="transaction_id" 
                                               class="form-control" 
                                               placeholder="Enter transaction reference"
                                               style="border-radius: 5px; border: 2px solid #e3f2fd;">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="remarks" style="color: #62a8ea; font-weight: bold;">
                                            <i class="voyager-edit"></i> Remarks (optional)
                                        </label>
                                        <textarea name="remarks" 
                                                id="remarks" 
                                                class="form-control" 
                                                rows="3" 
                                                placeholder="Additional payment notes"
                                                style="border-radius: 5px; border: 2px solid #e3f2fd;"></textarea>
                                    </div>
                                    
                                    <hr style="margin: 20px 0;">
                                    
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-success" style="margin-right: 10px;">
                                            <i class="voyager-credit-cards"></i> Complete Payment
                                        </button>
                                        <a href="{{ route('portal.fees.index') }}" class="btn btn-default">
                                            <i class="voyager-x"></i> Cancel
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                        <!-- Payment Instructions Panel -->
                        <div style="margin-top: 30px;">
                            <div class="alert alert-info">
                                <h4 style="color: #1976d2; margin-bottom: 15px;">
                                    <i class="voyager-info-circled"></i> Payment Instructions
                                </h4>
                                <p style="margin-bottom: 10px;">This is a simulated payment system for demonstration purposes. In a production environment, this would integrate with a payment gateway.</p>
                                <p style="margin-bottom: 0;">Complete the form to simulate payment processing.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
