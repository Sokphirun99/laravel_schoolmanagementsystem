#!/bin/bash
set -e

# Run composer install if vendor directory doesn't exist
if [ ! -d "/var/www/html/vendor" ]; then
    echo "Running composer install..."
    composer install --no-interaction --no-plugins --no-scripts
fi

# Generate app key if not already set
if [ ! -f ".env" ] || ! grep -q "APP_KEY=" .env || grep -q "APP_KEY=base64:" .env; then
    echo "Generating application key..."
    php artisan key:generate --force
fi

# Run migrations if specified via environment variable
if [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
    echo "Running database migrations..."
    php artisan migrate --force
fi

# Run seeders if specified via environment variable
if [ "${RUN_SEEDERS:-false}" = "true" ]; then
    echo "Running database seeders..."
    php artisan db:seed --force
    
    # Ensure Voyager is installed properly
    echo "Setting up Voyager..."
    php artisan voyager:install --with-dummy
    
    # Create admin user
    echo "Creating admin user..."
    php artisan voyager:admin --name="Admin User" --email=admin@school.com --password=password
    
    # If the command doesn't exist, try running the custom command
    if [ $? -ne 0 ]; then
        echo "Trying with custom admin creation command..."
        php artisan make:auth
        
        # Create admin user with direct database insert
        php -r "
            require __DIR__.'/vendor/autoload.php';
            \$app = require_once __DIR__.'/bootstrap/app.php';
            \$kernel = \$app->make(Illuminate\Contracts\Console\Kernel::class);
            \$kernel->bootstrap();
            
            try {
                \$adminRole = TCG\Voyager\Models\Role::where('name', 'admin')->firstOrFail();
                \$user = TCG\Voyager\Models\User::where('email', 'admin@school.com')->first();
                
                if (!\$user) {
                    \$user = new TCG\Voyager\Models\User();
                    \$user->name = 'Admin User';
                    \$user->email = 'admin@school.com';
                    \$user->password = password_hash('password', PASSWORD_DEFAULT);
                    \$user->role_id = \$adminRole->id;
                    \$user->save();
                    echo \"Admin user created successfully\\n\";
                } else {
                    \$user->password = password_hash('password', PASSWORD_DEFAULT);
                    \$user->save();
                    echo \"Admin user updated successfully\\n\";
                }
            } catch (Exception \$e) {
                echo \$e->getMessage();
            }
        "
    fi
fi

# Clear and cache routes, config, and views
echo "Optimizing Laravel..."
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start cron service
service cron start

# Start supervisor
/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf

# Execute the main container CMD (Apache)
exec apache2-foreground
