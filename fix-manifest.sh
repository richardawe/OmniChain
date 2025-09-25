#!/bin/bash

# Fix Vite manifest location for Laravel
# This script ensures the manifest.json file is in the correct location

echo "🔧 Fixing Vite manifest location..."

# Check if .vite directory exists and has manifest
if [ -f "public/build/.vite/manifest.json" ]; then
    echo "📋 Found manifest in .vite directory, copying to build root..."
    cp public/build/.vite/manifest.json public/build/manifest.json
    echo "✅ Manifest file copied successfully!"
else
    echo "❌ No manifest file found in .vite directory"
    exit 1
fi

# Verify the file exists
if [ -f "public/build/manifest.json" ]; then
    echo "✅ Manifest file is now in the correct location: public/build/manifest.json"
    echo "📊 File size: $(wc -c < public/build/manifest.json) bytes"
else
    echo "❌ Failed to create manifest file"
    exit 1
fi

echo "🎉 Manifest fix completed!"
