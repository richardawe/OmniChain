#!/bin/bash

# Ultra-simple startup script for Railway
echo "🚀 Starting simple web server..."

# Set default port if not provided
export PORT=${PORT:-8000}

# Create a simple router for the public directory
echo "📝 Creating simple router..."
cat > /app/public/router.php << 'EOF'
<?php
// Simple router for Railway
$uri = $_SERVER['REQUEST_URI'];
$path = parse_url($uri, PHP_URL_PATH);

// Remove leading slash
$path = ltrim($path, '/');

// If path is empty or just '/', serve index.php
if (empty($path) || $path === '/') {
    include __DIR__ . '/index.php';
    return;
}

// If file exists, serve it
$file = __DIR__ . '/' . $path;
if (file_exists($file) && is_file($file)) {
    return false; // Let PHP serve the file
}

// Default to index.php for all other requests
include __DIR__ . '/index.php';
EOF

# Start PHP built-in server with router
echo "🌐 Starting PHP server on 0.0.0.0:$PORT"
cd /app/public
php -S 0.0.0.0:$PORT router.php
