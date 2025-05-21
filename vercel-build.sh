#!/bin/bash

# Vercel build script for Laravel School Management System
echo "Starting Vercel build process..."

# Install PHP dependencies with Composer
echo "Installing Composer dependencies..."
if [ -f "composer.lock" ]; then
  composer install --no-dev --optimize-autoloader
else
  composer update --no-dev --optimize-autoloader
fi

# Ensure the .env file exists
echo "Creating .env file..."
cp .env.vercel .env 2>/dev/null || echo "APP_KEY=${APP_KEY:-base64:KrA5W+nxGACkZInV26qCQMUJ4BSIMFwKSyePxoEIHcg=}" > .env

# Create directories needed for the build
echo "Setting up storage directories..."
mkdir -p public/storage
mkdir -p public/storage/users
mkdir -p public/css public/js public/images

# Copy storage directory contents to public/storage for Vercel
echo "Copying storage files to public directory..."
cp -r storage/app/public/* public/storage/ 2>/dev/null || true

# Copy images for avatars
if [ -f "public/images/teacher.png" ]; then
  echo "Creating default avatar images..."
  cp public/images/teacher.png public/storage/users/default.png
fi

# Run optimize commands without failing if they don't work
echo "Optimizing Laravel configuration..."
php artisan optimize:clear || true
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Create .vercel directory if it doesn't exist
mkdir -p .vercel

echo "Build process completed successfully!"
