#!/bin/bash

# Ultra-simple Laravel startup without any database operations
echo "🚀 Starting OmniChain Laravel application (simple mode)..."

# Set default port if not provided
export PORT=${PORT:-8000}

# Create .env file if it doesn't exist
if [ ! -f "/app/.env" ]; then
    echo "📝 Creating .env file from example..."
    cp /app/.env.example /app/.env
fi

# Set application environment
export APP_ENV=production
export APP_DEBUG=false
export APP_URL=https://web-production-8c4a.up.railway.app

# Set cache and session drivers to file (no Redis dependency)
export CACHE_DRIVER=file
export SESSION_DRIVER=file
export QUEUE_CONNECTION=sync

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

# Ensure frontend assets are built
echo "🎨 Checking frontend assets..."
if [ ! -d "/app/public/build" ]; then
    echo "⚠️  Frontend assets not found, building..."
    npm run build
fi

# Skip ALL database operations
echo "⚠️  Skipping ALL database operations..."
echo "🔍 No database connection test"
echo "📊 No database migrations"
echo "⚡ No config caching"

# Start the application
echo "🌐 Starting Laravel server on 0.0.0.0:$PORT"
echo "📱 OmniChain Supply Chain Platform is ready!"
echo "🔗 Access your application at: https://web-production-8c4a.up.railway.app"
php artisan serve --host=0.0.0.0 --port=$PORT --no-reload
