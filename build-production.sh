#!/bin/bash

# Production build script for Railway
echo "🏗️  Building OmniChain for production..."

# Install dependencies
echo "📦 Installing dependencies..."
composer install --no-dev --optimize-autoloader
npm ci

# Build frontend assets
echo "🎨 Building frontend assets..."
npm run build

# Verify build
if [ -d "/app/public/build" ]; then
    echo "✅ Frontend assets built successfully"
    ls -la /app/public/build/
else
    echo "❌ Frontend assets build failed"
    exit 1
fi

# Cache Laravel assets
echo "⚡ Caching Laravel assets..."
php artisan view:cache
php artisan route:cache

echo "✅ Production build complete!"
