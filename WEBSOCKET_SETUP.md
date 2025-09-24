# WebSocket Setup Guide

This guide explains how to set up real-time WebSocket functionality using Pusher for the OmniChain application.

## Environment Configuration

Add the following environment variables to your `.env` file:

```env
# WebSocket Configuration (Pusher)
BROADCAST_CONNECTION=pusher
PUSHER_APP_ID=your-pusher-app-id
PUSHER_APP_KEY=your-pusher-app-key
PUSHER_APP_SECRET=your-pusher-app-secret
PUSHER_APP_CLUSTER=mt1
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
```

## Frontend Environment Variables

Add these to your frontend `.env` file:

```env
VITE_PUSHER_APP_KEY=your-pusher-app-key
VITE_PUSHER_APP_CLUSTER=mt1
```

## Pusher Setup

1. **Create a Pusher Account**: Go to [pusher.com](https://pusher.com) and create an account
2. **Create a New App**: In your Pusher dashboard, create a new app
3. **Get Your Credentials**: Copy the App ID, Key, Secret, and Cluster from your Pusher app settings
4. **Update Environment Variables**: Add the credentials to your `.env` file

## Features

### Real-time Updates
- **Freight Orders**: Live updates when orders are created, updated, or status changes
- **Manufacturing**: Real-time work order, machine, and quality inspection updates
- **Inventory**: Live inventory balance, warehouse bin, and receiving updates
- **Delivery**: Real-time delivery task, notification, and exception updates
- **Returns**: Live return request, authorization, and processing updates
- **Suppliers**: Real-time supplier, contract, and performance updates

### Channel Types
- **Public Channels**: Available to all authenticated users
  - `freight-orders`
  - `manufacturing`
  - `inventory`
  - `delivery`
  - `returns`
  - `suppliers`
  - `notifications`
  - `tracking`
  - `alerts`

- **User-Specific Channels**: `user-{userId}`
- **Company-Specific Channels**: `company-{companyId}`

### Event Types
- `order-updated` - Freight order updates
- `manufacturing-updated` - Manufacturing updates
- `inventory-updated` - Inventory updates
- `delivery-updated` - Delivery updates
- `return-updated` - Return updates
- `supplier-updated` - Supplier updates
- `notification` - System notifications
- `location-updated` - Location tracking
- `system-alert` - System alerts

## Usage

### Backend (Laravel)

```php
use App\Services\WebSocketService;

// Broadcast a freight order update
$websocketService = new WebSocketService();
$websocketService->broadcastFreightOrderUpdate([
    'id' => $order->id,
    'status' => $order->status,
    'updated_at' => $order->updated_at
]);
```

### Frontend (Vue.js)

```javascript
import { useWebSocket } from '@/composables/useWebSocket'

// In your Vue component
const { subscribeToFreightOrders, isConnected } = useWebSocket()

// Subscribe to freight order updates
subscribeToFreightOrders((data) => {
  console.log('Freight order updated:', data)
  // Update your UI here
})
```

### Using the WebSocket Store

```javascript
import { useWebSocketStore } from '@/stores/websocket'

const websocketStore = useWebSocketStore()

// Initialize connection
await websocketStore.initialize()

// Subscribe to updates
websocketStore.subscribeToFreightOrders((data) => {
  // Handle update
})

// Disconnect when done
websocketStore.disconnect()
```

## Security

- **Authentication Required**: All WebSocket connections require user authentication
- **Channel Access Control**: Users can only access channels they have permission for
- **Rate Limiting**: Broadcasting authentication is rate-limited to prevent abuse
- **CSRF Protection**: All requests include CSRF tokens

## Testing

To test WebSocket functionality:

1. **Start the Laravel server**: `php artisan serve`
2. **Open browser console**: Check for WebSocket connection status
3. **Trigger updates**: Create/update records to see real-time updates
4. **Check network tab**: Verify WebSocket connections in browser dev tools

## Troubleshooting

### Common Issues

1. **Connection Failed**: Check Pusher credentials and network connectivity
2. **Authentication Failed**: Verify user is logged in and has proper permissions
3. **No Updates**: Check if the correct channels are subscribed to
4. **Rate Limiting**: Reduce the frequency of updates if hitting rate limits

### Debug Mode

Enable debug logging by adding to your `.env`:

```env
LOG_LEVEL=debug
```

This will log all WebSocket events and help identify issues.

## Performance Considerations

- **Connection Limits**: Pusher has connection limits based on your plan
- **Message Size**: Keep WebSocket messages small for better performance
- **Frequency**: Don't broadcast too frequently to avoid overwhelming clients
- **Cleanup**: Always unsubscribe from channels when components are destroyed

## Monitoring

Monitor WebSocket usage in your Pusher dashboard:
- Connection counts
- Message throughput
- Error rates
- Channel activity

This helps identify performance issues and optimize your real-time features.
