#!/bin/bash

# Debug startup script for Railway
echo "🚀 Starting OmniChain debug mode..."

# Set default port if not provided
export PORT=${PORT:-8000}

# Set database configuration for PostgreSQL
export DB_CONNECTION=pgsql
export DB_HOST=turntable.proxy.rlwy.net
export DB_PORT=54435
export DB_DATABASE=railway
export DB_USERNAME=postgres
export DB_PASSWORD=nqHVYqKxKaBcZPPZzErDHTPZBFRUsyWR

# Set Redis configuration
export REDIS_HOST=hopper.proxy.rlwy.net
export REDIS_PORT=11128
export REDIS_PASSWORD=gOxwGrzjoBeqwHHGFWAFqgRPnZDutALU

# Set cache and session drivers
export CACHE_DRIVER=redis
export SESSION_DRIVER=redis
export QUEUE_CONNECTION=redis

# Generate application key if not set
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "" ]; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force
fi

# Test database connection
echo "🔍 Testing database connection..."
php artisan tinker --execute="echo 'Database connected: ' . DB::connection()->getPdo() ? 'YES' : 'NO';"

# Test Redis connection
echo "🔍 Testing Redis connection..."
php artisan tinker --execute="echo 'Redis connected: ' . (Redis::ping() ? 'YES' : 'NO');"

# Start the application
echo "🌐 Starting Laravel server on 0.0.0.0:$PORT"
php artisan serve --host=0.0.0.0 --port=$PORT --no-reload
