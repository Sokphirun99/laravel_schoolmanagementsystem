<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="School Management System - A comprehensive solution for educational institutions">
        <meta name="keywords" content="school, management, education, students, teachers, admin">
        
        <!-- Open Graph / Social Media Meta Tags -->
        <meta property="og:title" content="{{ config('app.name', 'School Management System') }}">
        <meta property="og:description" content="A comprehensive solution for educational institutions to manage administrative tasks and student records">
        <meta property="og:image" content="{{ asset('images/school-logo.png') }}">
        <meta property="og:url" content="{{ url('/') }}">
        <meta property="og:type" content="website">
        <meta name="twitter:card" content="summary_large_image">
        
        <title>{{ config('app.name', 'School Management System') }}</title>
        
        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('favicon.ico') }}">

        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

        <!-- Styles -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="{{ asset('voyager-assets/css/app.css') }}" rel="stylesheet">
        
        <style>
            :root {
                /* Voyager exact color palette */
                --voyager-primary: #22A7F0;
                --voyager-secondary: #2B3D51;
                --voyager-bg: #F9FAFC;
                --voyager-bg-darker: #EEF3F7;
                --voyager-text: #333;
                --voyager-text-light: #fff;
                --voyager-accent: #62A8EA;
                --voyager-border: #E4EAEC;
                --voyager-sidebar: #2A3F54;
                --voyager-dark: #1A2226;
            }
            
            body {
                font-family: 'Open Sans', sans-serif;
                background-color: var(--voyager-bg);
                color: var(--voyager-text);
                overflow-x: hidden;
            }
            
            .welcome-header {
                background-color: var(--voyager-sidebar);
                padding: 3rem 0;
                border-bottom: 3px solid var(--voyager-primary);
                position: relative;
                color: var(--voyager-text-light);
            }
            
            .welcome-header::after {
                content: '';
                position: absolute;
                bottom: -20px;
                left: 50%;
                transform: translateX(-50%);
                width: 40px;
                height: 40px;
                background-color: var(--voyager-secondary);
                transform: rotate(45deg);
                border-bottom: 3px solid var(--voyager-primary);
                border-right: 3px solid var(--voyager-primary);
                z-index: 1;
            }
            
            .school-logo {
                max-width: 150px;
                filter: drop-shadow(0 5px 10px rgba(0,0,0,0.2));
                animation: float 6s ease-in-out infinite;
            }
            
            @keyframes float {
                0% { transform: translateY(0px); }
                50% { transform: translateY(-10px); }
                100% { transform: translateY(0px); }
            }
            
            .navbar {
                background-color: var(--voyager-sidebar);
                border-bottom: 3px solid var(--voyager-primary);
                box-shadow: 0 2px 10px rgba(0,0,0,0.2);
                padding: 0.75rem 0;
            }
            
            .navbar-brand {
                font-weight: 700;
                font-size: 1.5rem;
                display: flex;
                align-items: center;
                gap: 10px;
                color: white !important;
            }
            
            .nav-link {
                color: rgba(255, 255, 255, 0.8) !important;
                font-weight: 600;
                text-transform: uppercase;
                font-size: 0.8rem;
                letter-spacing: 0.5px;
            }
            
            .nav-link.active, .nav-link:hover {
                color: white !important;
            }
            
            .card {
                background-color: white;
                border: 1px solid var(--voyager-border);
                border-radius: 3px;
                overflow: hidden;
                box-shadow: 0 2px 4px rgba(0,0,0,0.05);
                margin-bottom: 30px;
                transition: all 0.3s ease;
                height: 100%;
            }
            
            .card:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 25px rgba(0,0,0,0.2);
                border-color: var(--voyager-primary);
            }
            
            .card-header {
                background: var(--voyager-primary);
                color: white;
                font-weight: bold;
                border: none;
                padding: 10px 15px;
                font-size: 1rem;
                position: relative;
                overflow: hidden;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }
            
            .card-header h4, .card-header h5 {
                margin-bottom: 0;
                font-size: 1rem;
                font-weight: 600;
            }
            
            .card-body {
                padding: 1.25rem;
                color: #76838f;
            }
            
            .btn-voyager {
                background-color: var(--voyager-primary);
                border: none;
                color: white;
                font-weight: 600;
                text-transform: uppercase;
                font-size: 0.8rem;
                letter-spacing: 0.5px;
                padding: 8px 15px;
                transition: all 0.2s ease;
            }
            
            .btn-voyager:hover {
                color: white;
                background-color: #1e96d9;
                box-shadow: 0 2px 5px rgba(34, 167, 240, 0.3);
            }
            
            .btn-outline-light {
                font-weight: 600;
                text-transform: uppercase;
                font-size: 0.8rem;
                letter-spacing: 0.5px;
                padding: 8px 15px;
            }
            
            .login-section {
                display: flex;
                align-items: center;
            }
            
            .features-icon {
                font-size: 2.5rem;
                color: var(--voyager-primary);
                margin-bottom: 1rem;
                transition: all 0.3s ease;
            }
            
            .card:hover .features-icon {
                color: var(--voyager-accent);
            }
            
            .footer {
                background-color: var(--voyager-sidebar);
                padding: 1.5rem 0;
                margin-top: 3rem;
                border-top: 3px solid var(--voyager-primary);
                position: relative;
                color: var(--voyager-text-light);
            }
            
            .section-title {
                position: relative;
                display: inline-block;
                margin-bottom: 2rem;
                color: #505050;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 1px;
                font-size: 1.5rem;
            }
            
            .section-title::after {
                content: '';
                position: absolute;
                bottom: -10px;
                left: 50%;
                transform: translateX(-50%);
                width: 60px;
                height: 3px;
                background: var(--voyager-primary);
            }
            
            .page-title {
                color: #505050;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 1px;
            }
            
            .panel {
                background-color: #fff;
                border: 0 solid transparent;
                border-radius: 3px;
                box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
            }
            
            .voyager-bg-pattern {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                opacity: 0.03;
                background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
                z-index: 0;
            }
            
            .list-unstyled li {
                margin-bottom: 0.5rem;
                position: relative;
                padding-left: 5px;
                transition: all 0.2s ease;
            }
            
            .list-unstyled li:hover {
                color: var(--voyager-primary);
            }
            
            /* Voyager specific styles */
            .page-content {
                background: #F9FAFC;
                border-radius: 3px;
                padding: 15px;
            }
            
            .panel-bordered {
                border: 1px solid #E4EAEC;
            }
            
            .voyager-breadcrumbs {
                display: flex;
                list-style: none;
                padding: 0;
                margin-bottom: 20px;
            }
            
            .voyager-breadcrumbs li {
                display: inline-flex;
                align-items: center;
            }
            
            .voyager-breadcrumbs li:not(:last-child):after {
                content: '/';
                margin: 0 5px;
                color: #ccc;
            }
            
            .text-primary {
                color: var(--voyager-primary) !important;
            }
            
            /* Responsive adjustments */
            @media (max-width: 767px) {
                .login-section {
                    position: static;
                    margin-top: 1rem;
                    display: flex;
                    justify-content: center;
                    width: 100%;
                }
                
                .navbar > .container {
                    flex-direction: column;
                }
                
                .welcome-header::after {
                    display: none;
                }
            }
        </style>
    </head>
    <body>
        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ asset('images/school-logo.png') }}" height="40" class="d-inline-block align-top" alt="School Logo">
                    <span>{{ config('app.name', 'School Management System') }}</span>
                </a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
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
                            <a class="nav-link" href="https://voyager-docs.devdojo.com/" target="_blank">Documentation</a>
                        </li>
                    </ul>
                    
                    <div class="login-section">                    <a href="{{ url('/admin/login') }}" class="btn btn-voyager">
                        <i class="fas fa-sign-in-alt me-1"></i> Admin Login
                    </a>
                    <a href="{{ route('portal.login') }}" class="btn btn-outline-light ms-2">
                        <i class="fas fa-user-graduate me-1"></i> Portal Login
                    </a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Welcome Header -->
        <header class="welcome-header text-center py-5 position-relative">
            <div class="voyager-bg-pattern"></div>
            <div class="container position-relative" style="z-index: 1;">
                <img src="{{ asset('images/school-logo.png') }}" alt="School Logo" class="school-logo mb-4">
                <h1 class="display-4 fw-bold" style="text-transform: uppercase; letter-spacing: 1px;">School Management System</h1>
                <p class="lead mb-4">Built with Laravel and Voyager Admin</p>
                <div class="mb-4">
                    <span class="badge bg-primary me-2 p-2">Students</span>
                    <span class="badge bg-success me-2 p-2">Teachers</span>
                    <span class="badge bg-info me-2 p-2">Parents</span>
                    <span class="badge bg-warning me-2 p-2">Administrators</span>
                </div>
                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ url('/admin/login') }}" class="btn btn-voyager">
                        <i class="fas fa-tachometer-alt me-2"></i> Admin Panel
                    </a>
                    <a href="{{ route('portal.login') }}" class="btn btn-outline-light">
                        <i class="fas fa-user-graduate me-2"></i> User Portal
                    </a>
                    <a href="#features" class="btn btn-outline-light">
                        <i class="fas fa-info-circle me-2"></i> Learn More
                    </a>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <div class="container py-5">
            <!-- Features Section -->
            <section id="features" class="py-5">
                <div class="row mb-5">
                    <div class="col-12 text-center mb-5">
                        <h2 class="section-title">Key Features</h2>
                        <p class="text-muted">Discover the powerful tools that make our school management system stand out</p>
                    </div>
                    
                    <div class="col-lg-4 col-md-6 text-center mb-4">
                        <div class="card h-100">
                            <div class="card-header">
                                <h4>Admin Panel</h4>
                            </div>
                            <div class="card-body d-flex flex-column">
                                <div class="features-icon">
                                    <i class="fas fa-user-shield"></i>
                                </div>
                                <p>Comprehensive admin dashboard powered by Voyager, giving you full control over all aspects of the school management system.</p>
                                <div class="mt-auto">
                                    <a href="{{ url('/admin') }}" class="btn btn-voyager">
                                        <i class="fas fa-external-link-alt me-2"></i>Access Admin
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4 col-md-6 text-center mb-4">
                        <div class="card h-100">
                            <div class="card-header">
                                <h4>Student Portal</h4>
                            </div>
                            <div class="card-body d-flex flex-column">
                                <div class="features-icon">
                                    <i class="fas fa-user-graduate"></i>
                                </div>
                                <p>Students can access their grades, assignments, attendance records, and communicate with teachers all in one place.</p>
                                <div class="mt-auto">
                                    <a href="{{ route('portal.login') }}" class="btn btn-voyager">
                                        <i class="fas fa-sign-in-alt me-2"></i>Student Login
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4 col-md-6 text-center mb-4">
                        <div class="card h-100">
                            <div class="card-header">
                                <h4>Parent Portal</h4>
                            </div>
                            <div class="card-body d-flex flex-column">
                                <div class="features-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <p>Parents can track their children's progress, communicate with teachers, and stay updated with announcements and events.</p>
                                <div class="mt-auto">
                                    <a href="{{ route('portal.login') }}" class="btn btn-voyager">
                                        <i class="fas fa-sign-in-alt me-2"></i>Parent Login
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Additional Features -->
                <div class="row mt-5">
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <div class="features-icon" style="font-size: 2rem;">
                                    <i class="fas fa-mobile-alt"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5>Mobile Responsive</h5>
                                <p class="text-muted">Access the system from any device, anywhere, anytime.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <div class="features-icon" style="font-size: 2rem;">
                                    <i class="fas fa-lock"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5>Secure Access</h5>
                                <p class="text-muted">Role-based permission system ensures data security.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <div class="features-icon" style="font-size: 2rem;">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5>Detailed Analytics</h5>
                                <p class="text-muted">Track performance with powerful reporting tools.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <div class="features-icon" style="font-size: 2rem;">
                                    <i class="fas fa-bell"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5>Real-time Notifications</h5>
                                <p class="text-muted">Stay updated with instant alerts and reminders.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- System Modules -->
            <section id="modules" class="py-5">
                <div class="row">
                    <div class="col-12 text-center mb-5">
                        <h2 class="section-title">System Modules</h2>
                        <p class="text-muted">Explore the comprehensive modules designed to streamline school administration</p>
                    </div>
                    
                    <div class="col-lg-3 col-md-6 text-center mb-4">
                        <div class="card h-100">
                            <div class="card-header">
                                <h5>Academic Management</h5>
                            </div>
                            <div class="card-body">
                                <div class="features-icon">
                                    <i class="fas fa-book"></i>
                                </div>
                                <ul class="list-unstyled text-start">
                                    <li><i class="fas fa-check-circle me-2 text-success"></i>Course Management</li>
                                    <li><i class="fas fa-check-circle me-2 text-success"></i>Class Scheduling</li>
                                    <li><i class="fas fa-check-circle me-2 text-success"></i>Assignments & Grading</li>
                                    <li><i class="fas fa-check-circle me-2 text-success"></i>Exams & Results</li>
                                </ul>
                                <a href="{{ url('/admin') }}" class="btn btn-sm btn-outline-light mt-3">Explore Module</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6 text-center mb-4">
                        <div class="card h-100">
                            <div class="card-header">
                                <h5>Student Management</h5>
                            </div>
                            <div class="card-body">
                                <div class="features-icon">
                                    <i class="fas fa-user-graduate"></i>
                                </div>
                                <ul class="list-unstyled text-start">
                                    <li><i class="fas fa-check-circle me-2 text-success"></i>Student Registration</li>
                                    <li><i class="fas fa-check-circle me-2 text-success"></i>Attendance Tracking</li>
                                    <li><i class="fas fa-check-circle me-2 text-success"></i>Performance Reports</li>
                                    <li><i class="fas fa-check-circle me-2 text-success"></i>Student Portfolios</li>
                                </ul>
                                <a href="{{ url('/admin') }}" class="btn btn-sm btn-outline-light mt-3">Explore Module</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6 text-center mb-4">
                        <div class="card h-100">
                            <div class="card-header">
                                <h5>Financial Management</h5>
                            </div>
                            <div class="card-body">
                                <div class="features-icon">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                                <ul class="list-unstyled text-start">
                                    <li><i class="fas fa-check-circle me-2 text-success"></i>Fee Collection</li>
                                    <li><i class="fas fa-check-circle me-2 text-success"></i>Payment Tracking</li>
                                    <li><i class="fas fa-check-circle me-2 text-success"></i>Expense Management</li>
                                    <li><i class="fas fa-check-circle me-2 text-success"></i>Financial Reports</li>
                                </ul>
                                <a href="{{ url('/admin') }}" class="btn btn-sm btn-outline-light mt-3">Explore Module</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6 text-center mb-4">
                        <div class="card h-100">
                            <div class="card-header">
                                <h5>Communication</h5>
                            </div>
                            <div class="card-body">
                                <div class="features-icon">
                                    <i class="fas fa-comments"></i>
                                </div>
                                <ul class="list-unstyled text-start">
                                    <li><i class="fas fa-check-circle me-2 text-success"></i>Announcements</li>
                                    <li><i class="fas fa-check-circle me-2 text-success"></i>Teacher-Parent Chat</li>
                                    <li><i class="fas fa-check-circle me-2 text-success"></i>Newsletters</li>
                                    <li><i class="fas fa-check-circle me-2 text-success"></i>Event Calendar</li>
                                </ul>
                                <a href="{{ url('/admin') }}" class="btn btn-sm btn-outline-light mt-3">Explore Module</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Stats Section -->
                <div class="row mt-5">
                    <div class="col-12 mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <h5 class="page-title mb-0">SYSTEM STATISTICS</h5>
                            <div class="ms-auto">
                                <span class="badge bg-primary"><i class="fas fa-chart-line me-1"></i> Live Data</span>
                            </div>
                        </div>
                        <div class="panel panel-bordered">
                            <div class="panel-body py-4">
                                <div class="row text-center">
                                    <div class="col-md-3 col-6 mb-3 mb-md-0">
                                        <h2 class="display-5 fw-bold text-primary mb-0">20+</h2>
                                        <p class="text-muted mb-0">Admin Features</p>
                                        <div class="progress mt-2" style="height: 5px;">
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6 mb-3 mb-md-0">
                                        <h2 class="display-5 fw-bold text-success mb-0">10+</h2>
                                        <p class="text-muted mb-0">Student Tools</p>
                                        <div class="progress mt-2" style="height: 5px;">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <h2 class="display-5 fw-bold text-info mb-0">8+</h2>
                                        <p class="text-muted mb-0">Parent Features</p>
                                        <div class="progress mt-2" style="height: 5px;">
                                            <div class="progress-bar bg-info" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <h2 class="display-5 fw-bold text-warning mb-0">24/7</h2>
                                        <p class="text-muted mb-0">System Availability</p>
                                        <div class="progress mt-2" style="height: 5px;">
                                            <div class="progress-bar bg-warning" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
                
            <!-- Call to Action -->
            <section class="py-5">
                <div class="row">
                    <div class="col-12 mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <h5 class="page-title mb-0">GET STARTED</h5>
                            <div class="ms-auto">
                                <span class="badge bg-success"><i class="fas fa-check me-1"></i> Ready to Deploy</span>
                            </div>
                        </div>
                        <div class="panel panel-bordered" style="background-color: var(--voyager-sidebar); color: white;">
                            <div class="voyager-bg-pattern"></div>
                            <div class="p-4 position-relative">
                                <div class="row align-items-center">
                                    <div class="col-lg-8 mb-4 mb-lg-0">
                                        <h5 style="text-transform: uppercase; letter-spacing: 1px;">Ready to transform your school management?</h5>
                                        <p class="mb-0">Get started with our comprehensive school management system and streamline your administrative processes.</p>
                                        <ul class="voyager-breadcrumbs mt-3 mb-0">
                                            <li><span><i class="fas fa-check-circle text-success me-1"></i> Easy Setup</span></li>
                                            <li><span><i class="fas fa-check-circle text-success me-1"></i> User Friendly</span></li>
                                            <li><span><i class="fas fa-check-circle text-success me-1"></i> Full Support</span></li>
                                        </ul>
                                    </div>
                                    <div class="col-lg-4 text-lg-end">
                                        <a href="{{ url('/admin/login') }}" class="btn btn-voyager me-2">
                                            <i class="fas fa-tachometer-alt me-2"></i> Admin Panel
                                        </a>
                                        <a href="{{ route('portal.login') }}" class="btn btn-outline-light">
                                            <i class="fas fa-user me-2"></i> User Portal
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        
        <!-- Footer -->
        <footer class="footer">
            <div class="voyager-bg-pattern"></div>
            <div class="container position-relative" style="z-index: 1;">
                <div class="row py-4">
                    <div class="col-lg-4 mb-4 mb-lg-0">
                        <h5 class="text-uppercase mb-3">School Management System</h5>
                        <p class="mb-3">A comprehensive solution for educational institutions to manage administrative tasks, student records, and facilitate communication between teachers, students, and parents.</p>
                        <div class="d-flex gap-3">
                            <a href="#" class="text-light"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="text-light"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="text-light"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="text-light"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 mb-4 mb-lg-0">
                        <h6 class="text-uppercase mb-3">Quick Links</h6>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2"><a href="#" class="text-light text-decoration-none">Home</a></li>
                            <li class="mb-2"><a href="#features" class="text-light text-decoration-none">Features</a></li>
                            <li class="mb-2"><a href="#modules" class="text-light text-decoration-none">Modules</a></li>
                            <li class="mb-2"><a href="{{ url('/admin/login') }}" class="text-light text-decoration-none">Admin Login</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-2 col-md-6 mb-4 mb-lg-0">
                        <h6 class="text-uppercase mb-3">Portals</h6>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2"><a href="{{ route('portal.login') }}" class="text-light text-decoration-none">Student Portal</a></li>
                            <li class="mb-2"><a href="{{ route('portal.login') }}" class="text-light text-decoration-none">Parent Portal</a></li>
                            <li class="mb-2"><a href="{{ route('portal.login') }}" class="text-light text-decoration-none">Teacher Portal</a></li>
                            <li class="mb-2"><a href="{{ url('/admin') }}" class="text-light text-decoration-none">Admin Panel</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-4">
                        <h6 class="text-uppercase mb-3">Contact</h6>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i> 123 School Street, Education City</li>
                            <li class="mb-2"><i class="fas fa-phone me-2"></i> +1 (555) 123-4567</li>
                            <li class="mb-2"><i class="fas fa-envelope me-2"></i> info@schoolsystem.com</li>
                        </ul>
                    </div>
                </div>
                <hr class="my-2 border-top border-secondary">
                <div class="row py-2">
                    <div class="col-md-6 text-center text-md-start">
                        <p class="mb-0 small">© {{ date('Y') }} School Management System. All rights reserved.</p>
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <p class="mb-0 small">
                            Powered by Laravel v{{ Illuminate\Foundation\Application::VERSION }} | 
                            PHP v{{ PHP_VERSION }} |
                            <a href="https://voyager.devdojo.com/" class="text-light" target="_blank">Voyager Admin</a>
                        </p>
                    </div>
                </div>
            </div>
        </footer>

        <!-- Scripts -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Smooth scrolling for anchor links
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
