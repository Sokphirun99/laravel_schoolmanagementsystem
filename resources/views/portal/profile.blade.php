@extends('portal.layouts.app')

@section('content')
<div class="page-content">
    <div class="analytics-sparkle"></div>
    <div class="analytics-sparkle-2"></div>
    
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-bordered">
                    <div class="panel-heading" style="background: linear-gradient(60deg, #26c6da, #00acc1); color: white;">
                        <h3 class="panel-title">
                            <i class="voyager-person"></i> My Profile
                        </h3>
                    </div>
                    <div class="panel-body">
                        <div class="text-center" style="margin-bottom: 30px;">
                            <div class="avatar" style="background: linear-gradient(60deg, #26c6da, #00acc1); width: 120px; height: 120px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 20px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                                <span style="color: white; font-size: 48px; font-weight: bold;">{{ substr(Auth::guard('portal')->user()->name, 0, 1) }}</span>
                            </div>
                            <h2 style="color: #62a8ea; margin-bottom: 10px;">{{ Auth::guard('portal')->user()->name }}</h2>
                            <p style="color: #78909c; font-size: 16px;">{{ ucfirst(Auth::guard('portal')->user()->user_type) }}</p>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="color: #62a8ea; font-weight: bold;">
                                        <i class="voyager-person"></i> Full Name:
                                    </label>
                                    <p style="padding: 10px; background: #f8f9fa; border-radius: 5px; margin-bottom: 15px;">
                                        {{ Auth::guard('portal')->user()->name }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="color: #62a8ea; font-weight: bold;">
                                        <i class="voyager-mail"></i> Email Address:
                                    </label>
                                    <p style="padding: 10px; background: #f8f9fa; border-radius: 5px; margin-bottom: 15px;">
                                        {{ Auth::guard('portal')->user()->email }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="color: #62a8ea; font-weight: bold;">
                                        <i class="voyager-group"></i> User Type:
                                    </label>
                                    <p style="padding: 10px; background: #f8f9fa; border-radius: 5px; margin-bottom: 15px;">
                                        <span class="label {{ Auth::guard('portal')->user()->user_type === 'student' ? 'label-primary' : 'label-success' }}">
                                            {{ ucfirst(Auth::guard('portal')->user()->user_type) }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label style="color: #62a8ea; font-weight: bold;">
                                        <i class="voyager-calendar"></i> Last Login:
                                    </label>
                                    <p style="padding: 10px; background: #f8f9fa; border-radius: 5px; margin-bottom: 15px;">
                                        {{ Auth::guard('portal')->user()->last_login_at ? Auth::guard('portal')->user()->last_login_at->format('M d, Y g:i A') : 'N/A' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <hr style="margin: 30px 0;">
                        
                        <div class="text-center">
                            <a href="{{ route('portal.change-password') }}" class="btn btn-primary" style="margin-right: 10px;">
                                <i class="voyager-lock"></i> Change Password
                            </a>
                            <a href="{{ Auth::guard('portal')->user()->user_type === 'student' ? route('portal.student.dashboard') : route('portal.parent.dashboard') }}" class="btn btn-default">
                                <i class="voyager-home"></i> Back to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
