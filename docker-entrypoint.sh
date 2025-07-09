#!/bin/bash
set -e

# Function to wait for database
wait_for_db() {
    echo "Waiting for database to be ready..."
    until nc -z -v -w30 db 3306; do
        echo "Waiting for database connection..."
        sleep 2
    done
    echo "Database is ready!"
}

# Function to check if database exists and is accessible
check_database() {
    php -r "
    try {
        \$pdo = new PDO('mysql:host=db;port=3306', 'laravel', 'secret');
        \$pdo->exec('CREATE DATABASE IF NOT EXISTS laravel');
        echo 'Database checked/created successfully';
    } catch (Exception \$e) {
        echo 'Database connection failed: ' . \$e->getMessage();
        exit(1);
    }
    "
}

# Set proper permissions for specific directories only
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache
chown -R www-data:www-data /var/www/html/public
chmod -R 755 /var/www/html/storage
chmod -R 755 /var/www/html/bootstrap/cache

# Install/update composer dependencies
echo "Installing composer dependencies..."
composer install --no-dev --optimize-autoloader

# Wait for database if using MySQL
if [ "$DB_CONNECTION" = "mysql" ]; then
    wait_for_db
    check_database
fi

# Use Docker environment file
echo "Setting up Docker environment..."
if [ -f /var/www/html/.env.docker ]; then
    cp /var/www/html/.env.docker /var/www/html/.env
else
    cp /var/www/html/.env.example /var/www/html/.env
fi

# Generate app key if not set
if ! grep -q "APP_KEY=base64:" /var/www/html/.env; then
    echo "Generating application key..."
    php artisan key:generate --force
fi

# Clear and cache config
echo "Clearing and caching configuration..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Run migrations
echo "Running migrations..."
php artisan migrate --force

# Create storage link if not exists
if [ ! -L /var/www/html/public/storage ]; then
    echo "Creating storage symlink..."
    php artisan storage:link
fi

# Install Voyager if not installed
if ! php artisan voyager:admin --help > /dev/null 2>&1; then
    echo "Installing Voyager..."
    php artisan voyager:install --with-dummy
fi

# Seed the database if tables are empty
echo "Checking if seeding is needed..."
USER_COUNT=$(php artisan tinker --execute="echo App\Models\User::count();")
if [ "$USER_COUNT" -eq "0" ]; then
    echo "Seeding database..."
    php artisan db:seed --class=TestUsersSeeder
    php artisan db:seed --class=SchoolClassSeeder
    php artisan db:seed --class=SectionSeeder
fi

# Set final permissions for specific directories only
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache
chown -R www-data:www-data /var/www/html/public
chmod -R 755 /var/www/html/storage
chmod -R 755 /var/www/html/bootstrap/cache

echo "Laravel application is ready!"

# Start Apache
exec apache2-foreground
