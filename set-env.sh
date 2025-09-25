#!/bin/bash

# Set environment variables for Railway deployment
echo "🔧 Setting environment variables for Railway..."

# Create .env file with correct values
cat > /app/.env << EOF
APP_NAME="OmniChain"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://web-production-8c4a.up.railway.app

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=pgsql
DB_HOST=turntable.proxy.rlwy.net
DB_PORT=54435
DB_DATABASE=railway
DB_USERNAME=postgres
DB_PASSWORD=nqHVYqKxKaBcZPPZzErDHTPZBFRUsyWR

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

REDIS_HOST=hopper.proxy.rlwy.net
REDIS_PASSWORD=gOxwGrzjoBeqwHHGFWAFqgRPnZDutALU
REDIS_PORT=11128

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="OmniChain"

VITE_APP_NAME="OmniChain"
VITE_PUSHER_APP_KEY=""
VITE_PUSHER_HOST=""
VITE_PUSHER_PORT="443"
VITE_PUSHER_SCHEME="https"
VITE_PUSHER_APP_CLUSTER="mt1"

# External API Keys
OPENROUTE_API_KEY="eyJvcmciOiI1YjNjZTM1OTc4NTExMTAwMDFjZjYyNDgiLCJpZCI6ImM4ZjI4MjJmYWU2MzRiYTZhMjk5NWM0YWI2MGJkMGQ2IiwiaCI6Im11cm11cjY0In0="
OPENWEATHER_API_KEY="7ba818bbe65339f2fc489561e114d7be"
EOF

echo "✅ Environment variables set successfully!"
