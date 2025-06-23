@extends('portal.layouts.app')

@section('content')
<div class="page-content">
    <div class="analytics-sparkle"></div>
    <div class="analytics-sparkle-2"></div>
    
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-bordered">
                    <div class="panel-heading" style="background: linear-gradient(60deg, #ff7043, #ff5722); color: white;">
                        <h3 class="panel-title">
                            <i class="voyager-lock"></i> Change Password
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

                        @if(session('error'))
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <strong><i class="voyager-x"></i> Error!</strong> {{ session('error') }}
                        </div>
                        @endif

                        <form method="POST" action="{{ route('portal.change-password') }}">
                            @csrf
                            
                            <div class="form-group">
                                <label for="current_password" style="color: #62a8ea; font-weight: bold;">
                                    <i class="voyager-lock"></i> Current Password
                                </label>
                                <input id="current_password" 
                                       type="password" 
                                       class="form-control @error('current_password') is-invalid @enderror" 
                                       name="current_password" 
                                       required 
                                       autocomplete="current-password"
                                       style="border-radius: 5px; border: 2px solid #e3f2fd;">
                                @error('current_password')
                                    <span class="help-block" style="color: #f44336;">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="password" style="color: #62a8ea; font-weight: bold;">
                                    <i class="voyager-key"></i> New Password
                                </label>
                                <input id="password" 
                                       type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       name="password" 
                                       required 
                                       autocomplete="new-password"
                                       style="border-radius: 5px; border: 2px solid #e3f2fd;">
                                @error('password')
                                    <span class="help-block" style="color: #f44336;">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation" style="color: #62a8ea; font-weight: bold;">
                                    <i class="voyager-key"></i> Confirm New Password
                                </label>
                                <input id="password_confirmation" 
                                       type="password" 
                                       class="form-control" 
                                       name="password_confirmation" 
                                       required 
                                       autocomplete="new-password"
                                       style="border-radius: 5px; border: 2px solid #e3f2fd;">
                            </div>

                            <hr style="margin: 30px 0;">

                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-primary" style="margin-right: 10px;">
                                    <i class="voyager-lock"></i> Update Password
                                </button>
                                <a href="{{ route('portal.profile') }}" class="btn btn-default">
                                    <i class="voyager-x"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
