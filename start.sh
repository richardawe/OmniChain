#!/bin/bash

# OmniChain Railway Startup Script
echo "🚀 Starting OmniChain application..."

# Wait for database to be ready
echo "⏳ Waiting for database connection..."
until php artisan tinker --execute="DB::connection()->getPdo();" > /dev/null 2>&1; do
    echo "Database not ready, waiting..."
    sleep 2
done
echo "✅ Database connection established"

# Generate application key if not set
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "" ]; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force
fi

# Run database migrations
echo "📊 Running database migrations..."
php artisan migrate --force

# Cache configuration for production
echo "⚡ Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start the application
echo "🌐 Starting Laravel server..."
php artisan serve --host=0.0.0.0 --port=$PORT
