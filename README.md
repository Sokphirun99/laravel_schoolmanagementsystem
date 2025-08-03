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
- [Voyager UI](README-VOYAGER-UI.md)

---

© 2025 School Management System. Built with Laravel, Voyager, and Docker.

---

© 2025 School Management System. Built with Laravel, Voyager, and Docker.
