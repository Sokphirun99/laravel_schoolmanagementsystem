# Docker Setup for Laravel School Management System

## Quick Start

The Laravel School Management System is now fully containerized and can be run using Docker.

### Prerequisites

- Docker and Docker Compose installed
- At least 2GB of available RAM
- Ports 8080, 8081, 3306, 4040, 6379 available

### Starting the Application

```bash
# Start all services
./docker-manage.sh start

# Or use docker-compose directly
docker-compose up -d
```

### Accessing the Application

Once started, you can access:

- **Main Application**: http://localhost:8080
- **phpMyAdmin**: http://localhost:8081
- **Ngrok Dashboard**: http://localhost:4040
- **MySQL Database**: localhost:3306
- **Redis Cache**: localhost:6379

### Test Accounts

After the containers start, the following test accounts are available:

- **Student**: student@test.com / password
- **Parent**: parent@test.com / password
- **Admin**: admin@admin.com / password

### Docker Services

The setup includes:

1. **app** - Laravel application (PHP 8.2 + Apache)
2. **db** - MySQL 8.0 database
3. **redis** - Redis cache server
4. **phpmyadmin** - Database administration interface
5. **ngrok** - Public tunnel for development

### Management Commands

Use the `docker-manage.sh` script for easy management:

```bash
# Start all containers
./docker-manage.sh start

# Stop all containers
./docker-manage.sh stop

# Restart containers
./docker-manage.sh restart

# View application logs
./docker-manage.sh logs

# Rebuild images
./docker-manage.sh build

# Open shell in app container
./docker-manage.sh shell

# Show container status
./docker-manage.sh status

# Reset entire environment (WARNING: deletes all data)
./docker-manage.sh reset
```

### Running Laravel Commands

To run Laravel Artisan commands:

```bash
# Open shell in app container
./docker-manage.sh shell

# Then run commands inside container
php artisan migrate
php artisan cache:clear
php artisan config:clear
```

Or run commands directly:

```bash
docker-compose exec app php artisan migrate
docker-compose exec app php artisan cache:clear
```

### Database Configuration

The application uses MySQL in Docker with these credentials:
- **Host**: db (internal) or localhost:3306 (external)
- **Database**: laravel
- **Username**: laravel
- **Password**: secret
- **Root Password**: root

### Environment Variables

The application uses `.env.docker` for Docker-specific configuration:
- Database connection points to `db` container
- Redis connection points to `redis` container
- Cache and session use Redis
- Debug mode enabled

### Volumes

The setup uses Docker volumes for:
- **Database data**: Persisted in `db_data` volume
- **Redis data**: Persisted in `redis_data` volume
- **Storage data**: Persisted in `storage_data` volume

### Troubleshooting

1. **Port conflicts**: Ensure ports 8080, 8081, 3306, 4040, 6379 are available
2. **Database connection**: Wait for database to be ready (30-60 seconds)
3. **Permission issues**: Fixed by using `.dockerignore` to exclude Git files and limiting chown to necessary directories
4. **Cache issues**: Run `docker-compose exec app php artisan cache:clear`
5. **Git permission errors**: These are resolved by the `.dockerignore` file excluding Git files from the Docker build context

### Development

For development, files are mounted as volumes, so changes to code are reflected immediately. However, for configuration changes, you may need to restart containers.

### Production Considerations

For production use:
1. Change default passwords in docker-compose.yml
2. Remove ngrok service
3. Set proper environment variables
4. Use production-ready database configuration
5. Set up proper SSL certificates

### Logs

View logs for specific services:

```bash
docker-compose logs app        # Application logs
docker-compose logs db         # Database logs
docker-compose logs redis      # Redis logs
docker-compose logs phpmyadmin # phpMyAdmin logs
```

### Stopping the Application

```bash
# Stop containers (keeps data)
./docker-manage.sh stop

# Stop and remove containers, networks (keeps volumes)
docker-compose down

# Stop and remove everything including volumes (WARNING: deletes all data)
docker-compose down -v
```
