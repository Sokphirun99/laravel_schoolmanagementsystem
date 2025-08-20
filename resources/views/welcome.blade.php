<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'School Management System') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.ico') }}">
    <style>
        html, body { height: 100%; }
        body { margin:0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, 'Noto Sans', sans-serif; background: #f5f7fb; color:#334155; }
        .wrap { min-height:100%; display:flex; align-items:center; justify-content:center; padding: 2rem }
        .card { background:#fff; border:1px solid rgba(0,0,0,.06); border-radius:16px; box-shadow: 0 10px 30px rgba(2,6,23,.06); padding: 2rem; width: 100%; max-width: 420px; text-align:center }
        .logo { width:96px; height:96px; border-radius: 16px; object-fit: contain; margin: 0 auto 1rem auto; display:block; }
        .title { font-weight: 700; font-size: 1.25rem; margin-bottom:.25rem }
        .subtitle { color:#64748b; font-size:.95rem; margin-bottom: 1.25rem }
        .btn { display:block; width:100%; padding:.75rem 1rem; border-radius:12px; border:1px solid transparent; font-weight:600; text-decoration:none; margin-bottom:.75rem; transition: all .15s ease; }
        .btn-primary { background:#2563eb; color:#fff; }
        .btn-primary:hover { background:#1d4ed8 }
        .btn-outline { background:#fff; color:#2563eb; border-color:#2563eb }
        .btn-outline:hover { background:#eff6ff }
        .muted { font-size:.8rem; color:#94a3b8; margin-top:.5rem }

        /* Keyboard accessibility */
        .btn:focus-visible { outline: 3px solid #1d4ed8; outline-offset: 2px; box-shadow: 0 0 0 4px rgba(37,99,235,.15); }
        .btn:active { transform: translateY(0.5px); }

        /* Respect reduced motion */
        @media (prefers-reduced-motion: reduce) {
            .btn { transition: none !important; }
        }

        /* Dark mode */
        @media (prefers-color-scheme: dark) {
            body { background:#0b1220; color:#cbd5e1; }
            .card { background:#0f172a; border-color: rgba(255,255,255,.08); box-shadow: 0 10px 30px rgba(0,0,0,.5); }
            .title { color:#e2e8f0; }
            .subtitle { color:#94a3b8; }
            .btn-primary { background:#3b82f6; color:#0b1220; }
            .btn-primary:hover { background:#2563eb; }
            .btn-outline { background:transparent; color:#93c5fd; border-color:#3b82f6; }
            .btn-outline:hover { background: rgba(59,130,246,.08); }
        }

        .login-container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #1d4ed8, #2563eb);
        }

        .login-box {
            background: #fff;
            border-radius: 8px;
            padding: 2rem;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 10px 25px rgba(0,0,0,.1);
            text-align: center;
        }

        .login-header h1 {
            margin: 1rem 0 .5rem;
            font-size: 1.5rem;
            color: #1f2937;
        }

        .login-header p {
            color: #6b7280;
            margin-bottom: 1.5rem;
        }

        .login-logo {
            width: 64px;
            height: 64px;
            border-radius: 12px;
            object-fit: contain;
        }

        .form-group {
            margin-bottom: 1rem;
            text-align: left;
        }

        .form-group input {
            width: 100%;
            padding: .75rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 1rem;
        }

        .btn-login {
            width: 100%;
            background: #2563eb;
            color: #fff;
            padding: .75rem 1rem;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: background .3s ease;
        }

        .btn-login:hover {
            background: #1d4ed8;
        }

        .login-footer {
            margin-top: 1rem;
        }

        .login-footer a {
            color: #2563eb;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <div class="login-header">
                <img class="login-logo" src="{{ asset('images/school-logo.png') }}" alt="{{ config('app.name', 'School') }} logo" onerror="this.style.display='none'">
                <h1>{{ config('app.name', 'School Management System') }}</h1>
                <p>Sign in to continue to your dashboard</p>
            </div>
            <form id="loginForm" method="POST" action="{{ route('portal.login') }}">
                @csrf
                <div class="form-group">
                    <label for="role" style="font-weight:600;display:block;margin-bottom:.5rem;">Login as:</label>
                    <select id="role" name="role" style="width:100%;padding:.5rem 1rem;border-radius:6px;border:1px solid #d1d5db;font-size:1rem;">
                        <option value="portal">Student/Parent/Teacher</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="form-group">
                    <input type="email" name="email" placeholder="Email Address" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <button type="submit" class="btn-login">Login</button>
            </form>
            <script>
                // Switch form action based on role
                document.getElementById('role').addEventListener('change', function(e) {
                    var form = document.getElementById('loginForm');
                    if (e.target.value === 'admin') {
                        form.action = '{{ url('/admin/login') }}';
                    } else {
                        form.action = '{{ route('portal.login') }}';
                    }
                });
            </script>
        </div>
    </div>
</body>
</html>
