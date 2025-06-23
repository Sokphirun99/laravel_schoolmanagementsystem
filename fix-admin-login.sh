#!/bin/bash

echo "Fixing Voyager admin login..."

# Stop all containers
docker-compose down

# Remove any persistent volumes that might be causing issues
docker volume prune -f

# Start containers again
docker-compose up -d

# Wait for services to start
echo "Waiting for services to start..."
sleep 15

# Run commands to set up admin user
echo "Setting up admin user..."
docker-compose exec app php artisan voyager:install --with-dummy

# Create admin user
docker-compose exec app php artisan tinker --execute="
try {
    \$adminRole = TCG\Voyager\Models\Role::where('name', 'admin')->firstOrFail();
    \$user = TCG\Voyager\Models\User::updateOrCreate(
        ['email' => 'admin@school.com'],
        [
            'name' => 'Admin User',
            'password' => Hash::make('password'),
            'role_id' => \$adminRole->id,
            'remember_token' => Str::random(60)
        ]
    );
    echo 'Admin user created/updated successfully: admin@school.com / password';
} catch(Exception \$e) {
    echo 'Error: '.\$e->getMessage();
}"

echo ""
echo "Setup completed!"
echo "You should now be able to login at http://localhost:8080/admin"
echo "with email: admin@school.com and password: password"
