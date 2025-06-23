#!/bin/bash

echo "Checking Voyager admin access directly in Docker container..."

# Get into the container and check if admin page works
docker-compose exec app curl -v http://localhost/admin 2>&1 | grep -i "HTTP\|Location\|Error\|<title>\|voyager"

echo ""
echo "Check if Voyager assets are properly installed:"
docker-compose exec app ls -la public/vendor/voyager/
