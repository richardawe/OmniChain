#!/bin/bash

# Fixed startup script for Railway
echo "🚀 Starting OmniChain (fixed version)..."

# Set default port if not provided
export PORT=${PORT:-8000}

# Create .env file if it doesn't exist
if [ ! -f "/app/.env" ]; then
    echo "📝 Creating .env file..."
    cp /app/.env.example /app/.env
fi

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

# Set cache and session drivers to database (fallback)
export CACHE_DRIVER=database
export SESSION_DRIVER=database
export QUEUE_CONNECTION=database

# Generate application key if not set
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "" ]; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force
fi

# Start the application
echo "🌐 Starting Laravel server on 0.0.0.0:$PORT"
php artisan serve --host=0.0.0.0 --port=$PORT --no-reload
