#!/bin/bash

echo "Rebuilding Docker container and publishing Voyager assets..."

# Stop all containers
docker-compose down

# Remove the container image to ensure a fresh build
docker rmi $(docker images -q school_app) 2>/dev/null || echo "No image to remove"

# Build and start the Docker containers
docker-compose up -d

# Wait for services to start
echo "Waiting for services to start..."
sleep 15

# Run commands to publish Voyager assets
echo "Publishing Voyager assets..."
docker-compose exec app php artisan vendor:publish --provider="TCG\Voyager\VoyagerServiceProvider" --tag=assets --force

# Create symbolic link for storage
echo "Creating storage link..."
docker-compose exec app php artisan storage:link

# Create admin user
echo "Creating admin user..."
docker-compose exec app php create_admin.php

echo ""
echo "Setup completed!"
echo "You should now be able to login at http://localhost:8080/admin"
echo "with email: admin@school.com and password: password"
