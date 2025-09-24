#!/bin/bash

# OmniChain Deployment Script
# This script prepares the application for deployment

echo "🚀 OmniChain Deployment Preparation"
echo "=================================="

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo "❌ Error: Please run this script from the Laravel project root directory"
    exit 1
fi

echo "✅ Laravel project detected"

# Check PHP version
PHP_VERSION=$(php -r "echo PHP_VERSION;")
echo "📋 PHP Version: $PHP_VERSION"

if [[ $(php -r "echo version_compare(PHP_VERSION, '8.2.0', '>=');") == "1" ]]; then
    echo "✅ PHP version is compatible"
else
    echo "❌ PHP 8.2+ required"
    exit 1
fi

# Check Node.js version
if command -v node &> /dev/null; then
    NODE_VERSION=$(node -v)
    echo "📋 Node.js Version: $NODE_VERSION"
    echo "✅ Node.js detected"
else
    echo "❌ Node.js not found"
    exit 1
fi

# Install dependencies
echo "📦 Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader

echo "📦 Installing Node.js dependencies..."
npm ci

echo "🔨 Building frontend assets..."
npm run build

# Generate application key if not exists
if [ -z "$(grep 'APP_KEY=' .env 2>/dev/null | cut -d '=' -f2)" ]; then
    echo "🔑 Generating application key..."
    php artisan key:generate
fi

# Cache configuration for production
echo "⚡ Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Deployment preparation complete!"
echo ""
echo "Next steps:"
echo "1. Push to GitHub: git push origin main"
echo "2. Deploy to Railway: Connect your GitHub repo"
echo "3. Set environment variables in Railway dashboard"
echo "4. Run migrations: railway run php artisan migrate"
echo ""
echo "🚀 Ready for deployment!"
