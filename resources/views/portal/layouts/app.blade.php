<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'School Management System') }} - Portal</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.ico') }}">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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

        .voyager-sidebar {
            background: linear-gradient(135deg, var(--voyager-sidebar), var(--voyager-dark));
            min-height: 100vh;
            color: var(--voyager-text-light);
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            width: 250px;
            transition: all 0.3s ease;
        }

        .voyager-sidebar .sidebar-header {
            padding: 20px 15px;
            background: rgba(0, 0, 0, 0.1);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .voyager-sidebar .sidebar-header h4 {
            color: var(--voyager-primary);
            font-weight: 700;
            margin: 0;
        }

        .voyager-sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 12px 20px;
            border-radius: 0;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .voyager-sidebar .nav-link:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.1);
            border-left: 3px solid var(--voyager-primary);
        }

        .voyager-sidebar .nav-link.active {
            background: rgba(34, 167, 240, 0.2);
            color: #fff;
            border-left: 3px solid var(--voyager-primary);
        }

        .voyager-sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .main-content {
            margin-left: 250px;
            min-height: 100vh;
            background: var(--voyager-bg);
            transition: all 0.3s ease;
        }

        .voyager-header {
            background: #fff;
            padding: 15px 30px;
            border-bottom: 1px solid var(--voyager-border);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .voyager-breadcrumbs {
            background: transparent;
            padding: 0;
            margin: 0;
        }

        .voyager-breadcrumbs .breadcrumb-item {
            color: var(--voyager-text);
        }

        .voyager-breadcrumbs .breadcrumb-item.active {
            color: var(--voyager-primary);
        }

        .voyager-content {
            padding: 30px;
        }

        .voyager-card {
            background: #fff;
            border: 1px solid var(--voyager-border);
            border-radius: 6px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .voyager-card-header {
            background: var(--voyager-primary);
            color: #fff;
            padding: 15px 20px;
            border-bottom: 1px solid var(--voyager-border);
            font-weight: 600;
        }

        .voyager-card-body {
            padding: 20px;
        }

        .btn-voyager {
            background-color: var(--voyager-primary);
            border-color: var(--voyager-primary);
            color: #fff;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-voyager:hover {
            background-color: #1e96d9;
            border-color: #1e96d9;
            color: #fff;
            transform: translateY(-1px);
        }

        .table-voyager {
            margin-bottom: 0;
        }

        .table-voyager th {
            background: var(--voyager-bg-darker);
            color: var(--voyager-text);
            font-weight: 600;
            border-bottom: 1px solid var(--voyager-border);
        }

        .voyager-stats-card {
            background: linear-gradient(135deg, var(--voyager-primary), var(--voyager-accent));
            color: #fff;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(34, 167, 240, 0.3);
        }

        .voyager-stats-number {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .voyager-stats-label {
            font-size: 0.9rem;
            opacity: 0.9;
            text-transform: uppercase;
            letter-spacing: 1px;
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
        <nav class="voyager-sidebar">
            <div class="sidebar-header">
                <h4><i class="fas fa-graduation-cap me-2"></i>{{ config('app.name', 'Portal') }}</h4>
            </div>
            
            @include('portal.layouts.nav')
            
            <!-- User Info -->
            <div class="user-dropdown">
                <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user-circle me-2"></i>
                    {{ Auth::guard('portal')->user()->name ?? 'User' }}
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('portal.profile') }}"><i class="fas fa-user me-2"></i>Profile</a></li>
                    <li><a class="dropdown-item" href="{{ route('portal.change-password') }}"><i class="fas fa-key me-2"></i>Change Password</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('portal.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt me-2"></i>Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <div class="voyager-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h4 mb-0">{{ $title ?? 'Portal Dashboard' }}</h1>
                        @if(isset($breadcrumbs))
                            <nav aria-label="breadcrumb" class="voyager-breadcrumbs">
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
                    
                    <button class="btn btn-outline-primary d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
            
            <!-- Content -->
            <div class="voyager-content">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    
    @stack('scripts')
</body>
</html>
