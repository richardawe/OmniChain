#!/bin/bash

# Ultra-simple Laravel startup without any database operations
echo "🚀 Starting OmniChain Laravel application (simple mode)..."

# Set default port if not provided
export PORT=${PORT:-8000}

# Set Railway environment variables directly
echo "📝 Setting up Railway environment variables..."

# Database configuration from Railway
if [ ! -z "$RAILWAY_PRIVATE_DOMAIN" ]; then
    export DB_CONNECTION=pgsql
    export DB_HOST=$RAILWAY_PRIVATE_DOMAIN
    export DB_PORT=5432
    export DB_DATABASE=$POSTGRES_DB
    export DB_USERNAME=$POSTGRES_USER
    export DB_PASSWORD=$POSTGRES_PASSWORD
    echo "✅ Database configured with Railway variables"
else
    echo "⚠️  Railway database variables not found"
fi

# Redis configuration from Railway
if [ ! -z "$RAILWAY_PRIVATE_DOMAIN" ] && [ ! -z "$REDIS_PASSWORD" ]; then
    export REDIS_HOST=$RAILWAY_PRIVATE_DOMAIN
    export REDIS_PORT=6379
    export REDIS_PASSWORD=$REDIS_PASSWORD
    echo "✅ Redis configured with Railway variables"
else
    echo "⚠️  Railway Redis variables not found"
fi

# Create basic .env file
echo "📝 Creating basic .env file..."
cat > /app/.env << EOF
APP_NAME="OmniChain"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://web-production-8c4a.up.railway.app

LOG_CHANNEL=stack
LOG_LEVEL=debug

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Database configuration (disabled for now)
DB_CONNECTION=sqlite
DB_DATABASE=/app/database/database.sqlite

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="OmniChain"

VITE_APP_NAME="OmniChain"

# External API Keys
OPENROUTE_API_KEY="eyJvcmciOiI1YjNjZTM1OTc4NTExMTAwMDFjZjYyNDgiLCJpZCI6ImM4ZjI4MjJmYWU2MzRiYTZhMjk5NWM0YWI2MGJkMGQ2IiwiaCI6Im11cm11cjY0In0="
OPENWEATHER_API_KEY="7ba818bbe65339f2fc489561e114d7be"
EOF

# Set application environment
export APP_ENV=production
export APP_DEBUG=false
export APP_URL=https://web-production-8c4a.up.railway.app
export APP_NAME="OmniChain"

# Force file-based caching to avoid database dependency
export CACHE_DRIVER=file
export SESSION_DRIVER=file
export QUEUE_CONNECTION=sync
echo "✅ Using file-based caching (avoiding database dependency)"

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
else
    echo "✅ Frontend assets found"
    ls -la /app/public/build/
fi

# Check if Vite manifest exists
if [ -f "/app/public/build/manifest.json" ]; then
    echo "✅ Vite manifest found"
    cat /app/public/build/manifest.json
else
    echo "⚠️  Vite manifest not found"
fi

# Create SQLite database for basic functionality
echo "📊 Creating SQLite database for basic functionality..."
mkdir -p /app/database
touch /app/database/database.sqlite
echo "✅ SQLite database created"

# Skip database operations to focus on frontend
echo "⚠️  Skipping database operations to focus on frontend functionality..."
echo "🔍 Database connection disabled"
echo "📊 Database migrations skipped"
echo "💡 Frontend will work without database dependency"

# Start the application
echo "🌐 Starting Laravel server on 0.0.0.0:$PORT"
echo "📱 OmniChain Supply Chain Platform is ready!"
echo "🔗 Access your application at: https://web-production-8c4a.up.railway.app"
php artisan serve --host=0.0.0.0 --port=$PORT --no-reload
