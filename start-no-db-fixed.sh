#!/bin/bash

# OmniChain startup script without database dependencies
echo "🚀 Starting OmniChain without database dependencies..."

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

# Skip database operations for now
echo "⚠️  Skipping database operations due to connection issues..."

# Start the application
echo "🌐 Starting Laravel server on 0.0.0.0:$PORT"
echo "📱 OmniChain Supply Chain Platform is ready!"
echo "🔗 Access your application at: https://web-production-8c4a.up.railway.app"
php artisan serve --host=0.0.0.0 --port=$PORT --no-reload
