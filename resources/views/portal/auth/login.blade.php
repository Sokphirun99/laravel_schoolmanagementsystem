<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="robots" content="none" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'School Management System') }} - Student & Parent Portal</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.ico') }}">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('voyager-assets/css/app.css') }}">
    
    <style>
        :root {
            --voyager-primary: #22A7F0;
            --voyager-secondary: #2B3D51;
            --voyager-dark: #1A2226;
            --voyager-bg: #F9FAFC;
            --voyager-sidebar: #2A3F54;
        }

        body {
            font-family: 'Open Sans', sans-serif;
            background: linear-gradient(135deg, var(--voyager-sidebar), var(--voyager-dark));
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%2322A7F0' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            z-index: 1;
        }

        .login-container {
            position: relative;
            z-index: 2;
            width: 100%;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            max-width: 1000px;
            margin: 0 auto;
        }

        .login-left {
            background: linear-gradient(135deg, var(--voyager-primary), var(--voyager-sidebar));
            color: white;
            padding: 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .login-right {
            padding: 3rem;
            background: white;
        }

        .logo-container {
            margin-bottom: 2rem;
        }

        .logo-container img {
            max-width: 120px;
            height: auto;
            filter: brightness(0) invert(1);
            margin-bottom: 1rem;
        }

        .welcome-text h2 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .welcome-text p {
            font-size: 1.1rem;
            opacity: 0.9;
            line-height: 1.6;
        }

        .features-list {
            list-style: none;
            padding: 0;
            margin-top: 2rem;
        }

        .features-list li {
            padding: 0.5rem 0;
            display: flex;
            align-items: center;
        }

        .features-list li i {
            margin-right: 0.75rem;
            color: rgba(255, 255, 255, 0.8);
        }

        .login-form h3 {
            color: var(--voyager-secondary);
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .login-form .subtitle {
            color: #6c757d;
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: var(--voyager-secondary);
            margin-bottom: 0.5rem;
        }

        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--voyager-primary);
            box-shadow: 0 0 0 0.2rem rgba(34, 167, 240, 0.25);
        }

        .input-group-text {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-right: none;
            color: #6c757d;
        }

        .input-group .form-control {
            border-left: none;
        }

        .btn-login {
            background: var(--voyager-primary);
            border: none;
            border-radius: 8px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            font-size: 1rem;
            width: 100%;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background: #1e96d9;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(34, 167, 240, 0.4);
        }

        .forgot-password {
            color: var(--voyager-primary);
            text-decoration: none;
            font-weight: 500;
        }

        .forgot-password:hover {
            color: #1e96d9;
            text-decoration: underline;
        }

        .divider {
            text-align: center;
            margin: 2rem 0;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e9ecef;
            z-index: 1;
        }

        .divider span {
            background: white;
            padding: 0 1rem;
            color: #6c757d;
            font-size: 0.9rem;
            position: relative;
            z-index: 2;
        }

        .quick-access {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-top: 1rem;
        }

        .quick-access-btn {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 0.75rem;
            text-decoration: none;
            color: var(--voyager-secondary);
            transition: all 0.3s ease;
            text-align: center;
        }

        .quick-access-btn:hover {
            background: var(--voyager-primary);
            color: white;
            border-color: var(--voyager-primary);
        }

        @media (max-width: 768px) {
            .login-left {
                display: none;
            }
            
            .login-right {
                padding: 2rem 1.5rem;
            }
            
            .quick-access {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container login-container">
        <div class="login-card">
            <div class="row g-0">
                <!-- Left Side - Welcome -->
                <div class="col-lg-6 login-left">
                    <div class="logo-container">
                        <img src="{{ asset('images/school-logo.png') }}" alt="School Logo">
                        <h2>{{ config('app.name', 'School Portal') }}</h2>
                    </div>
                    
                    <div class="welcome-text">
                        <h2>Welcome to Your Portal</h2>
                        <p>Access your academic information, grades, assignments, and communicate with teachers all in one place.</p>
                    </div>
                    
                    <ul class="features-list">
                        <li><i class="fas fa-graduation-cap"></i>View Grades & Progress</li>
                        <li><i class="fas fa-tasks"></i>Track Assignments</li>
                        <li><i class="fas fa-calendar-alt"></i>Check Attendance</li>
                        <li><i class="fas fa-envelope"></i>Communicate with Teachers</li>
                        <li><i class="fas fa-bullhorn"></i>Stay Updated with Announcements</li>
                    </ul>
                </div>
                
                <!-- Right Side - Login Form -->
                <div class="col-lg-6 login-right">
                    <div class="login-form">
                        <h3>Sign In to Your Account</h3>
                        <p class="subtitle">Enter your credentials to access the portal</p>

                        @if(!$errors->isEmpty())
                            <div class="alert alert-danger">
                                <ul class="list-unstyled mb-0">
                                    @foreach($errors->all() as $err)
                                        <li><i class="fas fa-exclamation-circle me-2"></i>{{ $err }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('portal.login') }}" method="POST">
                            @csrf
                            
                            <div class="form-group">
                                <label for="email" class="form-label">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <input type="email" 
                                           name="email" 
                                           id="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           value="{{ old('email') }}" 
                                           placeholder="Enter your email"
                                           required 
                                           autocomplete="email" 
                                           autofocus>
                                </div>
                                @error('email')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" 
                                           name="password" 
                                           id="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           placeholder="Enter your password"
                                           required 
                                           autocomplete="current-password">
                                </div>
                                @error('password')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <div class="form-check">
                                    <input type="checkbox" 
                                           name="remember" 
                                           id="remember" 
                                           class="form-check-input" 
                                           value="1" 
                                           {{ old('remember') ? 'checked' : '' }}>
                                    <label for="remember" class="form-check-label">
                                        Remember me for 30 days
                                    </label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-login">
                                <i class="fas fa-sign-in-alt me-2"></i>
                                <span class="signin">Sign In to Portal</span>
                                <span class="signingin d-none">
                                    <i class="fas fa-spinner fa-spin me-2"></i>Signing in...
                                </span>
                            </button>

                            <div class="text-center mt-3">
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="forgot-password">
                                        <i class="fas fa-key me-1"></i>Forgot your password?
                                    </a>
                                @endif
                            </div>
                        </form>

                        <div class="divider">
                            <span>Need help?</span>
                        </div>

                        <div class="quick-access">
                            <a href="{{ url('/') }}" class="quick-access-btn">
                                <i class="fas fa-home"></i>
                                <div class="mt-1">Home</div>
                            </a>
                            <a href="{{ url('/admin') }}" class="quick-access-btn">
                                <i class="fas fa-cog"></i>
                                <div class="mt-1">Admin</div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Form submission handling
        const form = document.querySelector('form');
        const submitBtn = document.querySelector('button[type="submit"]');
        const signinText = submitBtn.querySelector('.signin');
        const signinginText = submitBtn.querySelector('.signingin');

        form.addEventListener('submit', function(e) {
            if (form.checkValidity()) {
                signinText.classList.add('d-none');
                signinginText.classList.remove('d-none');
                submitBtn.disabled = true;
            }
        });

        // Focus management
        document.getElementById('email').focus();
    </script>
</body>
</html>
