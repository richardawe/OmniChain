# OmniChain Backend

This is the backend component of the OmniChain supply chain execution platform.

## 🚀 Quick Start Guide

### Prerequisites

- PHP 8.2+
- Node.js 18+ (for frontend assets)
- Composer
- npm or yarn

### Setup Instructions

1. **Install dependencies**
   ```bash
   # Install PHP dependencies
   composer install
   
   # Install JS dependencies
   npm install
   ```

2. **Configure environment**
   ```bash
   # Create a .env file manually with the following content:
   echo "APP_NAME=OmniChain
   APP_ENV=local
   APP_KEY=
   APP_DEBUG=true
   APP_URL=http://localhost:8000
   
   LOG_CHANNEL=stack
   LOG_DEPRECATIONS_CHANNEL=null
   LOG_LEVEL=debug
   
   DB_CONNECTION=sqlite
   DB_DATABASE=$(pwd)/database/database.sqlite
   
   BROADCAST_DRIVER=log
   CACHE_DRIVER=file
   FILESYSTEM_DISK=local
   QUEUE_CONNECTION=sync
   SESSION_DRIVER=file
   SESSION_LIFETIME=120
   
   OPENWEATHER_API_KEY=7ba818bbe65339f2fc489561e114d7be
   OPENROUTE_API_KEY=eyJvcmciOiI1YjNjZTM1OTc4NTExMTAwMDFjZjYyNDgiLCJpZCI6ImM4ZjI4MjJmYWU2MzRiYTZhMjk5NWM0YWI2MGJkMGQ2IiwiaCI6Im11cm11cjY0In0=" > .env
   
   # Generate application key
   php artisan key:generate
   ```

3. **Database setup**
   ```bash
   # Run migrations
   php artisan migrate
   
   # Seed the database with sample data
   php artisan db:seed
   ```

4. **Build frontend assets**
   ```bash
   # Development mode
   npm run dev
   
   # Production build
   npm run build
   ```

## 🚀 Running the Application

### Option 1: Standard Laravel Server (May Have Display Issues)

```bash
# Start the Laravel server
php artisan serve

# In a separate terminal, start the Vite dev server
npm run dev
```

Access the application at: http://localhost:8000

### Option 2: Fixed Server (Recommended)

Due to an output buffering issue that causes blank screens in some environments, we recommend using this method:

```bash
# Start the PHP server with our fix script
php -S localhost:8004 public/soh_fix.php

# In a separate terminal, start the Vite dev server
npm run dev
```

Access the application at: http://localhost:8004

## 🔑 Default Login Credentials

### Main Dashboard
- **URL**: `http://localhost:8004`
- **Admin Access**: Available without authentication for demo purposes

### Driver App
- **URL**: `http://localhost:8004/driver/login`
- **Email**: `driver@omnichain.com`
- **Password**: `password`

Additional test drivers:
- **Email**: `sarah@omnichain.com` / **Password**: `password`
- **Email**: `mike@omnichain.com` / **Password**: `password`

## 📱 Driver App Features

### PWA Installation
1. Open `http://localhost:8004/driver/login` in mobile browser
2. Tap "Add to Home Screen" when prompted
3. App installs as native-like PWA

### Location Tracking
- **Automatic**: Starts when driver marks "Picked Up"
- **Permission Required**: Browser location access
- **Real-time Updates**: Every 30 seconds during delivery

## 🗺️ Dashboard Features

### Real-time Tracking
- **Live Driver Locations**: Pulsing markers on map
- **Animated Routes**: 30-second delivery animations
- **Weather Overlay**: Current conditions and forecasts
- **Route Intelligence**: Optimized routing with recommendations

### Order Management
- **Driver Assignment**: View assigned drivers in Orders tab
- **Status Tracking**: Real-time status updates
- **Location Management**: Geospatial company and location data

## 🐛 Troubleshooting

### Common Issues

#### 1. Blank Screen Issue
- **Solution**: Use the fixed server option with `php -S localhost:8004 public/soh_fix.php`
- **Cause**: Output buffering issue with PHP/Laravel

#### 2. "Failed to delete and flush buffer" Error
- **Solution**: Restart the PHP server
- **Cause**: Conflict in output buffering between Laravel and our fix script

#### 3. Database Connection Issues
- **Solution**: Check that your .env file has the correct DB_DATABASE path
- **Alternative**: Run `php artisan migrate:fresh --seed` to reset the database

#### 4. API Keys Not Working
- **Verify**: Keys are correctly added to `.env` file
- **Check**: Clear config cache: `php artisan config:clear`
- **Test**: API endpoints directly with curl

### Debug Mode

Enable debug mode in `.env`:
```env
APP_DEBUG=true
LOG_LEVEL=debug
```

Check logs:
```bash
tail -f storage/logs/laravel.log
```

## 📊 Performance Optimization

1. **Enable Redis**: For better caching performance
2. **Queue Jobs**: Use background processing for heavy tasks
3. **Asset Optimization**: Run `npm run build` for production
4. **Database Indexing**: Ensure proper indexes on location and order tables

## 🚀 Deployment

### Railway Deployment

To deploy on Railway:

1. Connect your GitHub repository to Railway
2. Set the following environment variables:
   - `APP_ENV=production`
   - `APP_DEBUG=false`
   - `APP_URL=your-railway-url`
   - `DB_CONNECTION=sqlite` (or your preferred database)
   - Other environment variables as needed
3. Add the following build commands:
   ```
   composer install --no-dev --optimize-autoloader && 
   php artisan config:cache && 
   php artisan route:cache && 
   php artisan view:cache && 
   npm ci && 
   npm run build
   ```
4. Set the start command to: `php -S 0.0.0.0:$PORT public/soh_fix.php`

---

**Happy Shipping! 🚛📦**