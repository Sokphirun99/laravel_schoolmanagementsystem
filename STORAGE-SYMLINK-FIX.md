# Resolving Storage Symlink Issues

This document provides solutions for the "symlink(): No such file or directory" error that can occur in both local and Docker environments.

## Local Development Environment

If you're experiencing symlink issues in your local environment, run:

```bash
# Make the script executable if it's not already
chmod +x ./fix-storage-link.sh

# Run the script
./fix-storage-link.sh
```

This script will:
1. Create the `storage/app/public` directory if it doesn't exist
2. Set proper permissions on the `storage` and `bootstrap/cache` directories
3. Create the symbolic link with the `--force` flag
4. Clear Laravel caches

## Docker Environment

The Docker configuration has been updated to automatically handle symlinks when the container starts. The process includes:

1. Creating the `storage/app/public` directory if it doesn't exist
2. Setting proper ownership and permissions
3. Creating the symbolic link with `php artisan storage:link --force`
4. Clearing Laravel caches

### Rebuilding the Docker Environment

If you need to rebuild the Docker environment after updating the configuration:

```bash
# Stop containers
docker-compose down

# Rebuild the application container
docker-compose build app

# Start containers
docker-compose up -d

# Check container logs for any issues
docker-compose logs -f app
```

## Manual Symlink Creation in Docker

If you need to manually create the symlink in a running Docker container:

```bash
# Execute commands in the app container
docker-compose exec app bash -c "mkdir -p /var/www/html/storage/app/public && php artisan storage:link --force"
```

## Troubleshooting

### Issue: Permission Denied

If you see "Permission denied" errors:

```bash
# Fix permissions in Docker
docker-compose exec app bash -c "chown -R www-data:www-data /var/www/html/storage && chmod -R 775 /var/www/html/storage"

# Fix permissions locally
sudo chmod -R 775 ./storage
```

### Issue: Symlink Exists but Files Not Accessible

If the symlink exists but files are not accessible:

1. Check if the symlink is pointing to the correct location:
   ```bash
   ls -la public/storage
   ```

2. Verify the target directory exists:
   ```bash
   ls -la storage/app/public
   ```

3. Force recreate the symlink:
   ```bash
   php artisan storage:link --force
   ```

4. Clear Laravel caches:
   ```bash
   php artisan config:clear && php artisan cache:clear
   ```
