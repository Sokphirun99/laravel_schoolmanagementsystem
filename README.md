# School Management System

A comprehensive school management system built with Laravel and Voyager admin panel, containerized with Docker for easy deployment and management.

![School Management System](https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg)

## Features

- **User Management**: Administrators, teachers, students, and parents with role-based access
  - Advanced role system supporting multiple roles per user
  - Comprehensive role-based access control
- **Academic Management**: Classes, sections, subjects, and timetables
- **Student Management**: Enrollment, attendance, and performance tracking
- **Teacher Management**: Staff profiles, assignments, and schedules
- **Examination System**: Exam creation, grading, and result analysis
- **Financial Management**: Fee structures, invoices, and payment tracking
- **Attendance Tracking**: Daily attendance records for students and staff
- **Reports & Analytics**: Academic and administrative reporting tools
- **School Calendar**: Academic year planning and event management
- **CI/CD Integration**: Automated build, test, and deployment with Jenkins and Kubernetes

## System Requirements

- Docker & Docker Compose
- Git
- 2GB+ RAM
- 5GB+ disk space

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
docker compose exec app php artisan storage:link --force

# Run migrations and seed the database
docker compose exec app php artisan migrate --seed

# Install Voyager with sample data
docker compose exec app php artisan voyager:install --with-dummy
```

## Complete Setup Guide

### 1. Application Setup

After installing with the quick instructions above, you can:

```bash
# Run database migrations
docker compose exec app php artisan migrate

# Seed the database with initial data
docker compose exec app php artisan db:seed

# Seed specific data sets
docker compose exec app php artisan db:seed --class=SchoolRolesSeeder
docker compose exec app php artisan db:seed --class=StudentsSeeder
docker compose exec app php artisan db:seed --class=TeachersSeeder
docker compose exec app php artisan db:seed --class=ClassesSeeder
```

### 2. Clear Application Cache

After making configuration changes:

```bash
docker compose exec app php artisan config:clear
docker compose exec app php artisan cache:clear
docker compose exec app php artisan view:clear
```

## Accessing the Application

- **Main Application**: http://localhost:8080
- **Admin Panel**: http://localhost:8080/admin
  - Default credentials:
    - Email: `admin@admin.com`
    - Password: `password`
- **Database Management**: http://localhost:8081
  - Server: `db`
  - Username: `laravel`
  - Password: `secret`

## User Account Types

| Role | Access Level | Default Credentials |
|------|-------------|---------------------|
| Administrator | Full system access | Email: admin@admin.com<br>Password: password |
| Teacher | Class management, attendance, grades | Email: teacher1@school.test<br>Password: password123 |
| Student | View timetables, exam results, attendance | Email: student1@school.test<br>Password: password123 |
| Parent | View child information, reports | Generated during setup |

## Project Structure

- **App Models**: Student, Teacher, Parents, ClassRoom, Section, etc.
- **Controllers**: Role-specific dashboards and operations
- **Database Seeders**: Prepopulated data for testing
- **Views**: Voyager-based admin templates and custom views
- **Routes**: Web routes for different user types

## Docker Configuration

The application runs in Docker containers with the following services:

- **app**: Laravel application running on PHP 8.2 with Apache
- **db**: MySQL 8 database server
- **phpmyadmin**: Web interface for MySQL database management

## Customization

### 1. Environment Settings

Modify the .env file for application settings:

```
APP_NAME="School Management System"
APP_URL=http://localhost:8080

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=secret
```

### 2. Docker Settings

Modify docker-compose.yml for container configurations.

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

### Login Issues

If you can't login to the admin panel, try:

```bash
# Reset password for admin user
docker compose exec app php artisan voyager:admin your@email.com --create
```

## Role Management System

The system includes an advanced role management implementation that supports multiple roles per user. For detailed information, see `README-ROLE-SYSTEM.md`.

### Recent Cleanup (May 2025)

We've recently cleaned up the codebase by removing redundant files and streamlining the role management system:

- Removed duplicate migration for `user_roles` table
- Consolidated multiple role synchronization commands and seeders
- Updated documentation to reflect the current implementation
- Ensured backward compatibility with the legacy role system

All removed files have been backed up in their respective `_backup` directories.

## Support & Contribution

- **Issues**: Submit issues on GitHub
- **Pull Requests**: Contributions are welcome
- **Documentation**: More detailed docs in the `/docs` directory

## CI/CD Pipeline

This project includes a complete CI/CD pipeline setup with Jenkins and Kubernetes:

### Jenkins Pipeline

The included Jenkinsfile defines a complete pipeline that:
- Builds the application
- Runs automated tests
- Creates a Docker image
- Deploys to Kubernetes

### Kubernetes Deployment

The `/kubernetes` directory contains all necessary manifests for deploying to a Kubernetes cluster:

```bash
# Apply all Kubernetes configurations
./kubernetes/setup.sh
```

For detailed instructions on setting up the CI/CD pipeline, see [Jenkins and Kubernetes Setup Guide](docs/jenkins-kubernetes-setup.md).

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

© 2025 School Management System. Built with Laravel, Voyager, and Docker.
