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
    <link rel="stylesheet" href="{{ asset('voyager-assets/css/app.css') }}">
    
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

        /* Utility classes for flexbox */
        .flex { display: flex; }
        .items-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .space-x-3 > * + * { margin-left: 0.75rem; }
        .space-x-4 > * + * { margin-left: 1rem; }

        /* Text utilities */
        .text-xl { font-size: 1.25rem; }
        .text-sm { font-size: 0.875rem; }
        .font-bold { font-weight: 700; }
        .font-medium { font-weight: 500; }

        /* Colors */
        .text-gray-900 { color: var(--gray-900); }
        .text-gray-700 { color: var(--gray-700); }
        .text-gray-600 { color: var(--gray-600); }
        .text-gray-500 { color: var(--gray-500); }
        .text-red-600 { color: #dc2626; }
        
        .dark .text-white { color: white; }
        .dark .text-gray-200 { color: #e5e7eb; }
        .dark .text-gray-300 { color: #d1d5db; }
        .dark .text-gray-400 { color: #9ca3af; }

        /* Background colors */
        .bg-white { background-color: white; }
        .bg-gray-100 { background-color: var(--gray-100); }
        .bg-red-50 { background-color: #fef2f2; }
        .bg-green-100 { background-color: #dcfce7; }
        .bg-red-100 { background-color: #fee2e2; }
        
        .dark .bg-gray-800 { background-color: var(--gray-800); }
        .dark .bg-gray-700 { background-color: var(--gray-700); }
        .dark .bg-red-900\/20 { background-color: rgba(127, 29, 29, 0.2); }
        .dark .bg-green-900\/20 { background-color: rgba(20, 83, 45, 0.2); }

        /* Borders */
        .border { border-width: 1px; }
        .border-gray-200 { border-color: var(--gray-200); }
        .border-gray-700 { border-color: var(--gray-700); }
        .border-red-200 { border-color: #fecaca; }
        .border-green-200 { border-color: #bbf7d0; }
        .border-t { border-top-width: 1px; }
        .border-b { border-bottom-width: 1px; }
        
        .dark .border-gray-700 { border-color: var(--gray-700); }
        .dark .border-red-800 { border-color: #991b1b; }
        .dark .border-green-800 { border-color: #166534; }

        /* Rounded corners */
        .rounded-full { border-radius: 9999px; }
        .rounded-lg { border-radius: 0.5rem; }

        /* Sizing */
        .w-8 { width: 2rem; }
        .h-8 { height: 2rem; }
        .w-4 { width: 1rem; }
        .h-4 { height: 1rem; }
        .w-48 { width: 12rem; }
        .w-full { width: 100%; }

        /* Padding */
        .p-4 { padding: 1rem; }
        .px-4 { padding-left: 1rem; padding-right: 1rem; }
        .py-2 { padding-top: 0.5rem; padding-bottom: 0.5rem; }
        .py-3 { padding-top: 0.75rem; padding-bottom: 0.75rem; }
        .mb-2 { margin-bottom: 0.5rem; }
        .mb-6 { margin-bottom: 1.5rem; }
        .mr-3 { margin-right: 0.75rem; }
        .mt-2 { margin-top: 0.5rem; }
        .my-2 { margin-top: 0.5rem; margin-bottom: 0.5rem; }

        /* Positioning */
        .relative { position: relative; }
        .absolute { position: absolute; }
        .right-0 { right: 0; }
        .z-50 { z-index: 50; }

        /* Display */
        .hidden { display: none; }
        .block { display: block; }

        /* Shadows */
        .shadow-sm { box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); }
        .shadow-lg { box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); }

        /* Transitions */
        .transition-colors { transition-property: color, background-color, border-color, text-decoration-color, fill, stroke; transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1); transition-duration: 150ms; }

        /* Hover states */
        .hover\:bg-gray-100:hover { background-color: var(--gray-100); }
        .hover\:bg-red-50:hover { background-color: #fef2f2; }
        .hover\:text-gray-900:hover { color: var(--gray-900); }
        
        .dark .hover\:bg-gray-700:hover { background-color: var(--gray-700); }
        .dark .hover\:text-white:hover { color: white; }
        .dark .hover\:bg-red-900\/20:hover { background-color: rgba(127, 29, 29, 0.2); }

        /* List styles */
        .list-disc { list-style-type: disc; }
        .list-inside { list-style-position: inside; }

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
                            <img src="{{ Auth::guard('portal')->user()->avatar ?? asset('voyager-assets/images/captain-avatar.png') }}" 
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
