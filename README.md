# School Management System

A comprehensive school management system built with Laravel and Voyager admin panel, featuring a separate portal for students and parents. The system is containerized with Docker for easy deployment and management.

![School Management System](https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg)

## Overview

The School Management System provides a complete solution for educational institutions to manage administrative tasks, student records, and facilitate communication between teachers, students, and parents. The system consists of a powerful admin panel for administrators and teachers, and dedicated portals for students and parents.

## Installation & Setup Guide

This guide provides step-by-step instructions for setting up, running, and troubleshooting the School Management System.

## Features

- **Dual Interface**:
  - Powerful Admin Panel powered by Voyager
  - Dedicated Student and Parent Portal with custom authentication

- **User Management**: 
  - Administrators, teachers, students, and parents with role-based access
  - Advanced role system supporting multiple roles per user
  - Comprehensive role-based access control
  
- **Academic Management**: 
  - Classes, sections, subjects, and timetables
  - Assignment management and submission
  - Course enrollment and tracking

- **Student/Parent Portal**:
  - Customized dashboards for students and parents
  - Access to grades, attendance, and announcements
  - Fee payment and tracking
  - Communication with teachers

- **Communication System**:
  - School-wide announcements with audience targeting
  - Teacher-parent messaging
  - Event notifications and alerts
  
- **Financial Management**: 
  - Fee structures and categories
  - Payment tracking and receipts
  - Due date reminders
  
- **Reports & Analytics**: 
  - Academic performance tracking
  - Attendance summaries
  - Administrative dashboards with key metrics

## System Requirements

- Docker & Docker Compose
- Git
- 2GB+ RAM
- 5GB+ disk space

## Quick Installation

```bash
# Clone the repository
git clone https://github.com/Sokphirun99/laravel_schoolmanagementsystem.git
cd laravel_schoolmanagementsystem

# Start Docker containers
docker compose up -d

# Create environment file from example
cp .env.example .env

# Update database connection in .env file (make sure DB_HOST=db)
sed -i '' 's/DB_HOST=127.0.0.1/DB_HOST=db/' .env

# Install Laravel dependencies
docker compose exec app composer install

# Set proper permissions
docker compose exec app chmod -R 775 storage bootstrap/cache
docker compose exec app chown -R www-data:www-data storage bootstrap/cache

# Generate application key
docker compose exec app php artisan key:generate

# Create storage link
docker compose exec app php artisan storage:link --force

# Run migrations and seed the database
docker compose exec app php artisan migrate --seed

# Install Voyager with sample data
docker compose exec app php artisan voyager:install --with-dummy

# Run seeders for school management system
docker compose exec app php artisan db:seed --class=FeesTableSeeder
docker compose exec app php artisan db:seed --class=ParentsSeeder
docker compose exec app php artisan db:seed --class=SchoolClassesSeeder
docker compose exec app php artisan db:seed --class=SectionsSeeder
docker compose exec app php artisan db:seed --class=StudentsSeeder

# Setup admin user
docker compose exec app php artisan voyager:admin
```

## Complete Setup Guide

### 1. Application Setup

After installing with the quick instructions above, you can:

```bash
# Run database migrations if needed
docker compose exec app php artisan migrate

# Seed the database with specific data sets if needed
docker compose exec app php artisan db:seed --class=SchoolRolesSeeder
docker compose exec app php artisan db:seed --class=StudentsSeeder
docker compose exec app php artisan db:seed --class=TeachersSeeder
docker compose exec app php artisan db:seed --class=ClassesSeeder
```
# Run your menu setup command to ensure proper navigation
docker compose exec app php artisan voyager:install
```
### 2. Clear Application Cache

After making configuration changes:

```bash
docker compose exec app php artisan config:clear
docker compose exec app php artisan cache:clear
docker compose exec app php artisan view:clear
docker compose exec app php artisan route:clear
```

## Accessing the Application

- **Main Application**: http://localhost:8080
- **Admin Panel**: http://localhost:8080/admin
  - Default credentials:
    - Email: `admin@school.com`
    - Password: `password`
- **Student/Parent Portal**: http://localhost:8080/portal/login
  - Student credentials:
    - Email: `student@school.com`
    - Password: `password`
  - Parent credentials:
    - Email: `parent@school.com`
    - Password: `password`
- **Database Management**: http://localhost:8081
  - Server: `db`
  - Username: `laravel`
  - Password: `laravel`

## User Account Types

| Role | Access Level | Default Credentials |
|------|-------------|---------------------|
| Administrator | Full system access | Email: admin@school.com<br>Password: password |
| Teacher | Class management, attendance, grades | Created through admin panel |
| Student | View timetables, exam results, attendance | Email: student@school.com<br>Password: password |
| Parent | View child information, reports | Email: parent@school.com<br>Password: password |

## Project Structure

### Core Models
- **App Models**: Student, Teacher, ParentModel, SchoolClass, Section, PortalUser, Announcement, Fee, etc.
- **Controllers**: Admin and Portal controllers for all features
- **Database Seeders**: Prepopulated data for testing
- **Views**: Voyager-based admin templates and custom views
- **Routes**: Web routes for admin panel and student/parent portal

### Key Features Implementation

#### Authentication System
- Separate authentication for admin panel and portals
- Role-based access control with Voyager
- Custom middleware for portal access

#### Portal System
- Student Portal: View grades, fees, announcements, timetables
- Parent Portal: Monitor children's progress, communications
- Profile management and password changes
- Responsive design for mobile access

#### Communication System
- School-wide announcement system
- Teacher-parent messaging
- Event notifications and calendar

#### Financial System
- Fee management with different fee types
- Payment tracking and receipts
- Due date notifications
- Payment status tracking

#### Academic System
- Class and section management
- Subject assignments
- Student enrollment
- Grade reporting

## Docker Configuration

The application runs in a containerized environment with the following services:

- **app**: Laravel application running on PHP 8.2 with Apache
- **db**: MySQL 8 database server
- **phpmyadmin**: Web interface for MySQL database management
- **ngrok**: For exposing the application to the internet (development only)
- **redis**: For caching and session management

### Architecture

The system follows a modular architecture:

1. **Core Layer**: Laravel framework with Voyager admin panel
2. **Data Layer**: MySQL database with Eloquent ORM
3. **Presentation Layer**: 
   - Admin Panel: Voyager-based interface
   - Student Portal: Custom Bootstrap-based interface
   - Parent Portal: Custom Bootstrap-based interface
4. **API Layer**: RESTful endpoints for future mobile app integration
5. **Background Processing**: Queue-based processing for reports and notifications

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

If you encounter database connection errors like `getaddrinfo for db failed`, this typically happens when running Laravel locally while Docker containers are running. Here are the solutions:

**For Local Development (using `php artisan serve`):**
```bash
# Update your .env file to use localhost instead of 'db'
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=secret

# Clear configuration cache
php artisan config:clear
```

**For Docker Development:**
```bash
# Use the Docker service name
DB_HOST=db
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=secret
```

### Common Issues and Solutions

If you encounter "Connection refused" or "Name or service not known" errors:

```bash
# Check if your .env file has the correct database settings
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=secret

# Clear config cache
docker compose exec app php artisan config:clear

# Check database logs
docker compose logs db

# Check if containers are running
docker ps

# If DB container is restarting constantly, try removing volumes and starting fresh
docker compose down -v
docker compose up -d
```

If you see errors about MySQL version incompatibility in the logs:

```bash
# Remove all containers and volumes
docker compose down -v

# Start the containers again
docker compose up -d

# Follow the setup process from the beginning
cp .env.example .env
sed -i '' 's/DB_HOST=127.0.0.1/DB_HOST=db/' .env
docker compose exec app composer install
# ...and so on
```

If you encounter file permission errors:

```bash
docker compose exec app chmod -R 775 storage bootstrap/cache
docker compose exec app chown -R www-data:www-data storage bootstrap/cache
```

If you can't login to the admin panel, try:

```bash
# Reset password for admin user
docker compose exec app php artisan voyager:admin

# Or manually reset the password via Tinker
docker compose exec app php artisan tinker
> DB::table('users')->where('id', 1)->update(['password' => bcrypt('password')]);
> exit

# If you need to create a new portal user (student or parent)
docker compose exec app php artisan tinker
> App\Models\PortalUser::create(['name' => 'Student Name', 'email' => 'student@school.com', 'password' => bcrypt('password'), 'user_type' => 'student', 'related_id' => 1, 'status' => true]);
> App\Models\PortalUser::create(['name' => 'Parent Name', 'email' => 'parent@school.com', 'password' => bcrypt('password'), 'user_type' => 'parent', 'related_id' => 1, 'status' => true]);
> exit
```

If you can log in but don't see the dashboard:

```bash
# Check route configuration
docker compose exec app php artisan route:list | grep dashboard

# Temporarily comment out custom dashboard routes in routes/web.php
# Replace:
# Route::get('/', [SchoolDashboardController::class, 'index'])->name('voyager.dashboard');
# With:
# // Route::get('/', [SchoolDashboardController::class, 'index'])->name('voyager.dashboard');

# Clear all caches
docker compose exec app php artisan cache:clear
docker compose exec app php artisan config:clear
docker compose exec app php artisan view:clear
docker compose exec app php artisan route:clear
```

## Role Management System

The system includes an advanced role management implementation that supports multiple roles per user. For detailed information, see `README-ROLE-SYSTEM.md`.

### Recent Updates (June 2025)

We've recently enhanced the school management system with several new features:

- Added comprehensive announcement system for school-wide communications
- Implemented fee management system with payment tracking
- Created student and parent portal with custom authentication
- Redesigned the welcome page to match Voyager admin theme
- Added database models, migrations, and seeders for all school entities
- Implemented proper relationships between students, parents, classes, and sections
- Added dedicated dashboards for students and parents

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
