#!/bin/bash

# School Management System Docker Manager
# Usage: ./docker-manage.sh [start|stop|restart|logs|build|shell|reset]

case "$1" in
    start)
        echo "Starting Docker containers..."
        docker-compose up -d
        echo "Waiting for services to be ready..."
        sleep 10
        echo "Services are running:"
        echo "- Application: http://localhost:8080"
        echo "- phpMyAdmin: http://localhost:8081"
        echo "- Ngrok dashboard: http://localhost:4040"
        ;;
    stop)
        echo "Stopping Docker containers..."
        docker-compose down
        ;;
    restart)
        echo "Restarting Docker containers..."
        docker-compose down
        docker-compose up -d
        ;;
    logs)
        echo "Showing application logs..."
        docker-compose logs -f app
        ;;
    build)
        echo "Building Docker images..."
        docker-compose build --no-cache
        ;;
    shell)
        echo "Opening shell in app container..."
        docker-compose exec app bash
        ;;
    reset)
        echo "Resetting Docker environment (WARNING: This will delete all data)..."
        read -p "Are you sure? (y/N): " -n 1 -r
        echo
        if [[ $REPLY =~ ^[Yy]$ ]]; then
            docker-compose down -v
            docker-compose build --no-cache
            docker-compose up -d
        fi
        ;;
    status)
        echo "Docker container status:"
        docker-compose ps
        ;;
    *)
        echo "Usage: $0 {start|stop|restart|logs|build|shell|reset|status}"
        echo ""
        echo "Commands:"
        echo "  start   - Start all containers"
        echo "  stop    - Stop all containers"
        echo "  restart - Restart all containers"
        echo "  logs    - Show application logs"
        echo "  build   - Rebuild Docker images"
        echo "  shell   - Open shell in app container"
        echo "  reset   - Reset entire environment (deletes data)"
        echo "  status  - Show container status"
        exit 1
        ;;
esac
