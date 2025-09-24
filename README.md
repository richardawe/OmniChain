# OmniChain - Unified Supply Chain Execution Platform

A comprehensive supply chain management platform with real-time tracking, driver management, weather integration, and route optimization.

## 🚀 Features

- **Real-time Shipment Tracking** with interactive maps
- **Driver Mobile App (PWA)** with location tracking and offline capabilities
- **Weather & Route Intelligence** using OpenWeatherMap and OpenRouteService APIs
- **Freight Order Management** with carrier assignment
- **Company & Location Management** with geospatial data
- **Animated Route Visualization** for active deliveries
- **Responsive Dashboard** with real-time updates

## 📋 Prerequisites

- **PHP 8.2+**
- **Node.js 20.19+ or 22.12+**
- **Composer**
- **PostgreSQL with PostGIS extension**
- **Redis** (optional, for caching)

## 🛠️ Installation

### 1. Clone the Repository

```bash
git clone <repository-url>
cd omnichain-backend
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Node.js Dependencies

```bash
npm install
```

### 4. Environment Configuration

Copy the environment file and configure your settings:

```bash
cp .env.example .env
```

Edit `.env` file with your configuration:

```env
APP_NAME=OmniChain
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=omnichain
DB_USERNAME=your_username
DB_PASSWORD=your_password

# External APIs (Required for Weather & Route Intelligence)
OPENWEATHER_API_KEY=your_openweather_api_key
OPENROUTE_API_KEY=your_openroute_api_key

# Redis (Optional)
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Database Setup

Create PostgreSQL database with PostGIS extension:

```sql
CREATE DATABASE omnichain;
\c omnichain;
CREATE EXTENSION postgis;
```

Run migrations:

```bash
php artisan migrate
```

### 7. Seed Database

```bash
php artisan db:seed
```

This will create:
- Sample companies and locations
- Test driver accounts
- Sample freight orders
- Weather and logistics test data

## 🚀 Running the Application

### 1. Start Laravel Server

```bash
php artisan serve
```

The application will be available at `http://localhost:8000`

### 2. Build Frontend Assets

For development:

```bash
npm run dev
```

For production:

```bash
npm run build
```

### 3. Start Queue Worker (Optional)

For background job processing:

```bash
php artisan queue:work
```

## 🔑 Default Login Credentials

### Main Dashboard
- **URL**: `http://localhost:8000`
- **Admin Access**: Available without authentication for demo purposes

### Driver App
- **URL**: `http://localhost:8000/driver/login`
- **Email**: `driver@omnichain.com`
- **Password**: `password`

Additional test drivers:
- **Email**: `sarah@omnichain.com` / **Password**: `password`
- **Email**: `mike@omnichain.com` / **Password**: `password`

## 📱 Driver App Features

### PWA Installation
1. Open `http://localhost:8000/driver/login` in mobile browser
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

## 🌐 API Endpoints

### Authentication
```
POST /api/v1/auth/login
POST /api/v1/auth/logout
GET  /api/v1/auth/me
```

### Freight Orders
```
GET    /api/v1/freight-orders
POST   /api/v1/freight-orders
GET    /api/v1/freight-orders/{id}
PUT    /api/v1/freight-orders/{id}
DELETE /api/v1/freight-orders/{id}
GET    /api/v1/freight-orders/track/{orderNumber}
```

### Driver API
```
GET  /api/v1/driver/profile
PUT  /api/v1/driver/profile
GET  /api/v1/driver/tasks
POST /api/v1/driver/orders/{id}/claim
PUT  /api/v1/driver/tasks/{id}/status
POST /api/v1/driver/location
GET  /api/v1/driver-locations
```

### Logistics & Weather
```
GET  /api/v1/logistics/freight-orders/{id}
GET  /api/v1/logistics/weather/{id}
POST /api/v1/logistics/optimize-route
POST /api/v1/logistics/multiple-weather
POST /api/v1/logistics/weather-aware-route
```

## 🧪 Testing the Application

### 1. Create a Freight Order
1. Go to main dashboard → "Orders" tab
2. Click "Create Order"
3. Fill in order details
4. Save order (status: "booked")

### 2. Driver Claims Order
1. Open driver app: `http://localhost:8000/driver/login`
2. Login with `driver@omnichain.com` / `password`
3. Click "Claim Order" on available order
4. Order moves to "Pending" status

### 3. Start Delivery Animation
1. In driver app, click "Mark Picked Up"
2. Return to main dashboard
3. Enable "Show Drivers" on map
4. Watch animated route visualization

### 4. View Driver Details
1. Go to "Orders" tab in main dashboard
2. See driver information in "Driver" column
3. Real-time updates every 30 seconds

## 🔧 Configuration

### External APIs Setup

#### OpenWeatherMap API
1. Sign up at [OpenWeatherMap](https://openweathermap.org/api)
2. Get free API key
3. Add to `.env`: `OPENWEATHER_API_KEY=your_key`

#### OpenRouteService API
1. Sign up at [OpenRouteService](https://openrouteservice.org/)
2. Get free API key
3. Add to `.env`: `OPENROUTE_API_KEY=your_key`

### Database Configuration

Ensure PostgreSQL has PostGIS extension:

```sql
-- Check if PostGIS is installed
SELECT PostGIS_version();

-- Install PostGIS if needed
CREATE EXTENSION postgis;
```

## 🐛 Troubleshooting

### Common Issues

#### 1. Driver Column Shows "Unassigned"
- **Solution**: Refresh the page or wait for auto-refresh (30 seconds)
- **Cause**: Frontend loading stale data from server-side rendering

#### 2. Location Permission Denied
- **Solution**: Enable location access in browser settings
- **Alternative**: Driver app continues to work without location tracking

#### 3. Animated Route Not Showing
- **Check**: Browser console for error messages
- **Verify**: "Show Drivers" checkbox is enabled
- **Ensure**: Driver has active delivery task

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

## 📊 Performance

### Optimization Tips

1. **Enable Redis**: For better caching performance
2. **Queue Jobs**: Use background processing for heavy tasks
3. **Asset Optimization**: Run `npm run build` for production
4. **Database Indexing**: Ensure proper indexes on location and order tables

### Monitoring

- **Real-time Updates**: Every 30 seconds
- **Location Tracking**: GPS accuracy dependent
- **API Rate Limits**: Respect external API limits

## 🤝 Contributing

1. Fork the repository
2. Create feature branch: `git checkout -b feature-name`
3. Commit changes: `git commit -am 'Add feature'`
4. Push branch: `git push origin feature-name`
5. Submit pull request

## 📄 License

This project is licensed under the MIT License.

## 🚀 Deployment

### Quick Deploy to Railway

1. **Prepare for deployment:**
   ```bash
   ./deploy.sh
   ```

2. **Push to GitHub:**
   ```bash
   git add .
   git commit -m "Deploy to Railway"
   git push origin main
   ```

3. **Deploy to Railway:**
   - Go to [Railway](https://railway.app)
   - Connect your GitHub repository
   - Add PostgreSQL and Redis services
   - Set environment variables (see `.env.example`)
   - Deploy!

### Environment Variables for Production

Set these in Railway dashboard:

```env
APP_NAME=OmniChain
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app.railway.app

# Database (auto-configured by Railway)
DB_CONNECTION=pgsql
DB_HOST=${{Postgres.PGHOST}}
DB_PORT=${{Postgres.PGPORT}}
DB_DATABASE=${{Postgres.PGDATABASE}}
DB_USERNAME=${{Postgres.PGUSER}}
DB_PASSWORD=${{Postgres.PGPASSWORD}}

# Redis (auto-configured by Railway)
REDIS_HOST=${{Redis.REDIS_HOST}}
REDIS_PORT=${{Redis.REDIS_PORT}}
REDIS_PASSWORD=${{Redis.REDIS_PASSWORD}}

# External APIs
OPENWEATHER_API_KEY=your_openweather_api_key
OPENROUTE_API_KEY=your_openroute_api_key

# Production settings
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

### Post-Deployment Commands

```bash
# Run migrations
railway run php artisan migrate

# Seed database (optional)
railway run php artisan db:seed

# Cache configuration
railway run php artisan config:cache
```

## 🆘 Support

For issues and questions:
1. Check this README
2. Review browser console for errors
3. Check Laravel logs in `storage/logs/`
4. Verify API keys and database connection
5. See `DEPLOYMENT_GUIDE.md` for detailed deployment instructions

---

**Happy Shipping! 🚛📦**