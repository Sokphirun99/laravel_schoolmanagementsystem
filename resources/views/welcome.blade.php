<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Management System - Voyager Style</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.ico') }}">
    
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('voyager-assets/css/app.css') }}" rel="stylesheet">
    
    <style>
        :root {
            --voyager-primary: #22A7F0;
            --voyager-secondary: #2B3D51;
            --voyager-dark: #1A2226;
            --voyager-bg: #F9FAFC;
            --voyager-bg-darker: #EEF3F7;
            --voyager-text: #333;
            --voyager-text-light: #fff;
            --voyager-accent: #62A8EA;
            --voyager-border: #E4EAEC;
            --voyager-sidebar: #2A3F54;
            --voyager-success: #4CAF50;
            --voyager-warning: #FF9800;
            --voyager-danger: #F44336;
        }

        body {
            font-family: 'Open Sans', sans-serif;
            background-color: var(--voyager-bg);
            color: var(--voyager-text);
            overflow-x: hidden;
        }

        .voyager-navbar {
            background: var(--voyager-sidebar);
            border-bottom: 3px solid var(--voyager-primary);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            padding: 0.75rem 0;
        }

        .voyager-navbar .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
            color: white !important;
        }

        .voyager-navbar .nav-link {
            color: rgba(255, 255, 255, 0.8) !important;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            position: relative;
            padding: 0.5rem 1rem !important;
        }

        .voyager-navbar .nav-link::after {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--voyager-primary);
            transition: width 0.3s ease;
        }

        .voyager-navbar .nav-link:hover, 
        .voyager-navbar .nav-link.active {
            color: white !important;
        }

        .voyager-navbar .nav-link:hover::after,
        .voyager-navbar .nav-link.active::after {
            width: 100%;
        }

        .hero-section {
            background: linear-gradient(135deg, var(--voyager-sidebar), var(--voyager-dark));
            padding: 4rem 0 6rem;
            position: relative;
            overflow: hidden;
            color: white;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%2322A7F0' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            z-index: 1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .school-logo {
            max-width: 120px;
            margin-bottom: 1.5rem;
            filter: drop-shadow(0 5px 15px rgba(0, 0, 0, 0.3));
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            margin-bottom: 1.5rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .hero-subtitle {
            font-size: 1.5rem;
            font-weight: 300;
            margin-bottom: 2rem;
            max-width: 700px;
            opacity: 0.9;
        }

        .feature-badge {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 30px;
            padding: 0.5rem 1.25rem;
            font-size: 0.85rem;
            font-weight: 500;
            margin: 0 0.5rem 0.5rem 0;
            display: inline-block;
            backdrop-filter: blur(10px);
        }

        .btn-voyager {
            background-color: var(--voyager-primary);
            border: none;
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
            padding: 0.75rem 1.5rem;
            transition: all 0.3s ease;
            border-radius: 4px;
            box-shadow: 0 4px 6px rgba(34, 167, 240, 0.2);
        }

        .btn-voyager:hover {
            background-color: #1e96d9;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(34, 167, 240, 0.3);
            color: white;
        }

        .btn-voyager-outline {
            background: transparent;
            border: 2px solid var(--voyager-primary);
            color: var(--voyager-primary);
        }

        .btn-voyager-outline:hover {
            background: rgba(34, 167, 240, 0.1);
            color: var(--voyager-primary);
        }

        .section-title {
            position: relative;
            display: inline-block;
            margin-bottom: 2.5rem;
            color: var(--voyager-secondary);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 1.8rem;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 50px;
            height: 3px;
            background: var(--voyager-primary);
        }

        .card-voyager {
            background: white;
            border: 1px solid var(--voyager-border);
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
            transition: all 0.3s ease;
            height: 100%;
            position: relative;
        }

        .card-voyager:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border-color: var(--voyager-primary);
        }

        .card-voyager-header {
            background: var(--voyager-primary);
            color: white;
            font-weight: bold;
            border: none;
            padding: 15px 20px;
            font-size: 1.1rem;
            position: relative;
            overflow: hidden;
        }

        .card-voyager-body {
            padding: 25px;
            color: #76838f;
        }

        .features-icon {
            font-size: 2.5rem;
            color: var(--voyager-primary);
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }

        .card-voyager:hover .features-icon {
            color: var(--voyager-accent);
            transform: scale(1.1);
        }

        .stats-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            padding: 25px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .stats-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .stats-label {
            font-size: 0.9rem;
            color: #76838f;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }

        .module-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
            margin-top: 2rem;
        }

        .module-item {
            background: white;
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            border-left: 4px solid var(--voyager-primary);
        }

        .module-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            border-left: 4px solid var(--voyager-accent);
        }

        .module-icon {
            font-size: 2.2rem;
            color: var(--voyager-primary);
            margin-bottom: 1rem;
        }

        .cta-section {
            background: linear-gradient(135deg, var(--voyager-sidebar), var(--voyager-dark));
            padding: 5rem 0;
            color: white;
            border-radius: 10px;
            overflow: hidden;
            position: relative;
        }

        .cta-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%2322A7F0' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            z-index: 1;
        }

        .cta-content {
            position: relative;
            z-index: 2;
        }

        .cta-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }

        .voyager-footer {
            background: var(--voyager-sidebar);
            padding: 4rem 0 2rem;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .voyager-footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%2322A7F0' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            z-index: 1;
        }

        .footer-content {
            position: relative;
            z-index: 2;
        }

        .footer-logo {
            max-width: 150px;
            margin-bottom: 1.5rem;
        }

        .footer-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--voyager-primary);
        }

        .footer-links li {
            margin-bottom: 0.75rem;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .footer-links a:hover {
            color: white;
            padding-left: 5px;
        }

        .social-icons a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            color: white;
            margin-right: 0.75rem;
            transition: all 0.3s ease;
        }

        .social-icons a:hover {
            background: var(--voyager-primary);
            transform: translateY(-3px);
        }

        .copyright {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 1.5rem;
            margin-top: 3rem;
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.2rem;
            }
            
            .section-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark voyager-navbar">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="{{ asset('images/school-logo.png') }}" height="40" class="d-inline-block align-top" alt="School Logo">
                <span>{{ config('app.name', 'School Management System') }}</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="#features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#modules">Modules</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#stats">Statistics</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="https://voyager-docs.devdojo.com/" target="_blank">Documentation</a>
                    </li>
                </ul>
                
                <div class="d-flex gap-2">
                    <a href="{{ url('/admin/login') }}" class="btn btn-voyager btn-sm">
                        <i class="fas fa-sign-in-alt me-1"></i> Admin Login
                    </a>
                    <a href="{{ route('portal.login') }}" class="btn btn-voyager-outline btn-sm">
                        <i class="fas fa-user-graduate me-1"></i> Student Portal
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 hero-content">
                    <h1 class="hero-title">Transform Education with <span style="color: var(--voyager-primary);">Voyager</span></h1>
                    <p class="hero-subtitle">A comprehensive school management solution built with Laravel and Voyager Admin</p>
                    
                    <div class="mb-4">
                        <span class="feature-badge"><i class="fas fa-check-circle me-2"></i> Student Management</span>
                        <span class="feature-badge"><i class="fas fa-check-circle me-2"></i> Academic Tracking</span>
                        <span class="feature-badge"><i class="fas fa-check-circle me-2"></i> Parent Portal</span>
                        <span class="feature-badge"><i class="fas fa-check-circle me-2"></i> Financial System</span>
                    </div>
                    
                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ url('/admin/login') }}" class="btn btn-voyager">
                            <i class="fas fa-tachometer-alt me-2"></i> Admin Panel
                        </a>
                        <a href="{{ route('portal.login') }}" class="btn btn-voyager-outline">
                            <i class="fas fa-user-graduate me-2"></i> User Portal
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 d-none d-lg-block text-center">
                    <img src="{{ asset('images/school-logo.png') }}" alt="School Management" class="img-fluid rounded shadow" style="max-width: 90%; border: 5px solid rgba(255,255,255,0.1);">
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-5">
        <div class="container">
            <div class="row mb-4">
                <div class="col-lg-6">
                    <h2 class="section-title">Key Features</h2>
                    <p class="text-muted">Our school management system offers a comprehensive suite of tools designed to streamline administrative tasks and enhance educational outcomes.</p>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="card-voyager h-100">
                        <div class="card-voyager-body text-center">
                            <div class="features-icon">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <h4 class="mb-3">Student Management</h4>
                            <p class="mb-0">Comprehensive student profiles, attendance tracking, and performance analytics.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3">
                    <div class="card-voyager h-100">
                        <div class="card-voyager-body text-center">
                            <div class="features-icon">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </div>
                            <h4 class="mb-3">Teacher Portal</h4>
                            <p class="mb-0">Tools for attendance management, grade submission, and communication with students and parents.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3">
                    <div class="card-voyager h-100">
                        <div class="card-voyager-body text-center">
                            <div class="features-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <h4 class="mb-3">Parent Access</h4>
                            <p class="mb-0">Real-time access to student grades, attendance, and direct communication with teachers.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3">
                    <div class="card-voyager h-100">
                        <div class="card-voyager-body text-center">
                            <div class="features-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <h4 class="mb-3">Analytics</h4>
                            <p class="mb-0">Detailed reports and insights on student performance, attendance, and institutional metrics.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3">
                    <div class="card-voyager h-100">
                        <div class="card-voyager-body text-center">
                            <div class="features-icon">
                                <i class="fas fa-book"></i>
                            </div>
                            <h4 class="mb-3">Course Management</h4>
                            <p class="mb-0">Easily create, manage, and schedule courses with integrated curriculum planning.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3">
                    <div class="card-voyager h-100">
                        <div class="card-voyager-body text-center">
                            <div class="features-icon">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <h4 class="mb-3">Scheduling</h4>
                            <p class="mb-0">Automated timetable generation and calendar management for classes and events.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3">
                    <div class="card-voyager h-100">
                        <div class="card-voyager-body text-center">
                            <div class="features-icon">
                                <i class="fas fa-file-invoice-dollar"></i>
                            </div>
                            <h4 class="mb-3">Fee Management</h4>
                            <p class="mb-0">Streamlined billing, payment tracking, and financial reporting for school finances.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-3">
                    <div class="card-voyager h-100">
                        <div class="card-voyager-body text-center">
                            <div class="features-icon">
                                <i class="fas fa-bullhorn"></i>
                            </div>
                            <h4 class="mb-3">Announcements</h4>
                            <p class="mb-0">Broadcast important information to specific groups or the entire school community.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modules Section -->
    <section id="modules" class="py-5 bg-light">
        <div class="container">
            <div class="row mb-4">
                <div class="col-lg-6">
                    <h2 class="section-title">System Modules</h2>
                    <p class="text-muted">Our modular system is designed to address every aspect of school management.</p>
                </div>
            </div>
            
            <div class="module-grid">
                <div class="module-item">
                    <div class="module-icon">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <h4 class="mb-2">Administration</h4>
                    <p class="mb-0">Complete administrative control with role-based access and permissions.</p>
                </div>
                
                <div class="module-item">
                    <div class="module-icon">
                        <i class="fas fa-users-cog"></i>
                    </div>
                    <h4 class="mb-2">Student Affairs</h4>
                    <p class="mb-0">Manage student records, discipline, and extracurricular activities.</p>
                </div>
                
                <div class="module-item">
                    <div class="module-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h4 class="mb-2">Academics</h4>
                    <p class="mb-0">Course planning, curriculum management, and student grading system.</p>
                </div>
                
                <div class="module-item">
                    <div class="module-icon">
                        <i class="fas fa-clipboard-check"></i>
                    </div>
                    <h4 class="mb-2">Attendance</h4>
                    <p class="mb-0">Track student and staff attendance with automated reporting.</p>
                </div>
                
                <div class="module-item">
                    <div class="module-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h4 class="mb-2">Examinations</h4>
                    <p class="mb-0">Schedule exams, record results, and generate performance reports.</p>
                </div>
                
                <div class="module-item">
                    <div class="module-icon">
                        <i class="fas fa-comment"></i>
                    </div>
                    <h4 class="mb-2">Communication</h4>
                    <p class="mb-0">Messaging system for students, parents, teachers, and administrators.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section id="stats" class="py-5">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12 text-center">
                    <h2 class="section-title">System Statistics</h2>
                    <p class="text-muted">A growing ecosystem supporting educational institutions worldwide</p>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-6 col-md-3">
                    <div class="stats-card">
                        <div class="stats-number text-primary">1000+</div>
                        <div class="stats-label">Students</div>
                    </div>
                </div>
                
                <div class="col-6 col-md-3">
                    <div class="stats-card">
                        <div class="stats-number" style="color: var(--voyager-success);">100+</div>
                        <div class="stats-label">Teachers</div>
                    </div>
                </div>
                
                <div class="col-6 col-md-3">
                    <div class="stats-card">
                        <div class="stats-number" style="color: var(--voyager-warning);">50+</div>
                        <div class="stats-label">Courses</div>
                    </div>
                </div>
                
                <div class="col-6 col-md-3">
                    <div class="stats-card">
                        <div class="stats-number" style="color: var(--voyager-accent);">24/7</div>
                        <div class="stats-label">Support</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5">
        <div class="container">
            <div class="cta-section">
                <div class="container cta-content text-center">
                    <h2 class="cta-title">Ready to Transform Your School Management?</h2>
                    <p class="mb-4">Get started today and experience the difference our system can make for your institution.</p>
                    <div class="d-flex justify-content-center gap-3 flex-wrap">
                        <a href="{{ url('/admin/login') }}" class="btn btn-voyager">
                            <i class="fas fa-rocket me-2"></i> Get Started
                        </a>
                        <a href="https://voyager-docs.devdojo.com/" target="_blank" class="btn btn-light">
                            <i class="fas fa-book me-2"></i> Documentation
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="voyager-footer">
        <div class="container footer-content">
            <div class="row mb-5">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <img src="{{ asset('images/school-logo.png') }}" alt="School Logo" class="footer-logo">
                    <p>A comprehensive school management system built with Laravel and Voyager Admin, designed to streamline educational administration and enhance learning outcomes.</p>
                    <div class="social-icons mt-4">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-github"></i></a>
                    </div>
                </div>
                
                <div class="col-sm-6 col-lg-2 mb-4 mb-lg-0">
                    <h5 class="footer-title">Quick Links</h5>
                    <ul class="list-unstyled footer-links">
                        <li><a href="#features">Features</a></li>
                        <li><a href="#modules">Modules</a></li>
                        <li><a href="#stats">Statistics</a></li>
                        <li><a href="{{ url('/admin/login') }}">Admin Login</a></li>
                        <li><a href="{{ route('portal.login') }}">Portal Login</a></li>
                    </ul>
                </div>
                
                <div class="col-sm-6 col-lg-3 mb-4 mb-lg-0">
                    <h5 class="footer-title">Resources</h5>
                    <ul class="list-unstyled footer-links">
                        <li><a href="https://voyager-docs.devdojo.com/" target="_blank">Documentation</a></li>
                        <li><a href="https://laracasts.com" target="_blank">Tutorials</a></li>
                        <li><a href="https://laravel.com" target="_blank">Laravel</a></li>
                        <li><a href="https://github.com/the-control-group/voyager" target="_blank">Voyager GitHub</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-3">
                    <h5 class="footer-title">Contact Us</h5>
                    <ul class="list-unstyled footer-links">
                        <li><i class="fas fa-map-marker-alt me-2"></i> 123 Education Ave., Campus Way</li>
                        <li><i class="fas fa-phone me-2"></i> (123) 456-7890</li>
                        <li><i class="fas fa-envelope me-2"></i> info@schoolsystem.com</li>
                    </ul>
                </div>
            </div>
            
            <div class="copyright text-center">
                <p>&copy; {{ date('Y') }} {{ config('app.name', 'School Management System') }}. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Smooth scrolling for navigation links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    
                    document.querySelector(this.getAttribute('href')).scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            });
            
            // Add active class to navbar items on scroll
            window.addEventListener('scroll', function() {
                let scrollPosition = window.scrollY;
                
                document.querySelectorAll('section').forEach(section => {
                    const sectionTop = section.offsetTop - 100;
                    const sectionHeight = section.offsetHeight;
                    const sectionId = section.getAttribute('id');
                    
                    if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
                        document.querySelectorAll('.navbar-nav .nav-link').forEach(link => {
                            link.classList.remove('active');
                            if (link.getAttribute('href') === '#' + sectionId) {
                                link.classList.add('active');
                            }
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>
