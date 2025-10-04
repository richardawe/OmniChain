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

# Print environment variables for debugging
echo "MySQL Environment Variables:"
echo "MYSQLHOST: ${MYSQLHOST:-not set}"
echo "MYSQLPORT: ${MYSQLPORT:-not set}"
echo "MYSQLDATABASE: ${MYSQLDATABASE:-not set}"
echo "MYSQLUSER: ${MYSQLUSER:-not set}"
echo "MYSQLPASSWORD: [hidden]"

# Directly write database config to ensure correct values
cat >> .env << EOF

# Railway MySQL Configuration
DB_CONNECTION=mysql
DB_HOST=${MYSQLHOST}
DB_PORT=${MYSQLPORT:-3306}
DB_DATABASE=${MYSQLDATABASE:-railway}
DB_USERNAME=${MYSQLUSER:-root}
DB_PASSWORD=${MYSQLPASSWORD}
EOF

# Wait for MySQL to be ready
echo "Waiting for MySQL connection..."
max_retries=30
counter=0
while ! php -r "try { \$pdo = new PDO('mysql:host=${MYSQLHOST};port=${MYSQLPORT:-3306}', '${MYSQLUSER:-root}', '${MYSQLPASSWORD}'); echo 'connected'; } catch(PDOException \$e) { echo \$e->getMessage(); exit(1); }" 2>/dev/null
do
    if [ $counter -eq $max_retries ]; then
        echo "Failed to connect to MySQL after $max_retries attempts. Continuing anyway..."
        break
    fi
    echo "Waiting for MySQL to be ready... ($counter/$max_retries)"
    sleep 2
    counter=$((counter+1))
done

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

# Run migrations with fallback to SQLite if MySQL fails
echo "Running database migrations..."
if php artisan migrate --force; then
    echo "Migrations completed successfully."
    
    # Seed the database
    echo "Seeding the database..."
    php artisan db:seed --force || echo "Seeding failed but continuing..."
else
    echo "MySQL migrations failed. Setting up SQLite as fallback..."
    
    # Switch to SQLite
    sed -i 's/DB_CONNECTION=mysql/DB_CONNECTION=sqlite/' .env
    echo "DB_DATABASE=$(pwd)/database/database.sqlite" >> .env
    
    # Create SQLite database
    mkdir -p database
    touch database/database.sqlite
    
    # Run migrations on SQLite
    echo "Running migrations on SQLite..."
    php artisan migrate:fresh --force || echo "SQLite migration failed too. Continuing anyway..."
    
    # Seed SQLite database
    echo "Seeding SQLite database..."
    php artisan db:seed --force || echo "SQLite seeding failed. Continuing anyway..."
fi

echo "Railway setup complete!"
