#!/bin/bash

# Laravel startup script with proper environment loading
echo "🚀 Starting OmniChain Laravel application..."

# Set default port if not provided
export PORT=${PORT:-8000}

# Create .env file if it doesn't exist
if [ ! -f "/app/.env" ]; then
    echo "📝 Creating .env file from example..."
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

# Set cache and session drivers to database (fallback from Redis)
export CACHE_DRIVER=database
export SESSION_DRIVER=database
export QUEUE_CONNECTION=database

# Set application environment
export APP_ENV=production
export APP_DEBUG=false
export APP_URL=https://web-production-8c4a.up.railway.app

# Generate application key if not set
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "" ]; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force
fi

# Clear all caches
echo "🧹 Clearing Laravel caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Test database connection
echo "🔍 Testing database connection..."
php artisan tinker --execute="try { DB::connection()->getPdo(); echo 'Database: Connected'; } catch (Exception \$e) { echo 'Database Error: ' . \$e->getMessage(); }"

# Run migrations
echo "📊 Running database migrations..."
php artisan migrate --force

# Cache configuration for production
echo "⚡ Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start the application
echo "🌐 Starting Laravel server on 0.0.0.0:$PORT"
php artisan serve --host=0.0.0.0 --port=$PORT --no-reload
