#!/bin/bash

# Simple startup script for Railway
echo "🚀 Starting OmniChain..."

# Set basic environment if not set
export APP_ENV=${APP_ENV:-production}
export APP_DEBUG=${APP_DEBUG:-false}

# Generate key if not set
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "" ]; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force
fi

# Start the application
echo "🌐 Starting Laravel server on port $PORT..."
php artisan serve --host=0.0.0.0 --port=$PORT
