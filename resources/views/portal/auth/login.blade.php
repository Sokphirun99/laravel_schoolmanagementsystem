@extends('voyager::auth.master')

@section('content')
<style>
    .login-container {
        border-top: 5px solid {{ config('voyager.primary_color', '#22A7F0') }};
    }
</style>

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
            transform: translateY(0);
            transition: all 0.5s ease;
        }
        
        .login-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
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
            position: relative;
            overflow: hidden;
        }
        
        .login-left::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100' height='100' viewBox='0 0 100 100'%3E%3Cg fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath opacity='.5' d='M96 95h4v1h-4v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9zm-1 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-9-10h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm9-10v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-9-10h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm9-10v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-9-10h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm9-10v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-9-10h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.2;
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
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
        }

        .features-list {
            list-style: none;
            padding: 0;
            margin-top: 2rem;
            position: relative;
        }

        .features-list li {
            padding: 0.7rem 0;
            display: flex;
            align-items: center;
            transition: transform 0.3s ease;
            opacity: 0.9;
        }
        
        .features-list li:hover {
            transform: translateX(5px);
            opacity: 1;
        }

        .features-list li i {
            margin-right: 0.75rem;
            color: rgba(255, 255, 255, 0.9);
            background: rgba(255, 255, 255, 0.1);
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        .login-form h3 {
            color: var(--voyager-secondary);
            font-weight: 700;
            margin-bottom: 0.5rem;
            position: relative;
            display: inline-block;
        }
        
        .login-form h3::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 40px;
            height: 3px;
            background: var(--voyager-primary);
            border-radius: 2px;
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
            display: flex;
            align-items: center;
        }
        
        .form-label::before {
            content: '';
            display: inline-block;
            width: 6px;
            height: 6px;
            background: var(--voyager-primary);
            border-radius: 50%;
            margin-right: 8px;
            opacity: 0.7;
        }

        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .form-control:focus {
            border-color: var(--voyager-primary);
            box-shadow: 0 0 0 0.2rem rgba(34, 167, 240, 0.25);
            transform: translateY(-1px);
        }

        .input-group-text {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-right: none;
            color: var(--voyager-primary);
            transition: all 0.3s ease;
        }
        
        .input-group:focus-within .input-group-text {
            border-color: var(--voyager-primary);
            background: rgba(34, 167, 240, 0.05);
        }

        .input-group .form-control {
            border-left: none;
        }
        
        .input-group:focus-within .form-control {
            border-color: var(--voyager-primary);
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
            position: relative;
            overflow: hidden;
            z-index: 1;
        }
        
        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            z-index: -1;
            transition: all 0.6s ease;
        }

        .btn-login:hover {
            background: #1e96d9;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(34, 167, 240, 0.4);
        }
        
        .btn-login:hover::before {
            left: 100%;
        }

        .forgot-password {
            color: var(--voyager-primary);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
            padding-bottom: 2px;
        }
        
        .forgot-password::after {
            content: '';
            position: absolute;
            width: 0;
            height: 1px;
            bottom: 0;
            left: 0;
            background-color: var(--voyager-primary);
            transition: all 0.3s ease;
        }

        .forgot-password:hover {
            color: #1e96d9;
        }
        
        .forgot-password:hover::after {
            width: 100%;
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
            position: relative;
            overflow: hidden;
            z-index: 1;
        }
        
        .quick-access-btn::before {
            content: '';
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--voyager-primary);
            transition: all 0.3s ease;
            z-index: -1;
            border-radius: 8px;
        }

        .quick-access-btn:hover {
            color: white;
            border-color: var(--voyager-primary);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .quick-access-btn:hover::before {
            top: 0;
        }
        
        .quick-access-btn i {
            transition: all 0.3s ease;
        }
        
        .quick-access-btn:hover i {
            transform: scale(1.2);
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
<body class="voyager-login">
    <div class="container voyager-login-container">
        <div class="voyager-login-card fade-in-up">
            <div class="row g-0">
                <!-- Left Side - Welcome -->
                <div class="col-lg-6 voyager-login-left">
                    <div class="voyager-logo-container">
                        <img src="{{ asset('images/school-logo.png') }}" alt="School Logo">
                        <h2>{{ config('app.name', 'School Portal') }}</h2>
                    </div>
                    
                    <div class="voyager-welcome-text">
                        <h2>Welcome to Your Portal</h2>
                        <p>Access your academic information, grades, assignments, and communicate with teachers all in one place.</p>
                    </div>
                    
                    <ul class="voyager-features-list">
                        <li><i class="fas fa-graduation-cap"></i>View Grades & Progress</li>
                        <li><i class="fas fa-tasks"></i>Track Assignments</li>
                        <li><i class="fas fa-calendar-alt"></i>Check Attendance</li>
                        <li><i class="fas fa-envelope"></i>Communicate with Teachers</li>
                        <li><i class="fas fa-bullhorn"></i>Stay Updated with Announcements</li>
                        <li><i class="fas fa-chart-line"></i>Monitor Performance</li>
                    </ul>
                </div>
                
                <!-- Right Side - Login Form -->
                <div class="col-lg-6 voyager-login-right">
                    <div class="voyager-login-form">
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
                            
                            <div class="voyager-form-group">
                                <label for="email" class="voyager-form-label">Email Address</label>
                                <div class="input-group voyager-input-group">
                                    <span class="input-group-text voyager-input-group-text">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <input type="email" 
                                           name="email" 
                                           id="email" 
                                           class="form-control voyager-form-control @error('email') is-invalid @enderror" 
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

                            <div class="voyager-form-group">
                                <label for="password" class="voyager-form-label">Password</label>
                                <div class="input-group voyager-input-group">
                                    <span class="input-group-text voyager-input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" 
                                           name="password" 
                                           id="password" 
                                           class="form-control voyager-form-control @error('password') is-invalid @enderror" 
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

                            <button type="submit" class="btn voyager-btn">
                                <i class="fas fa-sign-in-alt me-2"></i>
                                <span class="signin">Sign In to Portal</span>
                                <span class="signingin d-none">
                                    <i class="fas fa-spinner fa-spin me-2"></i>Signing in...
                                </span>
                            </button>

                            <div class="text-center mt-3">
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="voyager-link">
                                        <i class="fas fa-key me-1"></i>Forgot your password?
                                    </a>
                                @endif
                            </div>
                        </form>

                        <div class="voyager-divider">
                            <span>Need help?</span>
                        </div>

                        <div class="voyager-quick-access">
                            <a href="{{ url('/') }}" class="voyager-quick-access-btn">
                                <i class="fas fa-home"></i>
                                <div class="mt-1">Home</div>
                            </a>
                            <a href="{{ url('/admin') }}" class="voyager-quick-access-btn">
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
        
        // Input animations for better UX
        const formInputs = document.querySelectorAll('.form-control');
        formInputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('input-focus');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('input-focus');
            });
        });

        // Animated entrance effect
        document.addEventListener('DOMContentLoaded', function() {
            const loginCard = document.querySelector('.login-card');
            loginCard.style.opacity = '0';
            loginCard.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                loginCard.style.transition = 'all 0.8s ease';
                loginCard.style.opacity = '1';
                loginCard.style.transform = 'translateY(0)';
            }, 200);
        });

        // Focus management
        document.getElementById('email').focus();
    </script>
</body>
</html>
