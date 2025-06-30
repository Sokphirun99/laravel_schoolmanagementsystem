@extends('portal.layouts.voyager-master')

@section('css')
    <link href="{{ asset('css/voyager-ui/portal.css') }}" rel="stylesheet">
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
