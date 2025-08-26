@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" style="box-shadow: 0 10px 30px rgba(0,0,0,0.1); border-radius: 10px; overflow: hidden; border: none;">
                <div class="card-header" style="background: linear-gradient(135deg, var(--voyager-primary, #22A7F0), #1989d8); color: white; padding: 15px 20px; position: relative; border-bottom: none;">
                    <h5 class="mb-0" style="font-weight: 600; display: flex; align-items: center;">
                        <i class="fas fa-key me-2" style="background: rgba(255,255,255,0.2); width: 30px; height: 30px; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; margin-right: 10px;"></i>
                        {{ __('Change Password') }}
                    </h5>
                </div>

                <div class="card-body" style="padding: 25px 20px;">
                    <form method="POST" action="{{ route('profile.password.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="current_password" class="form-label" style="color: var(--voyager-primary, #22A7F0); font-weight: 600; margin-bottom: 10px; display: block;">
                                <i class="fas fa-lock" style="background: rgba(34, 167, 240, 0.1); width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; margin-right: 8px;"></i>
                                {{ __('Current Password') }}
                            </label>
                            <div class="input-group">
                                <span class="input-group-text" style="background: #f8f9fa; border: 2px solid #e9ecef; border-right: none; color: var(--voyager-primary, #22A7F0);">
                                    <i class="fas fa-key"></i>
                                </span>
                                <input id="current_password" type="password" class="form-control @error('current_password') is-invalid @enderror" name="current_password" required
                                       style="border: 2px solid #e9ecef; border-left: none; border-radius: 0 8px 8px 0; padding: 10px 15px; transition: all 0.3s ease; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                            </div>
                            @error('current_password')
                                <div class="text-danger mt-2" style="font-size: 0.9em;">
                                    <i class="fas fa-exclamation-circle" style="margin-right: 5px;"></i>
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label" style="color: var(--voyager-primary, #22A7F0); font-weight: 600; margin-bottom: 10px; display: block;">
                                <i class="fas fa-key" style="background: rgba(34, 167, 240, 0.1); width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; margin-right: 8px;"></i>
                                {{ __('New Password') }}
                            </label>
                            <div class="input-group">
                                <span class="input-group-text" style="background: #f8f9fa; border: 2px solid #e9ecef; border-right: none; color: var(--voyager-primary, #22A7F0);">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required
                                       style="border: 2px solid #e9ecef; border-left: none; border-radius: 0 8px 8px 0; padding: 10px 15px; transition: all 0.3s ease; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                            </div>
                            @error('password')
                                <div class="text-danger mt-2" style="font-size: 0.9em;">
                                    <i class="fas fa-exclamation-circle" style="margin-right: 5px;"></i>
                                    <strong>{{ $message }}</strong>
                                </div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password-confirm" class="form-label" style="color: var(--voyager-primary, #22A7F0); font-weight: 600; margin-bottom: 10px; display: block;">
                                <i class="fas fa-check" style="background: rgba(34, 167, 240, 0.1); width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; margin-right: 8px;"></i>
                                {{ __('Confirm New Password') }}
                            </label>
                            <div class="input-group">
                                <span class="input-group-text" style="background: #f8f9fa; border: 2px solid #e9ecef; border-right: none; color: var(--voyager-primary, #22A7F0);">
                                    <i class="fas fa-check-circle"></i>
                                </span>
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required
                                       style="border: 2px solid #e9ecef; border-left: none; border-radius: 0 8px 8px 0; padding: 10px 15px; transition: all 0.3s ease; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                            </div>
                        </div>

                        <hr style="margin: 30px 0; border-top: 1px solid #f5f5f5;">
                        
                        <div class="row mb-0">
                            <div class="col-12 d-flex gap-2 justify-content-center">
                                <button type="submit" class="btn btn-primary" style="background: var(--voyager-primary, #22A7F0); border: none; border-radius: 8px; padding: 10px 20px; font-weight: 600; box-shadow: 0 4px 10px rgba(34, 167, 240, 0.3); transition: all 0.3s ease; position: relative; overflow: hidden;">
                                    <i class="fas fa-save me-2"></i>{{ __('Update Password') }}
                                </button>
                                <a href="{{ route('profile') }}" class="btn btn-secondary" style="border-radius: 8px; padding: 10px 20px; font-weight: 600; transition: all 0.3s ease;">
                                    <i class="fas fa-arrow-left me-2"></i>{{ __('Back to Profile') }}
                                </a>
                            </div>
                        </div>
                        
                        <div class="password-strength-info mt-4" style="background: #f8f9fa; border-radius: 8px; padding: 15px; margin-top: 20px;">
                            <h5 style="color: var(--voyager-primary, #22A7F0); font-size: 0.9em; font-weight: 600; margin-bottom: 10px;">
                                <i class="fas fa-info-circle" style="margin-right: 5px;"></i> Password Recommendations:
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
@endsection
