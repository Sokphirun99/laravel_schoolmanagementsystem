{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Management System - Voyager Style</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
            <a class="navbar-brand" href="#">
                <i class="fas fa-graduation-cap me-2"></i>
                <span>EDU<span style="color: var(--voyager-primary);">VOYAGER</span></span>
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
                        <a class="nav-link" href="#">Documentation</a>
                    </li>
                </ul>
                
                <div class="d-flex gap-2">
                    <a href="#" class="btn btn-voyager btn-sm">
                        <i class="fas fa-sign-in-alt me-1"></i> Admin Login
                    </a>
                    <a href="#" class="btn btn-voyager-outline btn-sm">
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
                        <a href="#" class="btn btn-voyager">
                            <i class="fas fa-tachometer-alt me-2"></i> Admin Panel
                        </a>
                        <a href="#" class="btn btn-voyager-outline">
                            <i class="fas fa-play-circle me-2"></i> Watch Demo
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 d-none d-lg-block text-center">
                    <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?auto=format&fit=crop&w=600&q=80" alt="School Management" class="img-fluid rounded shadow" style="max-width: 90%; border: 5px solid rgba(255,255,255,0.1);">
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-5" style="background: var(--voyager-bg-darker);">
        <div class="container py-5">
            <div class="row mb-5">
                <div class="col-12 text-center">
                    <h2 class="section-title">Powerful Features</h2>
                    <p class="text-muted">Designed to streamline every aspect of school administration</p>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="card-voyager">
                        <div class="card-voyager-header">
                            <h5><i class="fas fa-user-shield me-2"></i> Admin Panel</h5>
                        </div>
                        <div class="card-voyager-body">
                            <div class="features-icon">
                                <i class="fas fa-cogs"></i>
                            </div>
                            <p>Full-featured admin dashboard powered by Voyager, providing complete control over all aspects of your school management system.</p>
                            <a href="#" class="btn btn-voyager mt-3">
                                <i class="fas fa-external-link-alt me-2"></i> Access Panel
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="card-voyager">
                        <div class="card-voyager-header">
                            <h5><i class="fas fa-user-graduate me-2"></i> Student Portal</h5>
                        </div>
                        <div class="card-voyager-body">
                            <div class="features-icon">
                                <i class="fas fa-book-open"></i>
                            </div>
                            <p>Students can access grades, assignments, attendance records, and communicate with teachers through an intuitive portal.</p>
                            <a href="#" class="btn btn-voyager mt-3">
                                <i class="fas fa-sign-in-alt me-2"></i> Student Login
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="card-voyager">
                        <div class="card-voyager-header">
                            <h5><i class="fas fa-users me-2"></i> Parent Portal</h5>
                        </div>
                        <div class="card-voyager-body">
                            <div class="features-icon">
                                <i class="fas fa-user-friends"></i>
                            </div>
                            <p>Parents can track their children's progress, communicate with teachers, and stay updated with announcements.</p>
                            <a href="#" class="btn btn-voyager mt-3">
                                <i class="fas fa-sign-in-alt me-2"></i> Parent Login
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modules Section -->
    <section id="modules" class="py-5">
        <div class="container py-5">
            <div class="row mb-5">
                <div class="col-12 text-center">
                    <h2 class="section-title">Comprehensive Modules</h2>
                    <p class="text-muted">All the tools you need in one integrated platform</p>
                </div>
            </div>
            
            <div class="module-grid">
                <div class="module-item">
                    <div class="module-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <h4>Academic Management</h4>
                    <p class="text-muted">Course management, class scheduling, assignments and grading system.</p>
                </div>
                
                <div class="module-item">
                    <div class="module-icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <h4>Student Management</h4>
                    <p class="text-muted">Student registration, attendance tracking, performance reports.</p>
                </div>
                
                <div class="module-item">
                    <div class="module-icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <h4>Financial System</h4>
                    <p class="text-muted">Fee collection, payment tracking, expense management, reports.</p>
                </div>
                
                <div class="module-item">
                    <div class="module-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h4>Communication</h4>
                    <p class="text-muted">Announcements, teacher-parent chat, newsletters, event calendar.</p>
                </div>
                
                <div class="module-item">
                    <div class="module-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h4>Analytics & Reports</h4>
                    <p class="text-muted">Performance analytics, custom reports, data visualization.</p>
                </div>
                
                <div class="module-item">
                    <div class="module-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h4>Event Management</h4>
                    <p class="text-muted">School events, parent-teacher meetings, exam schedules.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section id="stats" class="py-5" style="background: var(--voyager-bg-darker);">
        <div class="container py-5">
            <div class="row mb-5">
                <div class="col-12 text-center">
                    <h2 class="section-title">System Statistics</h2>
                    <p class="text-muted">Real-time insights into our platform's performance</p>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-md-3 col-sm-6">
                    <div class="stats-card">
                        <div class="stats-number" style="color: var(--voyager-primary);">20+</div>
                        <div class="stats-label">Admin Features</div>
                        <div class="progress mt-3" style="height: 6px;">
                            <div class="progress-bar" role="progressbar" style="width: 85%; background-color: var(--voyager-primary);"></div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 col-sm-6">
                    <div class="stats-card">
                        <div class="stats-number" style="color: var(--voyager-success);">15+</div>
                        <div class="stats-label">Student Tools</div>
                        <div class="progress mt-3" style="height: 6px;">
                            <div class="progress-bar" role="progressbar" style="width: 75%; background-color: var(--voyager-success);"></div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 col-sm-6">
                    <div class="stats-card">
                        <div class="stats-number" style="color: var(--voyager-accent);">10+</div>
                        <div class="stats-label">Parent Features</div>
                        <div class="progress mt-3" style="height: 6px;">
                            <div class="progress-bar" role="progressbar" style="width: 65%; background-color: var(--voyager-accent);"></div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 col-sm-6">
                    <div class="stats-card">
                        <div class="stats-number" style="color: var(--voyager-warning);">99.9%</div>
                        <div class="stats-label">Uptime</div>
                        <div class="progress mt-3" style="height: 6px;">
                            <div class="progress-bar" role="progressbar" style="width: 100%; background-color: var(--voyager-warning);"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5">
        <div class="container">
            <div class="cta-section">
                <div class="cta-content text-center py-5">
                    <h2 class="cta-title">Ready to Transform Your School?</h2>
                    <p class="mb-4" style="max-width: 700px; margin: 0 auto; opacity: 0.9;">Join thousands of educational institutions using our Voyager-powered platform</p>
                    
                    <div class="d-flex justify-content-center gap-3 flex-wrap">
                        <a href="#" class="btn btn-voyager px-4 py-3">
                            <i class="fas fa-rocket me-2"></i> Get Started Now
                        </a>
                        <a href="#" class="btn btn-voyager-outline px-4 py-3">
                            <i class="fas fa-calendar me-2"></i> Schedule a Demo
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="voyager-footer">
        <div class="container">
            <div class="footer-content">
                <div class="row">
                    <div class="col-lg-4 mb-5 mb-lg-0">
                        <h3 class="d-flex align-items-center text-white">
                            <i class="fas fa-graduation-cap me-2"></i>
                            EDU<span style="color: var(--voyager-primary);">VOYAGER</span>
                        </h3>
                        <p class="mt-3 mb-4" style="opacity: 0.8;">A comprehensive school management solution built with Laravel and Voyager Admin to streamline educational administration.</p>
                        <div class="social-icons">
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#"><i class="fab fa-github"></i></a>
                        </div>
                    </div>
                    
                    <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
                        <h5 class="footer-title">Quick Links</h5>
                        <ul class="list-unstyled footer-links">
                            <li><a href="#"><i class="fas fa-chevron-right me-2"></i> Home</a></li>
                            <li><a href="#features"><i class="fas fa-chevron-right me-2"></i> Features</a></li>
                            <li><a href="#modules"><i class="fas fa-chevron-right me-2"></i> Modules</a></li>
                            <li><a href="#"><i class="fas fa-chevron-right me-2"></i> Pricing</a></li>
                        </ul>
                    </div>
                    
                    <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
                        <h5 class="footer-title">Resources</h5>
                        <ul class="list-unstyled footer-links">
                            <li><a href="#"><i class="fas fa-chevron-right me-2"></i> Documentation</a></li>
                            <li><a href="#"><i class="fas fa-chevron-right me-2"></i> Tutorials</a></li>
                            <li><a href="#"><i class="fas fa-chevron-right me-2"></i> Blog</a></li>
                            <li><a href="#"><i class="fas fa-chevron-right me-2"></i> Support</a></li>
                        </ul>
                    </div>
                    
                    <div class="col-lg-4 col-md-4">
                        <h5 class="footer-title">Contact Us</h5>
                        <ul class="list-unstyled footer-links">
                            <li><i class="fas fa-map-marker-alt me-2"></i> 123 Education St, Learning City</li>
                            <li><i class="fas fa-phone me-2"></i> +1 (555) 123-4567</li>
                            <li><i class="fas fa-envelope me-2"></i> info@eduvoyager.com</li>
                        </ul>
                    </div>
                </div>
                
                <div class="copyright text-center">
                    <p>&copy; 2023 EduVoyager School Management System. All rights reserved.</p>
                    <p class="mt-2">Powered by Laravel v8.83.0 and Voyager Admin v1.5.0</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
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
    </script>
</body>
</html> --}}