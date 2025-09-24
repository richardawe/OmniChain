#!/bin/bash

# OmniChain Railway Build Script
echo "🔨 Building OmniChain application..."

# Install PHP dependencies
echo "📦 Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader

# Install Node.js dependencies
echo "📦 Installing Node.js dependencies..."
npm ci

# Build frontend assets
echo "🔨 Building frontend assets..."
npm run build

echo "✅ Build completed successfully!"
