@extends('portal.layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">My Profile</h4>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="bg-secondary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 120px; height: 120px;">
                            <span class="text-white" style="font-size: 48px;">{{ substr(Auth::guard('portal')->user()->name, 0, 1) }}</span>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4 text-md-end fw-bold">Name:</div>
                        <div class="col-md-8">{{ Auth::guard('portal')->user()->name }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4 text-md-end fw-bold">Email:</div>
                        <div class="col-md-8">{{ Auth::guard('portal')->user()->email }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4 text-md-end fw-bold">User Type:</div>
                        <div class="col-md-8 text-capitalize">{{ Auth::guard('portal')->user()->user_type }}</div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-4 text-md-end fw-bold">Last Login:</div>
                        <div class="col-md-8">{{ Auth::guard('portal')->user()->last_login_at ? Auth::guard('portal')->user()->last_login_at->format('M d, Y g:i A') : 'N/A' }}</div>
                    </div>
                    
                    <div class="text-center mt-4">
                        <a href="{{ route('portal.change-password') }}" class="btn btn-primary">Change Password</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
