#!/bin/bash

# Improved script to handle Laravel commands without PHP 8.4 deprecation warnings
# Usage: ./artisan.sh command [arguments]

# Parse arguments
CMD="$1"
shift

# Special handling for common Laravel commands
case "$CMD" in
  serve)
    echo "🚀 Starting Laravel development server without deprecation warnings..."
    php -d error_reporting="E_ALL & ~E_DEPRECATED" artisan serve "$@"
    ;;
  migrate)
    echo "🗃️ Running database migrations without deprecation warnings..."
    php -d error_reporting="E_ALL & ~E_DEPRECATED" artisan migrate "$@"
    ;;
  db)
    echo "🔍 Running database command without deprecation warnings..."
    php -d error_reporting="E_ALL & ~E_DEPRECATED" artisan db "$@"
    ;;
  tinker)
    echo "💻 Starting Laravel Tinker without deprecation warnings..."
    php -d error_reporting="E_ALL & ~E_DEPRECATED" artisan tinker "$@"
    ;;
  *)
    # Default case for all other commands
    php -d error_reporting="E_ALL & ~E_DEPRECATED" artisan "$CMD" "$@"
    ;;
esac
