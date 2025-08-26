@extends('portal.auth.master')

@section('content')
    <div class="login-container">

        <p class="portal-heading">{{ __('Sign in to access the Portal') }}</p>

        <form action="{{ route('portal.login') }}" method="POST">
            @csrf
            <div class="form-group form-group-default" id="emailGroup">
                <label>{{ __('Email Address') }}</label>
                <div class="controls">
                    <input type="text" name="email" id="email" value="{{ old('email') }}" placeholder="{{ __('Enter your email') }}" class="form-control" required>
                </div>
            </div>

            <div class="form-group form-group-default" id="passwordGroup">
                <label>{{ __('Password') }}</label>
                <div class="controls">
                    <input type="password" name="password" placeholder="{{ __('Enter your password') }}" class="form-control" required>
                </div>
            </div>

            <div class="form-group" id="rememberMeGroup">
                <div class="controls">
                    <input type="checkbox" name="remember" id="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                    <label for="remember" class="remember-me-text">{{ __('Remember Me') }}</label>
                </div>
            </div>

            <button type="submit" class="btn btn-block login-button">
                <span class="signingin hidden"><span class="voyager-refresh"></span> {{ __('Signing In...') }}</span>
                <span class="signin">{{ __('Sign In') }}</span>
            </button>
        </form>

        <div style="clear:both"></div>

        @if(!$errors->isEmpty())
            <div class="alert alert-red">
                <ul class="list-unstyled">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="portal-features">
            <div class="features-title">
                <p>{{ __('Portal Features') }}</p>
            </div>
            <div class="features-list">
                <div class="feature-item">
                    <i class="voyager-certificate"></i> <span>{{ __('View Grades') }}</span>
                </div>
                <div class="feature-item">
                    <i class="voyager-book"></i> <span>{{ __('Track Assignments') }}</span>
                </div>
                <div class="feature-item">
                    <i class="voyager-calendar"></i> <span>{{ __('Check Attendance') }}</span>
                </div>
                <div class="feature-item">
                    <i class="voyager-mail"></i> <span>{{ __('Message Teachers') }}</span>
                </div>
            </div>
        </div>

    </div> <!-- .login-container -->

@endsection

@section('post_js')
<script>
    var btn = document.querySelector('button[type="submit"]');
    var form = document.forms[0];
    var email = document.querySelector('[name="email"]');
    var password = document.querySelector('[name="password"]');
    btn.addEventListener('click', function(ev){
        if (form.checkValidity()) {
            btn.querySelector('.signingin').className = 'signingin';
            btn.querySelector('.signin').className = 'signin hidden';
        } else {
            ev.preventDefault();
        }
    });
    email.focus();
    document.getElementById('emailGroup').classList.add("focused");

    // Focus events for email and password fields
    email.addEventListener('focusin', function(e){
        document.getElementById('emailGroup').classList.add("focused");
    });
    email.addEventListener('focusout', function(e){
        document.getElementById('emailGroup').classList.remove("focused");
    });

    password.addEventListener('focusin', function(e){
        document.getElementById('passwordGroup').classList.add("focused");
    });
    password.addEventListener('focusout', function(e){
        document.getElementById('passwordGroup').classList.remove("focused");
    });
</script>
@endsection
