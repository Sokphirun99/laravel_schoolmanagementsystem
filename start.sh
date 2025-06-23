#!/bin/bash

# Set the environment variable to true to run migrations and seeders on startup
export RUN_MIGRATIONS=true
export RUN_SEEDERS=true

# Pull the latest changes
echo "Pulling the latest changes..."
git pull

# Build and start the containers
echo "Building and starting Docker containers..."
docker-compose up -d --build

# Follow the logs
echo "Following app container logs (press Ctrl+C to stop)..."
docker-compose logs -f app
