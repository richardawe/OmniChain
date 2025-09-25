#!/bin/bash

# Ultra-simple Laravel startup without any database operations
echo "🚀 Starting OmniChain Laravel application (simple mode)..."

# Set default port if not provided
export PORT=${PORT:-8000}

# Create .env file with correct environment variables
echo "📝 Setting up environment variables..."
./set-env.sh

# Set application environment
export APP_ENV=production
export APP_DEBUG=false
export APP_URL=https://web-production-8c4a.up.railway.app
export APP_NAME="OmniChain"

# Set cache and session drivers (use Redis if available, fallback to file)
if [ ! -z "$REDIS_URL" ]; then
    export CACHE_DRIVER=redis
    export SESSION_DRIVER=redis
    export QUEUE_CONNECTION=redis
    echo "✅ Using Redis for caching and sessions"
else
    export CACHE_DRIVER=file
    export SESSION_DRIVER=file
    export QUEUE_CONNECTION=sync
    echo "⚠️  Using file-based caching (Redis not available)"
fi

# Set API keys
export OPENROUTE_API_KEY="eyJvcmciOiI1YjNjZTM1OTc4NTExMTAwMDFjZjYyNDgiLCJpZCI6ImM4ZjI4MjJmYWU2MzRiYTZhMjk5NWM0YWI2MGJkMGQ2IiwiaCI6Im11cm11cjY0In0="
export OPENWEATHER_API_KEY="7ba818bbe65339f2fc489561e114d7be"

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

# Test database connection if available
if [ ! -z "$DATABASE_URL" ]; then
    echo "🔍 Testing database connection with Railway variables..."
    php artisan tinker --execute="try { DB::connection()->getPdo(); echo 'Database: Connected via Railway'; } catch (Exception \$e) { echo 'Database Error: ' . \$e->getMessage(); }" || echo "⚠️  Database connection failed, continuing without database"
    
    echo "📊 Running database migrations..."
    php artisan migrate --force || echo "⚠️  Migrations failed, continuing without database"
else
    echo "⚠️  No DATABASE_URL found, skipping database operations..."
fi

# Start the application
echo "🌐 Starting Laravel server on 0.0.0.0:$PORT"
echo "📱 OmniChain Supply Chain Platform is ready!"
echo "🔗 Access your application at: https://web-production-8c4a.up.railway.app"
php artisan serve --host=0.0.0.0 --port=$PORT --no-reload
