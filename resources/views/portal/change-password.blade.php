@extends('portal.layouts.app')

@section('content')
<div class="page-content">
    <div class="analytics-sparkle"></div>
    <div class="analytics-sparkle-2"></div>
    
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="panel panel-bordered" style="box-shadow: 0 10px 25px rgba(0,0,0,0.1); border-radius: 10px; overflow: hidden; border: none;">
                    <div class="panel-heading" style="background: linear-gradient(135deg, var(--voyager-primary), #1989d8); color: white; padding: 15px 20px; position: relative;">
                        <h3 class="panel-title" style="font-weight: 600; display: flex; align-items: center;">
                            <i class="voyager-lock" style="margin-right: 10px; font-size: 1.2em;"></i> Change Password
                        </h3>
                    </div>
                    <div class="panel-body" style="padding: 25px;">
                        @if(session('success'))
                        <div class="alert alert-success alert-dismissible" style="background-color: #d4edda; color: #155724; border-left: 5px solid #28a745; border-radius: 5px; display: flex; align-items: center;">
                            <i class="voyager-check" style="font-size: 1.5em; margin-right: 10px;"></i>
                            <div style="flex-grow: 1;">
                                <strong>Success!</strong> {{ session('success') }}
                            </div>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="padding: 1.2rem; opacity: 0.8;">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @endif

                        @if(session('error'))
                        <div class="alert alert-danger alert-dismissible" style="background-color: #f8d7da; color: #721c24; border-left: 5px solid #dc3545; border-radius: 5px; display: flex; align-items: center;">
                            <i class="voyager-x" style="font-size: 1.5em; margin-right: 10px;"></i>
                            <div style="flex-grow: 1;">
                                <strong>Error!</strong> {{ session('error') }}
                            </div>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="padding: 1.2rem; opacity: 0.8;">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        @endif

                        <form method="POST" action="{{ route('portal.change-password') }}">
                            @csrf
                            
                            <div class="form-group">
                                <label for="current_password" style="color: var(--voyager-primary); font-weight: 600; margin-bottom: 10px; display: block;">
                                    <i class="voyager-lock" style="background: rgba(34, 167, 240, 0.1); width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; margin-right: 8px;"></i> Current Password
                                </label>
                                <div class="input-group">
                                    <span class="input-group-addon" style="background: #f8f9fa; border: 2px solid #e9ecef; border-right: none; color: var(--voyager-primary);">
                                        <i class="voyager-key"></i>
                                    </span>
                                    <input id="current_password" 
                                           type="password" 
                                           class="form-control @error('current_password') is-invalid @enderror" 
                                           name="current_password" 
                                           required 
                                           autocomplete="current-password"
                                           style="border: 2px solid #e9ecef; border-left: none; border-radius: 0 8px 8px 0; padding: 10px 15px; transition: all 0.3s ease; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                                </div>
                                @error('current_password')
                                    <span class="help-block" style="color: #f44336; margin-top: 5px; display: block; font-size: 0.9em;">
                                        <i class="voyager-x-circle" style="margin-right: 5px;"></i>
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="password" style="color: var(--voyager-primary); font-weight: 600; margin-bottom: 10px; display: block;">
                                    <i class="voyager-key" style="background: rgba(34, 167, 240, 0.1); width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; margin-right: 8px;"></i> New Password
                                </label>
                                <div class="input-group">
                                    <span class="input-group-addon" style="background: #f8f9fa; border: 2px solid #e9ecef; border-right: none; color: var(--voyager-primary);">
                                        <i class="voyager-lock"></i>
                                    </span>
                                    <input id="password" 
                                           type="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           name="password" 
                                           required 
                                           autocomplete="new-password"
                                           style="border: 2px solid #e9ecef; border-left: none; border-radius: 0 8px 8px 0; padding: 10px 15px; transition: all 0.3s ease; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                                </div>
                                @error('password')
                                    <span class="help-block" style="color: #f44336; margin-top: 5px; display: block; font-size: 0.9em;">
                                        <i class="voyager-x-circle" style="margin-right: 5px;"></i>
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation" style="color: var(--voyager-primary); font-weight: 600; margin-bottom: 10px; display: block;">
                                    <i class="voyager-check" style="background: rgba(34, 167, 240, 0.1); width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; margin-right: 8px;"></i> Confirm New Password
                                </label>
                                <div class="input-group">
                                    <span class="input-group-addon" style="background: #f8f9fa; border: 2px solid #e9ecef; border-right: none; color: var(--voyager-primary);">
                                        <i class="voyager-check-circle"></i>
                                    </span>
                                    <input id="password_confirmation" 
                                           type="password" 
                                           class="form-control" 
                                           name="password_confirmation" 
                                           required 
                                           autocomplete="new-password"
                                           style="border: 2px solid #e9ecef; border-left: none; border-radius: 0 8px 8px 0; padding: 10px 15px; transition: all 0.3s ease; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                                </div>
                            </div>

                            <hr style="margin: 30px 0; border-top: 1px solid #f5f5f5;">

                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-primary" style="margin-right: 10px; background: var(--voyager-primary); border: none; border-radius: 8px; padding: 10px 20px; font-weight: 600; box-shadow: 0 4px 10px rgba(34, 167, 240, 0.3); transition: all 0.3s ease; position: relative; overflow: hidden;">
                                    <i class="voyager-lock" style="margin-right: 5px;"></i> Update Password
                                </button>
                                <a href="{{ route('portal.profile') }}" class="btn btn-default" style="border-radius: 8px; padding: 10px 20px; font-weight: 600; transition: all 0.3s ease;">
                                    <i class="voyager-x" style="margin-right: 5px;"></i> Cancel
                                </a>
                            </div>
                            
                            <div class="password-strength-info mt-4" style="background: #f8f9fa; border-radius: 8px; padding: 15px; margin-top: 20px;">
                                <h5 style="color: var(--voyager-primary); font-size: 0.9em; font-weight: 600; margin-bottom: 10px;">
                                    <i class="voyager-info-circled" style="margin-right: 5px;"></i> Password Recommendations:
                                </h5>
                                <ul style="padding-left: 25px; margin-bottom: 0; font-size: 0.85em; color: #6c757d;">
                                    <li>Use at least 8 characters</li>
                                    <li>Include uppercase and lowercase letters</li>
                                    <li>Use numbers and special characters</li>
                                    <li>Avoid using easily guessable information</li>
                                </ul>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
