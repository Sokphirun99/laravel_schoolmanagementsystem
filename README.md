<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## School Management System

A comprehensive school management system built with Laravel and Voyager admin panel, containerized with Docker for easy deployment.

## System Requirements

- Docker & Docker Compose
- Git

## Quick Installation

```bash
# Clone the repository
git clone https://github.com/yourusername/laravel_schoolmanagementsystem.git
cd laravel_schoolmanagementsystem

# Start Docker containers
docker compose up -d

# Install Laravel dependencies
docker compose exec app composer install

# Set proper permissions
docker compose exec app chmod -R 775 storage bootstrap/cache
docker compose exec app chown -R www-data:www-data storage bootstrap/cache

# Generate application key
docker compose exec app php artisan key:generate

# Create storage link
docker compose exec app php artisan storage:link

# Install Voyager with sample data
docker compose exec app php artisan voyager:install --with-dummy
```

## Accessing the Application

- **Main Application**: http://localhost:8080
- **Admin Panel**: http://localhost:8080/admin
  - Default credentials:
    - Email: admin@admin.com
    - Password: password
- **Database Management**: http://localhost:8081
  - Server: db
  - Username: laravel
  - Password: secret

## Docker Configuration

The application runs in Docker containers with the following services:

- **app**: Laravel application running on PHP 8.2 with Apache
- **db**: MySQL 8 database server
- **phpmyadmin**: Web interface for MySQL database management

## Database Configuration

MySQL database connection settings can be modified in the .env file:

```
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=secret
```

## Troubleshooting

### Database Connection Issues

If you encounter "Connection refused" or "Name or service not known" errors:

```bash
# Clear config cache
docker compose exec app php artisan config:clear

# Check database logs
docker compose logs db

# Check if containers are running
docker ps
```

### File Permission Issues

If you encounter file permission errors:

```bash
docker compose exec app chmod -R 775 storage bootstrap/cache
docker compose exec app chown -R www-data:www-data storage bootstrap/cache
```

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
