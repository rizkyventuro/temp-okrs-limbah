#!/bin/sh
set -e

echo "Starting Mudik AirNav Application..."

if [ "$APP_ENV" = "production" ]; then
    echo "Running in production mode"
    
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    php artisan event:cache
    
    if [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
        echo "Running migrations..."
        php artisan migrate --force --no-interaction
    fi
fi

php artisan storage:link || true

exec "$@"
