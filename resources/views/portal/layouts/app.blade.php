<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Used to add dark mode right away, adding here prevents any flicker -->
    <script>
        if (typeof(Storage) !== "undefined") {
            if(localStorage.getItem('portal_dark_mode') && localStorage.getItem('portal_dark_mode') == 'true'){
                document.documentElement.classList.add('dark');
            }
        }
    </script>

    <title>{{ $title ?? 'Portal Dashboard' }} - School Management System</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    
    <!-- Icons -->
    <link rel="stylesheet" href="{{ voyager_asset('lib/css/responsive.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ voyager_asset('css/voyager.css') }}">
    
    <!-- Custom Portal CSS -->
    <style>
        /* Modern Genesis-inspired Portal Styles */
        :root {
            --primary-50: #eff6ff;
            --primary-100: #dbeafe;
            --primary-500: #3b82f6;
            --primary-600: #2563eb;
            --primary-700: #1d4ed8;
            --primary-900: #1e3a8a;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
        }

        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }
        
        .dark body {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        }

        .portal-container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .portal-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            padding: 1rem 2rem;
            position: sticky;
            top: 0;
            z-index: 50;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        .dark .portal-header {
            background: rgba(30, 41, 59, 0.95);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .portal-main {
            flex: 1;
            display: flex;
            background: transparent;
        }

        .portal-sidebar {
            width: 280px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-right: 1px solid rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            height: calc(100vh - 80px);
            position: sticky;
            top: 80px;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }
        
        .dark .portal-sidebar {
            background: rgba(30, 41, 59, 0.95);
            border-right: 1px solid rgba(255, 255, 255, 0.1);
        }

        .portal-content {
            flex: 1;
            padding: 2rem;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            margin: 1rem;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
        }
        
        .dark .portal-content {
            background: rgba(30, 41, 59, 0.8);
            color: #f1f5f9;
        }

        .portal-nav-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            margin: 0.25rem 1rem;
            border-radius: 12px;
            color: var(--gray-600);
            text-decoration: none;
            transition: all 0.2s ease-in-out;
            font-weight: 500;
        }
        
        .dark .portal-nav-item {
            color: #cbd5e1;
        }

        .portal-nav-item:hover {
            background: var(--primary-50);
            color: var(--primary-700);
            transform: translateX(4px);
            text-decoration: none;
        }
        
        .dark .portal-nav-item:hover {
            background: rgba(59, 130, 246, 0.1);
            color: #60a5fa;
        }

        .portal-nav-item.active {
            background: var(--primary-500);
            color: white;
            font-weight: 600;
        }

        .portal-nav-item i {
            width: 20px;
            margin-right: 0.75rem;
            text-align: center;
        }

        .portal-stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .portal-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .dark .portal-card {
            background: rgba(30, 41, 59, 0.9);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .portal-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 0.5rem;
        }
        
        .dark .page-title {
            color: #f1f5f9;
        }

        .page-subtitle {
            color: var(--gray-600);
            font-size: 1rem;
            margin-bottom: 2rem;
        }
        
        .dark .page-subtitle {
            color: #94a3b8;
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .portal-main {
                flex-direction: column;
            }
            
            .portal-sidebar {
                width: 100%;
                height: auto;
                position: relative;
                top: 0;
            }
            
            .portal-content {
                margin: 0.5rem;
            }
        }

        /* Dark mode toggle */
        .dark-mode-toggle {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            background: var(--primary-500);
            color: white;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 100;
        }

        .dark-mode-toggle:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }
    </style>
    
    @yield('css')
</head>

<body class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-900 dark:to-gray-800">
    <div class="portal-container">
        <!-- Header -->
        <header class="portal-header">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <h1 class="text-xl font-bold text-gray-900 dark:text-white">
                        School Management Portal
                    </h1>
                    <span class="text-sm text-gray-500 dark:text-gray-400">|</span>
                    <span class="text-sm text-gray-600 dark:text-gray-300">{{ $title ?? 'Dashboard' }}</span>
                </div>
                
                <div class="flex items-center space-x-4">
                    <!-- User dropdown -->
                    <div class="relative">
                        <button class="flex items-center space-x-3 text-sm font-medium text-gray-700 dark:text-gray-200 hover:text-gray-900 dark:hover:text-white transition-colors" onclick="toggleDropdown()">
                            <img src="{{ Auth::guard('portal')->user()->avatar ?? asset('images/default-avatar.png') }}" 
                                 class="w-8 h-8 rounded-full border-2 border-white shadow-sm" 
                                 alt="Profile">
                            <span>{{ Auth::guard('portal')->user()->name }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        
                        <div id="userDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50">
                            <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ Auth::guard('portal')->user()->name }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ ucfirst(Auth::guard('portal')->user()->user_type) }}</p>
                            </div>
                            <div class="py-2">
                                <a href="{{ route('portal.profile') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <i class="voyager-person mr-3"></i> My Profile
                                </a>
                                <a href="{{ route('portal.change-password') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <i class="voyager-lock mr-3"></i> Change Password
                                </a>
                                <div class="border-t border-gray-200 dark:border-gray-700 my-2"></div>
                                <form action="{{ route('portal.logout') }}" method="POST" class="block">
                                    @csrf
                                    <button type="submit" class="w-full text-left flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20">
                                        <i class="voyager-power mr-3"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="portal-main">
            <!-- Sidebar Navigation -->
            <aside class="portal-sidebar">
                <nav class="p-4">
                    @include('portal.layouts.navigation')
                </nav>
            </aside>

            <!-- Page Content -->
            <div class="portal-content">
                @if(isset($title))
                <div class="mb-6">
                    <h1 class="page-title">
                        @if(isset($icon))
                            <i class="{{ $icon }} mr-3"></i>
                        @endif
                        {{ $title }}
                    </h1>
                    @if(isset($subtitle))
                        <p class="page-subtitle">{{ $subtitle }}</p>
                    @endif
                </div>
                @endif

                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-100 border border-green-200 rounded-lg text-green-800 dark:bg-green-900/20 dark:border-green-800 dark:text-green-200">
                        <div class="flex items-center">
                            <i class="voyager-check-circle mr-3"></i>
                            {{ session('success') }}
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-100 border border-red-200 rounded-lg text-red-800 dark:bg-red-900/20 dark:border-red-800 dark:text-red-200">
                        <div class="flex items-center">
                            <i class="voyager-exclamation mr-3"></i>
                            {{ session('error') }}
                        </div>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-100 border border-red-200 rounded-lg text-red-800 dark:bg-red-900/20 dark:border-red-800 dark:text-red-200">
                        <div class="flex items-center mb-2">
                            <i class="voyager-exclamation mr-3"></i>
                            <strong>There were some errors:</strong>
                        </div>
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <!-- Dark mode toggle -->
    <button class="dark-mode-toggle" onclick="toggleDarkMode()" title="Toggle Dark Mode">
        <i class="voyager-sun-o dark:hidden"></i>
        <i class="voyager-moon hidden dark:block"></i>
    </button>

    <!-- Scripts -->
    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById('userDropdown');
            dropdown.classList.toggle('hidden');
        }

        function toggleDarkMode() {
            const html = document.documentElement;
            html.classList.toggle('dark');
            localStorage.setItem('portal_dark_mode', html.classList.contains('dark'));
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('userDropdown');
            const button = event.target.closest('button');
            
            if (!button || !button.onclick) {
                dropdown.classList.add('hidden');
            }
        });

        // Auto close alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.bg-green-100, .bg-red-100');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s ease';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    </script>

    @yield('javascript')
</body>
</html>
        
        .sidebar-menu .nav-link:hover,
        .sidebar-menu .nav-link.active {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateX(5px);
        }
        
        .sidebar-menu .nav-link i {
            margin-right: 0.75rem;
            width: 20px;
            text-align: center;
        }
        
        .assignment-item {
            padding: 1rem;
            border-radius: 8px;
            background: #f8f9fa;
            margin-bottom: 0.75rem;
            transition: all 0.3s ease;
        }
        
        .assignment-item:hover {
            background: #e9ecef;
            transform: translateX(5px);
        }
        
        .event-item {
            padding: 1rem;
            border-left: 4px solid #007bff;
            background: rgba(0, 123, 255, 0.05);
            border-radius: 0 8px 8px 0;
            margin-bottom: 1rem;
        }
        
        .announcement-item {
            padding: 1rem;
            border-left: 4px solid #28a745;
            background: rgba(40, 167, 69, 0.05);
            border-radius: 0 8px 8px 0;
            margin-bottom: 1rem;
        }
        
        /* Portal Components Styles */
        .portal-list-item {
            padding: 1rem;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }
        
        .portal-list-item:hover {
            background-color: rgba(0,123,255,0.05);
            transform: translateX(3px);
        }
        
        .portal-list-item:last-child {
            border-bottom: none;
        }
        
        .portal-list.with-dividers .portal-list-item {
            border-bottom: 1px solid #dee2e6;
        }
        
        .portal-table-panel .table {
            margin-bottom: 0;
        }
        
        .portal-table th {
            border-top: none;
            font-weight: 600;
            background-color: #f8f9fa;
        }
        
        .portal-empty-state {
            color: #6c757d;
        }
        
        .portal-alert {
            border: none;
            border-radius: 8px;
            border-left: 4px solid;
        }
        
        .portal-alert.alert-info {
            border-left-color: #17a2b8;
            background-color: rgba(23, 162, 184, 0.1);
        }
        
        .portal-alert.alert-success {
            border-left-color: #28a745;
            background-color: rgba(40, 167, 69, 0.1);
        }
        
        .portal-alert.alert-warning {
            border-left-color: #ffc107;
            background-color: rgba(255, 193, 7, 0.1);
        }
        
        .portal-alert.alert-danger {
            border-left-color: #dc3545;
            background-color: rgba(220, 53, 69, 0.1);
        }
        
        .portal-alert-icon {
            font-size: 1.25rem;
        }
        
        .student-card {
            transition: all 0.3s ease;
        }
        
        .student-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        /* Responsive improvements */
        @media (max-width: 768px) {
            .portal-stats-number {
                font-size: 2rem;
            }
            
            .portal-card-body {
                padding: 1rem;
            }
            
            .portal-list-item {
                padding: 0.75rem;
            }
        }
    </style>
    <style>
        /* Custom CSS specific to the portal with Genesis styling */
        body {
            font-family: var(--voyager-font-family);
            background-color: var(--voyager-bg);
            color: var(--voyager-text);
            overflow-x: hidden;
        }
        
        .voyager-sidebar {
            background: var(--voyager-sidebar);
            min-height: 100vh;
            color: var(--voyager-text-light);
            box-shadow: var(--voyager-card-shadow);
            position: fixed;
            top: 0;
        
        /* Additional Voyager UI Styles */
        .analytics-sparkle,
        .analytics-sparkle-2 {
            position: absolute;
            background: linear-gradient(45deg, rgba(255,255,255,0.1), transparent);
            border-radius: 50%;
            pointer-events: none;
            animation: sparkle 6s linear infinite;
        }
        
        .analytics-sparkle {
            width: 100px;
            height: 100px;
            top: 10%;
            right: 15%;
            animation-delay: 0s;
        }
        
        .analytics-sparkle-2 {
            width: 60px;
            height: 60px;
            top: 60%;
            right: 30%;
            animation-delay: 3s;
        }
        
        @keyframes sparkle {
            0%, 100% { 
                opacity: 0;
                transform: scale(0) rotate(0deg);
            }
            50% { 
                opacity: 1;
                transform: scale(1) rotate(180deg);
            }
        }
        
        .panel.widget.center.bgimage {
            border-radius: 8px;
            border: none;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .panel.widget.center.bgimage:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        .panel.widget.center.bgimage .dimmer {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, rgba(0,0,0,0.3), rgba(0,0,0,0.6));
            z-index: 1;
        }
        
        .panel.widget.center.bgimage .panel-content {
            position: relative;
            z-index: 2;
            padding: 20px;
            text-align: center;
        }
        
        .avatar.border-grey {
            border: 3px solid rgba(255,255,255,0.8);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .label {
            display: inline-block;
            padding: 0.25em 0.6em;
            font-size: 75%;
            font-weight: 700;
            line-height: 1;
            color: #fff;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.25rem;
        }
        
        .label-primary { background-color: #007bff; }
        .label-success { background-color: #28a745; }
        .label-info { background-color: #17a2b8; }
        .label-warning { background-color: #ffc107; color: #212529; }
        .label-danger { background-color: #dc3545; }
        .label-default { background-color: #6c757d; }
        
        .hr-divider {
            border-bottom: 1px solid rgba(255,255,255,0.2);
            margin: 10px 0;
            width: 100%;
        }
        
        .media {
            display: flex;
            align-items: flex-start;
        }
        
        .media-left {
            padding-right: 10px;
        }
        
        .media-body {
            flex: 1;
        }
        
        .media-right {
            padding-left: 10px;
        }
        
        .page-content {
            margin-left: 260px;
            padding: 30px;
            min-height: 100vh;
            position: relative;
        }
        
        @media (max-width: 768px) {
            .voyager-sidebar {
                transform: translateX(-100%);
            }
            
            .voyager-sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .page-content {
                margin-left: 0;
                padding: 15px;
            }
        }

        .user-dropdown {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 5px;
            margin: 15px;
            padding: 10px;
        }

        .user-dropdown .dropdown-toggle {
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        .user-dropdown .dropdown-toggle:after {
            margin-left: auto;
        }
    </style>
</head>
<body>
    <div id="app">
        <!-- Sidebar -->
        <nav class="voyager-sidebar genesis-sidebar">
            <div class="genesis-sidebar-header">
                <div class="genesis-logo">
                    <i class="fas fa-graduation-cap"></i>
                    <h4>{{ config('app.name', 'Portal') }}</h4>
                </div>
            </div>
            
            @include('portal.layouts.nav')
            
            <!-- User Info -->
            <div class="genesis-user-dropdown">
                <a href="#" class="genesis-user-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="genesis-avatar genesis-avatar-sm">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="genesis-user-info">
                        <span class="genesis-user-name">{{ Auth::guard('portal')->user()->name ?? 'User' }}</span>
                        <small>Student</small>
                    </div>
                    <i class="fas fa-chevron-down genesis-dropdown-icon"></i>
                </a>
                <ul class="dropdown-menu genesis-dropdown-menu">
                    <li><a class="genesis-dropdown-item" href="{{ route('portal.profile') }}"><i class="fas fa-user me-2"></i>Profile</a></li>
                    <li><a class="genesis-dropdown-item" href="{{ route('portal.change-password') }}"><i class="fas fa-key me-2"></i>Change Password</a></li>
                    <li><hr class="genesis-dropdown-divider"></li>
                    <li>
                        <form action="{{ route('portal.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="genesis-dropdown-item genesis-dropdown-danger"><i class="fas fa-sign-out-alt me-2"></i>Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="genesis-main-content">
            <!-- Header -->
            <div class="genesis-header">
                <div class="genesis-header-container">
                    <div class="genesis-header-content">
                        <h1 class="genesis-page-title">{{ $title ?? 'Portal Dashboard' }}</h1>
                        @if(isset($breadcrumbs))
                            <nav aria-label="breadcrumb" class="genesis-breadcrumbs">
                                <ol class="breadcrumb">
                                    @foreach($breadcrumbs as $breadcrumb)
                                        @if($loop->last)
                                            <li class="breadcrumb-item active">{{ $breadcrumb['title'] }}</li>
                                        @else
                                            <li class="breadcrumb-item">
                                                <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['title'] }}</a>
                                            </li>
                                        @endif
                                    @endforeach
                                </ol>
                            </nav>
                        @endif
                    </div>
                    
                    <div class="genesis-header-actions">
                        <button class="genesis-menu-toggle d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
                            <i class="fas fa-bars"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Content -->
            <div class="genesis-content">
                <div class="genesis-notifications">
                    @if(session('success'))
                        <div class="genesis-alert genesis-alert-success">
                            <div class="genesis-alert-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="genesis-alert-content">
                                <p>{{ session('success') }}</p>
                            </div>
                            <button type="button" class="genesis-alert-close" data-dismiss="alert">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="genesis-alert genesis-alert-danger">
                            <div class="genesis-alert-icon">
                                <i class="fas fa-exclamation-circle"></i>
                            </div>
                            <div class="genesis-alert-content">
                                <p>{{ session('error') }}</p>
                            </div>
                            <button type="button" class="genesis-alert-close" data-dismiss="alert">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endif
                </div>
                
                <div class="genesis-page-content">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>
@endsection

@section('javascript')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    
    <script>
        // Genesis-inspired JavaScript functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Alert dismissal
            const alertCloseButtons = document.querySelectorAll('.genesis-alert-close');
            alertCloseButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const alert = this.closest('.genesis-alert');
                    alert.classList.add('genesis-alert-dismissing');
                    setTimeout(() => {
                        alert.remove();
                    }, 300);
                });
            });
            
            // Add animation classes to Genesis cards
            const cards = document.querySelectorAll('.genesis-card');
            cards.forEach((card, index) => {
                card.classList.add('genesis-fadeIn');
                card.style.animationDelay = (index * 0.1) + 's';
            });
            
            // Menu toggle for mobile
            const menuToggle = document.querySelector('.genesis-menu-toggle');
            if (menuToggle) {
                menuToggle.addEventListener('click', function() {
                    document.querySelector('.genesis-sidebar').classList.toggle('show-mobile');
                    document.body.classList.toggle('sidebar-open');
                });
                
                // Close sidebar when clicking outside
                document.addEventListener('click', function(e) {
                    const sidebar = document.querySelector('.genesis-sidebar');
                    const toggle = document.querySelector('.genesis-menu-toggle');
                    
                    if (sidebar.classList.contains('show-mobile') && 
                        !sidebar.contains(e.target) && 
                        !toggle.contains(e.target)) {
                        sidebar.classList.remove('show-mobile');
                        document.body.classList.remove('sidebar-open');
                    }
                });
            }
        });
    </script>
    
    @stack('scripts')
@endsection
