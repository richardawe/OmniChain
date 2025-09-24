#!/bin/bash

# Railway startup script for OmniChain
echo "🚀 Starting OmniChain on Railway..."

# Set default port if not provided
export PORT=${PORT:-8000}

# Generate application key if not set
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "" ]; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force
fi

# Start the application
echo "🌐 Starting Laravel server on 0.0.0.0:$PORT"
php artisan serve --host=0.0.0.0 --port=$PORT --no-reload
