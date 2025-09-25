#!/bin/bash

# Ultra-simple startup script for Railway
echo "🚀 Starting simple web server..."

# Set default port if not provided
export PORT=${PORT:-8000}

# Start PHP built-in server serving the public directory
echo "🌐 Starting PHP server on 0.0.0.0:$PORT"
cd /app/public
php -S 0.0.0.0:$PORT
