#!/bin/bash

# Vercel build script for Laravel School Management System

echo "Starting Vercel build process..."

# Install PHP dependencies with Composer
echo "Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader

# Install NPM dependencies if needed
if [ -f "package.json" ]; then
    echo "Installing NPM dependencies..."
    npm install
    npm run build
fi

# Vercel-specific Laravel setup
echo "Optimizing Laravel for Vercel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Ensure storage directory is properly set up
echo "Setting up storage directories..."
mkdir -p storage/app/public
mkdir -p public/storage

# In a production build, we would normally run
# php artisan storage:link
# but on Vercel we'll create symbolic link manually
# by copying files to public/storage
cp -r storage/app/public/* public/storage/

# We need to ensure the default user avatar exists
mkdir -p public/storage/users
mkdir -p storage/app/public/users

if [ -f "public/images/teacher.png" ]; then
    cp public/images/teacher.png public/storage/users/default.png
    cp public/images/teacher.png storage/app/public/users/default.png
fi

echo "Build process completed!"
