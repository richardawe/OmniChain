#!/bin/bash

# Railway setup script for OmniChain
# This script sets up the environment for Railway deployment

echo "Starting Railway setup for OmniChain..."

# Create .env file from example
if [ -f .env.example ]; then
  echo "Creating .env file from .env.example..."
  cp -f .env.example .env
else
  echo "No .env.example found. Creating minimal .env file..."
  cat > .env << EOF
APP_NAME=OmniChain
APP_ENV=production
APP_KEY=
APP_DEBUG=true
APP_URL=https://web-production-8c4a.up.railway.app
VITE_APP_URL=https://web-production-8c4a.up.railway.app
DB_CONNECTION=mysql
EOF
fi

# Update database configuration
echo "Updating database configuration..."
sed -i 's/DB_CONNECTION=sqlite/DB_CONNECTION=mysql/' .env
sed -i "s/DB_HOST=127.0.0.1/DB_HOST=$MYSQLHOST/" .env
sed -i "s/DB_PORT=3306/DB_PORT=$MYSQLPORT/" .env
sed -i "s/DB_DATABASE=forge/DB_DATABASE=$MYSQLDATABASE/" .env
sed -i "s/DB_USERNAME=forge/DB_USERNAME=$MYSQLUSER/" .env
sed -i "s/DB_PASSWORD=/DB_PASSWORD=$MYSQLPASSWORD/" .env

# Generate application key
echo "Generating application key..."
php artisan key:generate --force

# Create storage directory symlink
echo "Creating storage symlink..."
php artisan storage:link || true

# Clear caches
echo "Clearing caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Run migrations
echo "Running database migrations..."
php artisan migrate --force || echo "Migration failed but continuing..."

# Seed the database
echo "Seeding the database..."
php artisan db:seed --force || echo "Seeding failed but continuing..."

echo "Railway setup complete!"
