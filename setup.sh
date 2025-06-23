#!/bin/bash

# Copy Docker environment file to .env
echo "Setting up environment for Docker..."
cp .env.docker .env

# Start Docker services
echo "Starting Docker services..."
docker-compose up -d

# Wait for database to be ready
echo "Waiting for database to initialize..."
sleep 10

# Set up the admin user directly in the container
echo "Setting up admin user in the container..."
docker-compose exec app php artisan voyager:install --with-dummy
docker-compose exec app php -r "
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
        echo \"You can now log in with:\\nEmail: admin@school.com\\nPassword: password\\n\";
    } catch (Exception \$e) {
        echo \$e->getMessage() . \"\\n\";
    }
"

echo "Setup complete! You can access:"
echo "- Main application: http://localhost:8080"
echo "- Admin panel: http://localhost:8080/admin"
echo "- PhpMyAdmin: http://localhost:8081"
echo ""
echo "Admin login:"
echo "- Email: admin@school.com"
echo "- Password: password"
