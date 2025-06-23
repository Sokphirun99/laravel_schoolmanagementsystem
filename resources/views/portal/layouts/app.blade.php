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
