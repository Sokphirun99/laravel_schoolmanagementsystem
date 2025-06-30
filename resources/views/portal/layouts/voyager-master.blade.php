<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" dir="{{ __('voyager::generic.is_rtl') == 'true' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="robots" content="none" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="School Portal">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'School Portal') - {{ config('app.name') }}</title>

    <!-- Google Fonts - Inter from Genesis theme -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Used to add dark mode right away (Genesis-inspired) -->
    <script>
        if (typeof(Storage) !== "undefined") {
            if(localStorage.getItem('dark_mode') && localStorage.getItem('dark_mode') == 'true'){
                document.documentElement.classList.add('dark');
            }
        }
    </script>
    
    <!-- Voyager CSS -->
    <link rel="stylesheet" href="{{ asset('voyager-assets/css/app.css') }}">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Portal specific CSS (extends/overrides Voyager CSS with Genesis styling) -->
    <link rel="stylesheet" href="{{ asset('css/voyager-ui/portal.css') }}">
    
    <style>
        body {
            background-image: url('{{ Voyager::image(Voyager::setting("admin.bg_image", "")) }}');
            background-color: var(--voyager-bg);
            font-family: var(--voyager-font-family);
        }
        
        body.portal .login-sidebar {
            border-top: 5px solid var(--voyager-primary);
            box-shadow: var(--voyager-card-shadow);
            border-radius: var(--voyager-radius);
            overflow: hidden;
        }
        
        @media (max-width: 767px) {
            body.portal .login-sidebar {
                border-top: 0px !important;
                border-left: 5px solid var(--voyager-primary);
            }
        }
        
        body.portal .navbar.navbar-default {
            background: var(--voyager-sidebar);
            border-color: transparent;
            box-shadow: var(--voyager-card-shadow);
            height: var(--voyager-header-height);
            display: flex;
            align-items: center;
        }
        
        .side-menu.sidebar-inverse {
            background: var(--voyager-sidebar);
            color: var(--voyager-text-light);
            border-right: 1px solid rgba(255,255,255,0.1);
        }
        
        .nav-tabs>li.active>a, .nav-tabs>li.active>a:focus, .nav-tabs>li.active>a:hover {
            color: var(--voyager-primary) !important;
            border-bottom: 2px solid var(--voyager-primary);
        }
        
        .panel-body .table.table-bordered {
            border-top: 1px solid var(--voyager-primary);
        }
        
        /* Genesis-inspired Dark Mode Styles */
        .dark body {
            background-color: #111827;
            color: rgba(255,255,255,0.9);
        }
        
        .dark .side-menu.sidebar-inverse {
            background: #0f172a;
            border-right: 1px solid rgba(255,255,255,0.05);
        }
        
        .dark .navbar.navbar-default {
            background: #0f172a;
            border-color: rgba(255,255,255,0.05);
        }
        
        .dark .panel-default {
            background-color: #1e293b;
            border-color: rgba(255,255,255,0.1);
        }
        
        .dark .panel-default > .panel-heading {
            background-color: #0f172a;
            border-color: rgba(255,255,255,0.1);
            color: rgba(255,255,255,0.9);
        }
    </style>
    
    @yield('css')
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.ico') }}">
</head>

<body class="voyager @yield('body_class')">
    <div id="voyager-loader">
        <img src="{{ asset('voyager-assets/images/logo-icon.png') }}" alt="Voyager Loader">
    </div>
    
    <div class="app-container">
        <div class="fadetoblack visible-xs"></div>
        <div class="row content-container">
            @yield('sidebar')

            <div class="container-fluid">
                <div class="side-body padding-top">
                    <!-- Genesis-inspired Light/Dark Mode Toggle -->
                    <div class="genesis-theme-toggle">
                        <button 
                            id="theme-toggle"
                            class="theme-toggle-btn"
                            aria-label="Toggle Dark Mode"
                        >
                            <i class="fas fa-sun theme-toggle-light"></i>
                            <i class="fas fa-moon theme-toggle-dark"></i>
                        </button>
                    </div>
                    
                    @yield('page_header')
                    <div id="voyager-notifications"></div>
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    
    @yield('javascript')
    
    <!-- Genesis Theme Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggle = document.getElementById('theme-toggle');
            
            // Check initial state
            const darkMode = localStorage.getItem('dark_mode') === 'true';
            if (darkMode) {
                document.documentElement.classList.add('dark');
                themeToggle.classList.add('dark-active');
            } else {
                document.documentElement.classList.remove('dark');
                themeToggle.classList.remove('dark-active');
            }
            
            // Toggle theme
            themeToggle.addEventListener('click', function() {
                const isDark = document.documentElement.classList.toggle('dark');
                localStorage.setItem('dark_mode', isDark ? 'true' : 'false');
                themeToggle.classList.toggle('dark-active', isDark);
            });
        });
    <script>
        $('document').ready(function () {
            $('#voyager-loader').fadeOut();
        });
    </script>
</body>
</html>
