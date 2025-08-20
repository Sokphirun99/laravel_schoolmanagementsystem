# School Management System

# School Management System

A modern school management system with Laravel, Voyager admin panel, and Genesis-inspired UI for students and parents.

## 🚀 Quick Start

```bash
# Clone and setup
git clone https://github.com/Sokphirun99/laravel_schoolmanagementsystem.git
cd laravel_schoolmanagementsystem

# Start with Docker
docker compose up -d
cp .env.example .env
sed -i '' 's/DB_HOST=127.0.0.1/DB_HOST=db/' .env

# Install and setup
docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --seed
docker compose exec app php artisan voyager:install --with-dummy
docker compose exec app php artisan voyager:admin
```

## 🌐 Access Points

- **App**: http://localhost:8080
- **Admin**: http://localhost:8080/admin (admin@school.com / password)
- **Portal**: http://localhost:8080/portal/login (student@school.com / password)
- **Database**: http://localhost:8081 (laravel / laravel)

## 🔧 Maintenance

```bash
# Clear caches
docker compose exec app php artisan config:clear
docker compose exec app php artisan cache:clear
docker compose exec app php artisan view:clear

# Check logs
docker compose logs app
docker compose logs db

# Restart services
docker compose restart
```

## 🐛 Common Issues

**Database connection failed?**
```bash
# Fix environment
DB_HOST=db  # For Docker
DB_HOST=127.0.0.1  # For local php artisan serve
docker compose exec app php artisan config:clear
```

**Can't login?**
```bash
docker compose exec app php artisan voyager:admin
```

**Permission errors?**
```bash
docker compose exec app chmod -R 775 storage bootstrap/cache
```

## ✨ Features

- **Modern UI**: Genesis-inspired design with dark mode
- **Dual Interface**: Admin panel + Student/Parent portal  
- **Complete System**: Users, classes, grades, fees, announcements
- **Responsive**: Mobile-friendly design
- **Dockerized**: Easy deployment and scaling

## 📚 Documentation

For detailed documentation, see:
- [Migration Fix](MIGRATION-FIX.md)
- [Role System](README-ROLE-SYSTEM.md)

## 🖥️ Run with MAMP (no Docker)

If you prefer MAMP on macOS, use these steps (adjust the PHP path to your MAMP version, e.g. php8.1.12):

```bash
# 1) Choose MAMP PHP (pick the version you have installed)
MAMP_PHP=/Applications/MAMP/bin/php/php8.1.12/bin/php

# 2) Configure environment
cp .env.example .env
# Then edit .env with these settings (default MAMP MySQL):
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=8889
# DB_DATABASE=laravel_school
# DB_USERNAME=root
# DB_PASSWORD=root

# 3) Create database (or use phpMyAdmin at http://localhost:8888/phpMyAdmin)
/Applications/MAMP/Library/bin/mysql -u root -proot -e "CREATE DATABASE IF NOT EXISTS laravel_school CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 4) Install dependencies & app key
composer install
$MAMP_PHP artisan key:generate

# 5) Migrate & seed
$MAMP_PHP artisan migrate
# Optional demo seeders (uncomment if present)
# $MAMP_PHP artisan db:seed --class=SchoolRolesSeeder
# $MAMP_PHP artisan db:seed --class=StudentsSeeder
# $MAMP_PHP artisan db:seed --class=TeachersSeeder
# $MAMP_PHP artisan db:seed --class=ClassesSeeder

# 6) Storage link & permissions
$MAMP_PHP artisan storage:link
chmod -R 775 storage bootstrap/cache

# 7) Frontend assets
npm install
npm run build   # or: npm run dev

# 8A) Serve via MAMP (recommended)
# In MAMP app → Preferences → Web Server → set Document Root to: <project>/public, then restart
# Visit http://localhost:8888

# 8B) Or quick local server
$MAMP_PHP artisan serve --host=127.0.0.1 --port=8000
# Visit http://127.0.0.1:8000

# Health check (JSON)
curl http://127.0.0.1:8000/health
```

Troubleshooting (MAMP):
- If DB fails to connect, confirm MySQL is running in MAMP and DB_PORT=8889.
- If caches cause odd behavior: `$MAMP_PHP artisan config:clear && $MAMP_PHP artisan cache:clear && $MAMP_PHP artisan view:clear`.
- Logs: `tail -f storage/logs/laravel.log`.
- [Voyager UI](README-VOYAGER-UI.md)

---

© 2025 School Management System. Built with Laravel, Voyager, and Docker.

---

© 2025 School Management System. Built with Laravel, Voyager, and Docker.
