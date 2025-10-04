#!/bin/bash

# Script to update .env file with MySQL configuration for Railway

# Backup current .env
cp .env .env.backup

# Update database connection
sed -i '' 's/DB_CONNECTION=sqlite/DB_CONNECTION=mysql/' .env

# Remove SQLite database path
sed -i '' '/DB_DATABASE=\/Users\/3d7tech\/OmniChain\/omnichain-backend\/database\/database.sqlite/d' .env

# Add MySQL configuration
cat << EOF >> .env

# MySQL Configuration for Railway
MYSQL_DATABASE="railway"
MYSQL_URL="mysql://\${MYSQLUSER}:\${MYSQL_ROOT_PASSWORD}@\${RAILWAY_PRIVATE_DOMAIN}:3306/\${MYSQL_DATABASE}"
MYSQLDATABASE="\${MYSQL_DATABASE}"
MYSQLHOST="\${RAILWAY_PRIVATE_DOMAIN}"
MYSQLPASSWORD="\${MYSQL_ROOT_PASSWORD}"
MYSQLPORT="3306"
MYSQLUSER="root"
EOF

echo "Environment file updated with MySQL configuration for Railway."
echo "Original .env file backed up as .env.backup"
