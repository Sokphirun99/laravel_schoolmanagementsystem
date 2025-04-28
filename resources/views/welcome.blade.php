<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>School Management System</title>

        <!-- Fonts -->
        <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->
        <style>
            body {
                font-family: 'Nunito', sans-serif;
                background: linear-gradient(135deg, #f8fafc 0%, #e0e7ef 100%);
                min-height: 100vh;
                margin: 0;
            }
            .main-container {
                max-width: 700px;
                margin: 60px auto;
                background: #fff;
                border-radius: 16px;
                box-shadow: 0 8px 32px rgba(60,72,88,0.12);
                padding: 40px 32px;
            }
            .logo {
                display: block;
                margin: 0 auto 24px auto;
                width: 120px;
            }
            h1 {
                text-align: center;
                font-size: 2.5rem;
                color: #2d3748;
                margin-bottom: 8px;
            }
            .subtitle {
                text-align: center;
                color: #4a5568;
                margin-bottom: 32px;
                font-size: 1.15rem;
            }
            .quick-links {
                display: flex;
                flex-wrap: wrap;
                gap: 24px;
                justify-content: center;
                margin-bottom: 32px;
            }
            .quick-link {
                background: #f1f5f9;
                border-radius: 10px;
                padding: 18px 28px;
                text-align: center;
                text-decoration: none;
                color: #2d3748;
                font-weight: 600;
                font-size: 1.1rem;
                box-shadow: 0 2px 8px rgba(60,72,88,0.06);
                transition: background 0.2s, color 0.2s;
            }
            .quick-link:hover {
                background: #3182ce;
                color: #fff;
            }
            .info-section {
                margin-top: 32px;
                background: #f8fafc;
                border-radius: 10px;
                padding: 24px;
                color: #4a5568;
                font-size: 1rem;
            }
            .footer {
                text-align: center;
                margin-top: 40px;
                color: #a0aec0;
                font-size: 0.95rem;
            }
        </style>
    </head>
    <body>
        <div class="main-container">
            <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" alt="Laravel Logo" class="logo">
            <h1>School Management System</h1>
            <div class="subtitle">
                Welcome to your comprehensive school management platform.<br>
                Built with Laravel & Voyager, containerized with Docker.
            </div>

            <div class="quick-links">
                <a href="{{ url('/admin') }}" class="quick-link">Admin Panel</a>
                <a href="{{ url('/setup') }}" class="quick-link">System Setup</a>
                <a href="http://localhost:8081" class="quick-link" target="_blank">Database Admin</a>
                <a href="https://laravel.com/docs" class="quick-link" target="_blank">Laravel Docs</a>
            </div>

            <div class="info-section">
                <strong>Default Admin Login:</strong><br>
                Email: <code>admin@admin.com</code><br>
                Password: <code>password</code><br>
                <br>
                <strong>Database Admin:</strong><br>
                URL: <a href="http://localhost:8081" target="_blank">http://localhost:8081</a><br>
                Server: <code>db</code> &nbsp; Password: <code>secret</code>
            </div>

            <div class="footer">
                &copy; {{ date('Y') }} School Management System &mdash; Powered by Laravel & Voyager
            </div>
        </div>
    </body>
</html>
