# Error Handling Guide

This guide explains the comprehensive error handling system implemented in the OmniChain application.

## Backend Error Handling (Laravel)

### Exception Handler
The main exception handler is located in `app/Exceptions/Handler.php` and provides:

- **Standardized JSON responses** for API requests
- **Detailed error logging** with context information
- **Specific handling** for different exception types
- **Debug information** in development mode

### Error Types Handled

#### 1. Validation Errors (422)
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "field_name": ["The field is required."]
  },
  "status_code": 422,
  "request_id": "req_abc123_1234567890"
}
```

#### 2. Authentication Errors (401)
```json
{
  "success": false,
  "message": "Unauthenticated",
  "status_code": 401,
  "request_id": "req_abc123_1234567890"
}
```

#### 3. Authorization Errors (403)
```json
{
  "success": false,
  "message": "Forbidden",
  "status_code": 403,
  "request_id": "req_abc123_1234567890"
}
```

#### 4. Not Found Errors (404)
```json
{
  "success": false,
  "message": "Resource not found",
  "status_code": 404,
  "request_id": "req_abc123_1234567890"
}
```

#### 5. Server Errors (500)
```json
{
  "success": false,
  "message": "Internal Server Error",
  "status_code": 500,
  "request_id": "req_abc123_1234567890",
  "debug": {
    "exception": "Exception",
    "file": "/path/to/file.php",
    "line": 123,
    "trace": "..."
  }
}
```

### Logging
All errors are logged with detailed context:
- Exception type and message
- File and line number
- Stack trace
- Request information (URL, method, IP, user agent)
- User information (if authenticated)

## Frontend Error Handling (Vue.js)

### Global Error Handler
Located in `resources/js/utils/errorHandler.js`, provides:

- **Global error catching** for unhandled errors
- **API error handling** with user-friendly messages
- **Notification system** integration
- **Error logging** for debugging

### Error Boundary Component
The `ErrorBoundary.vue` component provides:

- **Component error catching** for Vue components
- **User-friendly error display** with retry options
- **Error reporting** functionality
- **Graceful fallback** UI

### Axios Error Handling
Enhanced axios instance in `resources/js/utils/axiosErrorHandler.js`:

- **Automatic error handling** for all API requests
- **Request/response interceptors** for consistent error handling
- **Retry mechanism** for failed requests
- **Timeout handling** for slow requests

## Error Handling Patterns

### 1. API Error Handling
```javascript
import { apiUtils } from '@/utils/axiosErrorHandler'

try {
  const data = await apiUtils.get('/freight-orders')
  // Handle success
} catch (error) {
  // Error is automatically handled and user notified
  console.error('API Error:', error)
}
```

### 2. Component Error Handling
```vue
<template>
  <ErrorBoundary @error="handleComponentError">
    <YourComponent />
  </ErrorBoundary>
</template>

<script setup>
const handleComponentError = (error, info) => {
  console.error('Component error:', error, info)
  // Custom error handling logic
}
</script>
```

### 3. Global Error Handling
```javascript
import errorHandler from '@/utils/errorHandler'

// Handle specific error types
errorHandler.handleApiError(error, 'Custom Context')
errorHandler.handleNetworkError()
errorHandler.handleTimeoutError()
```

## Error Types and Responses

### Client-Side Errors
- **Network Errors**: Connection issues, timeouts
- **Validation Errors**: Form validation failures
- **Authentication Errors**: Login required, session expired
- **Authorization Errors**: Insufficient permissions
- **Application Errors**: Unhandled exceptions, component errors

### Server-Side Errors
- **Validation Errors**: Request data validation failures
- **Authentication Errors**: Invalid credentials, expired tokens
- **Authorization Errors**: Insufficient permissions
- **Not Found Errors**: Missing resources
- **Server Errors**: Internal server issues, database errors

## Error Monitoring and Logging

### Backend Logging
- **Laravel Log**: All errors logged to `storage/logs/laravel.log`
- **Context Information**: Request details, user info, stack traces
- **Error Classification**: Different log levels for different error types

### Frontend Logging
- **Console Logging**: Development error logging
- **Error Reporting**: User-reported errors (when implemented)
- **Performance Monitoring**: Error impact on user experience

## Best Practices

### 1. Error Prevention
- **Input Validation**: Validate all user inputs
- **Type Checking**: Use TypeScript for type safety
- **Defensive Programming**: Handle edge cases gracefully

### 2. Error Handling
- **Graceful Degradation**: Provide fallback functionality
- **User-Friendly Messages**: Clear, actionable error messages
- **Error Recovery**: Allow users to retry failed operations

### 3. Error Reporting
- **Comprehensive Logging**: Log all relevant context
- **Error Classification**: Categorize errors by type and severity
- **Performance Impact**: Monitor error impact on performance

### 4. User Experience
- **Clear Messaging**: Explain what went wrong and how to fix it
- **Recovery Options**: Provide retry, refresh, or alternative actions
- **Progress Indication**: Show loading states and progress

## Configuration

### Environment Variables
```env
# Error handling configuration
APP_DEBUG=true
LOG_LEVEL=debug
LOG_CHANNEL=stack

# Error reporting (when implemented)
SENTRY_DSN=your-sentry-dsn
BUGSNAG_API_KEY=your-bugsnag-key
```

### Frontend Configuration
```javascript
// Error handling configuration
const errorConfig = {
  enableGlobalErrorHandling: true,
  enableErrorBoundary: true,
  enableErrorReporting: false,
  logLevel: 'error'
}
```

## Testing Error Handling

### Backend Testing
```php
// Test exception handling
public function test_handles_validation_errors()
{
    $response = $this->postJson('/api/v1/freight-orders', []);
    
    $response->assertStatus(422)
             ->assertJsonStructure([
                 'success',
                 'message',
                 'errors',
                 'status_code',
                 'request_id'
             ]);
}
```

### Frontend Testing
```javascript
// Test error boundary
import { mount } from '@vue/test-utils'
import ErrorBoundary from '@/Components/ErrorBoundary.vue'

test('ErrorBoundary catches component errors', () => {
  const wrapper = mount(ErrorBoundary)
  
  // Simulate component error
  wrapper.vm.handleError(new Error('Test error'))
  
  expect(wrapper.find('.error-boundary').exists()).toBe(true)
})
```

## Monitoring and Alerting

### Error Metrics
- **Error Rate**: Percentage of failed requests
- **Error Types**: Distribution of error types
- **Error Impact**: User experience impact
- **Recovery Time**: Time to resolve errors

### Alerting
- **Critical Errors**: Immediate alerts for system failures
- **Error Trends**: Alerts for increasing error rates
- **Performance Impact**: Alerts for error-related performance issues

## Troubleshooting

### Common Issues
1. **Silent Failures**: Check error handling implementation
2. **User Confusion**: Improve error messages and recovery options
3. **Performance Impact**: Optimize error handling overhead
4. **Debugging Difficulties**: Enhance error logging and context

### Debug Tools
- **Laravel Telescope**: Request and error monitoring
- **Browser DevTools**: Frontend error debugging
- **Error Tracking Services**: Sentry, Bugsnag integration
- **Log Analysis**: Centralized log analysis and monitoring

This comprehensive error handling system ensures a robust, user-friendly experience while providing developers with the tools needed to diagnose and resolve issues quickly.
