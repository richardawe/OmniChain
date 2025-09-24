#!/bin/bash

# OmniChain Railway startup script without database dependency
echo "🚀 Starting OmniChain (no database mode)..."

# Set default port if not provided
export PORT=${PORT:-8000}

# Generate application key if not set
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "" ]; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force
fi

# Set database to sqlite for testing
export DB_CONNECTION=sqlite
export DB_DATABASE=/app/database/database.sqlite

# Create sqlite database file if it doesn't exist
touch /app/database/database.sqlite

# Start the application
echo "🌐 Starting Laravel server on 0.0.0.0:$PORT"
php artisan serve --host=0.0.0.0 --port=$PORT --no-reload
