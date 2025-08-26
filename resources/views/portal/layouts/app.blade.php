@php
    $portalUser = Auth::guard('portal')->user();
    $now = now();
    // Lightweight announcement feed for header bell
    $recentAnnouncements = \App\Models\Announcement::query()
        ->where('status', true)
        ->where(function ($q) use ($portalUser) {
            $q->where('audience', 'all');
            if ($portalUser && $portalUser->user_type) {
                $q->orWhere('audience', $portalUser->user_type);
            }
        })
        ->where(function ($q) use ($now) {
            $q->whereNull('expiry_date')->orWhere('expiry_date', '>=', $now);
        })
        ->latest()
        ->limit(5)
        ->get();
    $announcementCount = \App\Models\Announcement::query()
        ->where('status', true)
        ->where(function ($q) use ($portalUser) {
            $q->where('audience', 'all');
            if ($portalUser && $portalUser->user_type) {
                $q->orWhere('audience', $portalUser->user_type);
            }
        })
        ->where(function ($q) use ($now) {
            $q->whereNull('expiry_date')->orWhere('expiry_date', '>=', $now);
        })
        ->count();
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="antialiased">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        // Prevent dark-mode flicker
        if (typeof Storage !== 'undefined') {
            if (localStorage.getItem('portal_dark_mode') === 'true') {
                document.documentElement.classList.add('dark');
            }
        }
    </script>

    <title>{{ $title ?? 'Portal Dashboard' }} - School Management System</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    <link rel="stylesheet" href="{{ voyager_asset('css/voyager.css') }}">

    <style>
        :root {
            --primary-50:#eff6ff;--primary-500:#3b82f6;--primary-700:#1d4ed8;
            --gray-50:#f9fafb;--gray-100:#f3f4f6;--gray-200:#e5e7eb;--gray-600:#4b5563;--gray-700:#374151;--gray-800:#1f2937;--gray-900:#111827;
        }
        * { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }
        body { background: linear-gradient(135deg,#667eea 0%,#764ba2 100%); min-height: 100vh; margin:0 }
        .dark body { background: linear-gradient(135deg,#1e293b 0%,#334155 100%); }
        .portal-container { min-height: 100vh; display:flex; flex-direction:column }
        .portal-header { background: rgba(255,255,255,.95); backdrop-filter: blur(10px); border-bottom:1px solid rgba(0,0,0,.1); padding:1rem 2rem; position:sticky; top:0; z-index:50; box-shadow:0 1px 3px rgba(0,0,0,.1) }
        .dark .portal-header { background: rgba(30,41,59,.95); border-bottom:1px solid rgba(255,255,255,.1) }
        .portal-main { flex:1; display:flex }
        .portal-sidebar { width:280px; background: rgba(255,255,255,.95); backdrop-filter: blur(10px); border-right:1px solid rgba(0,0,0,.1); overflow-y:auto; height:calc(100vh - 80px); position:sticky; top:80px; box-shadow:2px 0 10px rgba(0,0,0,.1) }
        .dark .portal-sidebar { background: rgba(30,41,59,.95); border-right:1px solid rgba(255,255,255,.1) }
        .portal-content { flex:1; padding:2rem; background: rgba(255,255,255,.8); backdrop-filter: blur(10px); margin:1rem; border-radius:16px; box-shadow:0 4px 20px rgba(0,0,0,.1); overflow-y:auto }
        .dark .portal-content { background: rgba(30,41,59,.8); color:#f1f5f9 }
        .portal-nav-item { display:flex; align-items:center; padding:.75rem 1.5rem; margin:.25rem 1rem; border-radius:12px; color:var(--gray-600); text-decoration:none; transition:.2s; font-weight:500 }
        .portal-nav-item i { width:20px; margin-right:.75rem; text-align:center }
        .portal-nav-item:hover { background:var(--primary-50); color:var(--primary-700); transform: translateX(4px) }
        .dark .portal-nav-item{ color:#cbd5e1 }
        .dark .portal-nav-item:hover{ background:rgba(59,130,246,.1); color:#60a5fa }
        .portal-nav-item.active { background:#3b82f6; color:#fff; font-weight:600 }
        .page-title { font-size:2rem; font-weight:700; color:var(--gray-900); margin-bottom:.5rem }
        .dark .page-title{ color:#f1f5f9 }
        .page-subtitle{ color:var(--gray-600); font-size:1rem; margin-bottom:2rem }
        .dark .page-subtitle{ color:#94a3b8 }
        @media (max-width: 768px){ .portal-main{flex-direction:column} .portal-sidebar{width:100%; height:auto; position:relative; top:0} .portal-content{margin:.5rem} }
        .dark-mode-toggle{ position:fixed; bottom:2rem; right:2rem; background:#3b82f6; color:#fff; border:none; border-radius:50%; width:50px; height:50px; display:flex; align-items:center; justify-content:center; box-shadow:0 4px 15px rgba(0,0,0,.2); cursor:pointer; transition:.3s; z-index:100 }
        .dark-mode-toggle:hover{ transform:scale(1.1) }
        .icon-button{ position:relative; display:inline-flex; align-items:center; justify-content:center; width:40px; height:40px; border-radius:9999px; border:1px solid rgba(0,0,0,.08); background:#fff; color:#334155 }
        .dark .icon-button{ background:#0f172a; color:#e2e8f0; border-color:rgba(255,255,255,.1) }
        .badge{ position:absolute; top:-4px; right:-4px; background:#ef4444; color:#fff; border-radius:9999px; font-size:10px; padding:2px 6px; font-weight:700 }
        .dropdown{ position:relative }
        .dropdown-menu{ position:absolute; right:0; top:calc(100% + .5rem); min-width:260px; background:#fff; border:1px solid rgba(0,0,0,.08); border-radius:12px; box-shadow:0 10px 30px rgba(0,0,0,.08); overflow:hidden; z-index:60 }
        .dark .dropdown-menu{ background:#0f172a; border-color:rgba(255,255,255,.1) }
        .dropdown-item{ display:flex; padding:.75rem 1rem; gap:.75rem; text-decoration:none; color:#334155; align-items:flex-start }
        .dropdown-item:hover{ background:#f1f5f9 }
        .dark .dropdown-item{ color:#e2e8f0 }
        .dark .dropdown-item:hover{ background:#111827 }
        .muted{ color:#6b7280 }
    </style>
    @yield('css')
    
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-900 dark:to-gray-800">
    <div class="portal-container">
        <header class="portal-header">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <h1 class="text-xl font-bold text-gray-900 dark:text-white">School Management Portal</h1>
                    <span class="text-sm text-gray-500 dark:text-gray-400">|</span>
                    <span class="text-sm text-gray-600 dark:text-gray-300">{{ $title ?? 'Dashboard' }}</span>
                </div>
                <div class="flex items-center space-x-3">
                    <!-- Notifications -->
                    <div class="dropdown">
                        <button class="icon-button" onclick="toggleMenu('notifMenu')" aria-label="Notifications">
                            <i class="voyager-bell"></i>
                            @if($announcementCount > 0)
                                <span class="badge">{{ $announcementCount }}</span>
                            @endif
                        </button>
                        <div id="notifMenu" class="dropdown-menu hidden">
                            <div class="border-b border-gray-200 dark:border-gray-700 px-4 py-3">
                                <strong>Announcements</strong>
                            </div>
                            @forelse($recentAnnouncements as $ann)
                                <a class="dropdown-item" href="{{ route('portal.announcements.index') }}">
                                    <i class="voyager-megaphone" style="margin-top: .15rem"></i>
                                    <div>
                                        <div>{{ \Illuminate\Support\Str::limit($ann->title, 60) }}</div>
                                        <div class="muted" style="font-size: 12px;">{{ $ann->created_at?->diffForHumans() }}</div>
                                    </div>
                                </a>
                            @empty
                                <div class="p-4 muted">No recent announcements</div>
                            @endforelse
                            <div class="border-t border-gray-200 dark:border-gray-700 p-2 text-center">
                                <a href="{{ route('portal.announcements.index') }}" class="text-sm">View all</a>
                            </div>
                        </div>
                    </div>

                    <!-- User dropdown -->
                    <div class="dropdown">
                        <button class="flex items-center space-x-3 text-sm font-medium text-gray-700 dark:text-gray-200 hover:text-gray-900 dark:hover:text-white transition-colors" onclick="toggleMenu('userMenu')">
                            <img src="{{ $portalUser->avatar ?? asset('images/default-avatar.png') }}" class="w-8 h-8 rounded-full border-2 border-white shadow-sm" alt="Profile">
                            <span>{{ $portalUser->name ?? 'User' }}</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div id="userMenu" class="dropdown-menu hidden">
                            <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $portalUser->name ?? '' }}</p>
                                <p class="text-sm muted">{{ ucfirst($portalUser->user_type ?? '') }}</p>
                            </div>
                            <a href="{{ route('portal.profile') }}" class="dropdown-item"><i class="voyager-person"></i> <span>My Profile</span></a>
                            <a href="{{ route('portal.change-password') }}" class="dropdown-item"><i class="voyager-lock"></i> <span>Change Password</span></a>
                            <div class="border-t border-gray-200 dark:border-gray-700 my-2"></div>
                            <form action="{{ route('portal.logout') }}" method="POST" class="block px-2 pb-2">
                                @csrf
                                <button type="submit" class="w-full text-left dropdown-item" style="color:#dc2626"><i class="voyager-power"></i> <span>Logout</span></button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </header>

        <main class="portal-main">
            <aside class="portal-sidebar">
                <nav class="p-4">
                    @include('portal.layouts.navigation')
                </nav>
            </aside>
            <div class="portal-content">
                @if(isset($title))
                    <div class="mb-6">
                        <h1 class="page-title">@isset($icon)<i class="{{ $icon }} mr-3"></i>@endisset {{ $title }}</h1>
                        @isset($subtitle)<p class="page-subtitle">{{ $subtitle }}</p>@endisset
                    </div>
                @endif

                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-100 border border-green-200 rounded-lg text-green-800 dark:bg-green-900/20 dark:border-green-800 dark:text-green-200">
                        <div class="flex items-center"><i class="voyager-check-circle mr-3"></i>{{ session('success') }}</div>
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-100 border border-red-200 rounded-lg text-red-800 dark:bg-red-900/20 dark:border-red-800 dark:text-red-200">
                        <div class="flex items-center"><i class="voyager-exclamation mr-3"></i>{{ session('error') }}</div>
                    </div>
                @endif
                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-100 border border-red-200 rounded-lg text-red-800 dark:bg-red-900/20 dark:border-red-800 dark:text-red-200">
                        <div class="flex items-center mb-2"><i class="voyager-exclamation mr-3"></i><strong>There were some errors:</strong></div>
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

    <button class="dark-mode-toggle" onclick="toggleDarkMode()" title="Toggle Dark Mode">
        <i class="voyager-sun-o dark:hidden"></i>
        <i class="voyager-moon hidden dark:block"></i>
    </button>

    <script>
        function toggleDarkMode(){ const html=document.documentElement; html.classList.toggle('dark'); localStorage.setItem('portal_dark_mode', html.classList.contains('dark')); }
        function toggleMenu(id){ const m=document.getElementById(id); if(!m) return; m.classList.toggle('hidden'); }
        document.addEventListener('click', function(e){
            const menus=['notifMenu','userMenu'];
            menus.forEach(id=>{
                const menu=document.getElementById(id);
                const button=e.target.closest('button');
                if(menu && !menu.contains(e.target) && (!button || (button && button.getAttribute('onclick') && !button.getAttribute('onclick').includes(id)))){
                    menu.classList.add('hidden');
                }
            });
        });
        setTimeout(function(){
            const alerts=document.querySelectorAll('.bg-green-100, .bg-red-100');
            alerts.forEach(a=>{ a.style.transition='opacity .5s ease'; a.style.opacity='0'; setTimeout(()=>a.remove(),500); });
        }, 5000);
    </script>
    @yield('javascript')
</body>
</html>
