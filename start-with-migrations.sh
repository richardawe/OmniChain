#!/bin/bash

# OmniChain Railway startup script with PostgreSQL and Redis
echo "🚀 Starting OmniChain with PostgreSQL and Redis..."

# Set default port if not provided
export PORT=${PORT:-8000}

# Generate application key if not set
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "" ]; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force
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

# Set cache and session drivers
export CACHE_DRIVER=redis
export SESSION_DRIVER=redis
export QUEUE_CONNECTION=redis

# Wait for database to be ready
echo "⏳ Waiting for database connection..."
until php artisan tinker --execute="DB::connection()->getPdo();" > /dev/null 2>&1; do
    echo "Database not ready, waiting..."
    sleep 2
done
echo "✅ Database connection established"

# Run migrations
echo "📊 Running database migrations..."
php artisan migrate --force

# Seed database with sample data
echo "🌱 Seeding database..."
php artisan db:seed --force

# Cache configuration for production
echo "⚡ Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start the application
echo "🌐 Starting Laravel server on 0.0.0.0:$PORT"
php artisan serve --host=0.0.0.0 --port=$PORT --no-reload
