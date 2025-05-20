# Using Laravel School Management System with PHP 8.4+

This document provides guidance on running this Laravel 9.x application with PHP 8.4 or higher.

## About PHP 8.4 Deprecation Warnings

PHP 8.4 introduces stricter type checking and has started showing deprecation notices for implicit nullable parameters. Laravel 9.x was developed before these changes, so running it on PHP 8.4 can result in many deprecation warnings.

These warnings won't prevent the application from functioning, but they can clutter your terminal output and logs.

## How We Handle Deprecation Warnings

This project has been set up to automatically suppress PHP 8.4 deprecation warnings in several ways:

1. The `bootstrap/suppress_deprecations.php` file is included in the main entry points.
2. Special scripts have been provided to run Laravel commands without warnings.

## Recommended Ways to Run Commands

### Using the artisan.sh Script

For command-line operations, use the `artisan.sh` wrapper script:

```bash
# Instead of this:
php artisan migrate

# Use this:
./artisan.sh migrate
```

Common commands:
- `./artisan.sh serve` - Start development server
- `./artisan.sh migrate` - Run database migrations
- `./artisan.sh db:seed` - Seed database
- `./artisan.sh tinker` - Run Laravel Tinker

### Running in Docker

If using Docker, the PHP version inside the container may be different from your local PHP version. The Docker setup is configured to work properly with the database host "db".

```bash
# Start Docker containers
docker compose up -d

# Run commands inside the Docker container
docker compose exec app php artisan migrate
```

## Database Configuration

This application is configured to detect whether it's running inside Docker or locally:

- In Docker: Uses the hostname "db" for database connections
- Local development: Automatically uses "127.0.0.1" when "db" hostname isn't available

This means you don't need to modify your `.env` file when switching between Docker and local development.

## Troubleshooting

If you're still seeing deprecation warnings:

1. Make sure the `bootstrap/suppress_deprecations.php` file exists
2. Use the provided `artisan.sh` script instead of direct `php artisan` commands
3. For web access, check that `public/index.php` includes the suppression file

For persistent database connection issues, check your `.env` file and make sure the 
database credentials are correct for your environment.
