# Audit Logging System

This document describes the comprehensive audit logging system implemented in the OmniChain application.

## Overview

The audit logging system tracks all sensitive operations and changes to critical data, providing a complete audit trail for compliance, security, and troubleshooting purposes.

## Features

### Automatic Logging
- **Model Changes**: Automatically logs create, update, and delete operations on auditable models
- **API Requests**: Logs all sensitive API requests through middleware
- **Authentication Events**: Tracks login, logout, and failed authentication attempts
- **User Actions**: Records user-initiated actions and status changes

### Manual Logging
- **Custom Events**: Log specific business events with context
- **Approval/Rejection**: Track approval workflows
- **Status Changes**: Monitor state transitions
- **Sensitive Operations**: Log high-security operations

## Configuration

### Environment Variables

Add these to your `.env` file:

```env
# Audit Logging
AUDIT_ENABLED=true
AUDIT_RETENTION_DAYS=365
AUDIT_MIDDLEWARE_ENABLED=true
AUDIT_QUEUE=false
AUDIT_CLEANUP_ENABLED=true
AUDIT_CLEANUP_SCHEDULE=daily
AUDIT_CLEANUP_BATCH_SIZE=1000
```

### Configuration File

The audit system is configured in `config/audit.php`:

```php
return [
    'enabled' => env('AUDIT_ENABLED', true),
    'retention_days' => env('AUDIT_RETENTION_DAYS', 365),
    'sensitive_models' => [
        'App\Models\User',
        'App\Models\Company',
        'App\Models\FreightOrder',
        // ...
    ],
    'events' => ['created', 'updated', 'deleted', 'restored'],
    'excluded_attributes' => ['password', 'remember_token', 'api_token'],
    // ...
];
```

## Components

### 1. AuditLog Model

The central model that stores all audit log entries:

```php
use App\Models\AuditLog;

// Get audit logs
$logs = AuditLog::with('user')->latest()->get();

// Filter by action
$createdLogs = AuditLog::byAction('created')->get();

// Filter by table
$userLogs = AuditLog::byTable('users')->get();

// Date range
$recentLogs = AuditLog::dateRange('2023-01-01', '2023-12-31')->get();
```

### 2. AuditLogService

The main service for logging audit events:

```php
use App\Services\AuditLogService;

$auditService = app(AuditLogService::class);

// Log model creation
$auditService->logCreated($model);

// Log model update
$auditService->logUpdated($model, $oldValues);

// Log custom event
$auditService->log('custom_action', $model, $oldData, $newData, 'Description');

// Log authentication events
$auditService->logLogin($email);
$auditService->logFailedLogin($email, 'Invalid password');

// Log approval/rejection
$auditService->logApproved($model, 'Approved by manager');
$auditService->logRejected($model, 'Does not meet criteria');
```

### 3. Auditable Trait

Add automatic auditing to models:

```php
use App\Traits\Auditable;

class FreightOrder extends Model
{
    use Auditable;
    
    // Optional: specify which events to audit
    protected $auditEvents = ['created', 'updated', 'deleted'];
}

// Usage
$order = new FreightOrder();
$order->save(); // Automatically logged

$order->auditLog('status_changed', ['status' => 'draft'], ['status' => 'confirmed']);
$order->auditApproval('Approved by supervisor');
```

### 4. Audit Middleware

Automatically logs API requests:

```php
// Applied to all API routes in bootstrap/app.php
$middleware->append(\App\Http\Middleware\AuditMiddleware::class);

// The middleware logs:
// - All non-GET requests to API endpoints
// - Failed requests (4xx, 5xx status codes)
// - Authentication requests
// - Sensitive GET requests (exports, reports, etc.)
```

### 5. Audit Controller

Provides API endpoints for viewing audit logs:

```php
// Get audit logs with filtering
GET /api/v1/audit?action=created&start_date=2023-01-01

// Get statistics
GET /api/v1/audit/statistics

// Export audit logs
POST /api/v1/audit/export
{
    "format": "csv",
    "start_date": "2023-01-01",
    "end_date": "2023-12-31"
}

// Get logs for specific model
GET /api/v1/audit/model?table_name=freight_orders&record_id=123

// Get user activity
GET /api/v1/audit/user/123
```

## Usage Examples

### Basic Model Auditing

```php
use App\Models\FreightOrder;
use App\Traits\Auditable;

class FreightOrder extends Model
{
    use Auditable;
}

// Create order (automatically logged)
$order = FreightOrder::create([
    'order_number' => 'FO-2023-001',
    'status' => 'draft'
]);

// Update order (automatically logged)
$order->update(['status' => 'confirmed']);

// Get audit history
$auditLogs = $order->auditLogs();
```

### Custom Audit Events

```php
use App\Services\AuditLogService;

$auditService = app(AuditLogService::class);

// Log business event
$auditService->logSensitiveOperation(
    'price_override',
    $order,
    ['original_price' => 1000, 'new_price' => 800],
    ['reason' => 'Customer discount', 'approved_by' => 'manager']
);

// Log approval workflow
$auditService->logApproved($order, 'Order approved for shipment');

// Log status change
$auditService->logStatusChanged($order, 'pending', 'confirmed');
```

### Authentication Auditing

```php
// In your authentication controller
public function login(Request $request)
{
    $credentials = $request->only('email', 'password');
    
    if (Auth::attempt($credentials)) {
        $auditService->logLogin($credentials['email']);
        return response()->json(['token' => $user->createToken('auth')->plainTextToken]);
    } else {
        $auditService->logFailedLogin($credentials['email'], 'Invalid credentials');
        return response()->json(['error' => 'Unauthorized'], 401);
    }
}

public function logout()
{
    $auditService->logLogout();
    Auth::user()->currentAccessToken()->delete();
    return response()->json(['message' => 'Logged out successfully']);
}
```

### Bulk Operations

```php
// Log bulk update
$auditService->logBulkUpdate(
    'freight_orders',
    ['status' => 'pending'],
    ['status' => 'confirmed'],
    $affectedCount
);

// Log bulk delete
$auditService->logBulkDelete(
    'old_records',
    ['created_at' => '<= 2022-01-01'],
    $deletedCount
);
```

### Data Export Auditing

```php
// Log data export
$auditService->logExport('freight_orders', $recordCount, [
    'format' => 'csv',
    'filters' => $request->all()
]);

// Log data import
$auditService->logImport('products', $importedCount, [
    'file_name' => $fileName,
    'source' => 'supplier_catalog'
]);
```

## Database Schema

The `audit_logs` table structure:

```sql
CREATE TABLE audit_logs (
    id BIGINT PRIMARY KEY,
    user_id BIGINT NULL,
    action VARCHAR(50),
    table_name VARCHAR(100) NULL,
    record_id BIGINT NULL,
    old_values JSON NULL,
    new_values JSON NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    request_id VARCHAR(50) NULL,
    session_id VARCHAR(100) NULL,
    url VARCHAR(500) NULL,
    method VARCHAR(10) NULL,
    description TEXT NULL,
    metadata JSON NULL,
    created_at TIMESTAMP,
    
    INDEX idx_user_created (user_id, created_at),
    INDEX idx_action_created (action, created_at),
    INDEX idx_table_record (table_name, record_id),
    INDEX idx_ip_created (ip_address, created_at),
    INDEX idx_request_id (request_id),
    INDEX idx_session_id (session_id),
    INDEX idx_created_at (created_at)
);
```

## Querying Audit Logs

### Find Changes to Specific Record

```php
$auditLogs = AuditLog::where('table_name', 'freight_orders')
                    ->where('record_id', 123)
                    ->with('user')
                    ->orderBy('created_at', 'desc')
                    ->get();
```

### Find User Activity

```php
$userActivity = AuditLog::where('user_id', $userId)
                       ->whereBetween('created_at', [$startDate, $endDate])
                       ->with('user')
                       ->get();
```

### Find Failed Operations

```php
$failedOperations = AuditLog::where('action', 'failed_login')
                           ->orWhere('metadata->error', true)
                           ->orderBy('created_at', 'desc')
                           ->get();
```

### Security Analysis

```php
// Multiple failed logins from same IP
$suspiciousActivity = AuditLog::where('action', 'failed_login')
                             ->where('ip_address', $ipAddress)
                             ->where('created_at', '>=', now()->subHour())
                             ->count();

// Sensitive operations by user
$sensitiveOps = AuditLog::where('user_id', $userId)
                       ->where('metadata->sensitive', true)
                       ->get();
```

## Performance Considerations

### Indexes

The system includes optimized indexes for common query patterns:

- User activity: `(user_id, created_at)`
- Action filtering: `(action, created_at)`
- Model changes: `(table_name, record_id)`
- Security analysis: `(ip_address, created_at)`

### Retention and Cleanup

Configure automatic cleanup of old audit logs:

```php
// In config/audit.php
'retention_days' => 365,
'cleanup' => [
    'enabled' => true,
    'schedule' => 'daily',
    'batch_size' => 1000,
],
```

### Queue Processing

For high-volume applications, enable queued audit logging:

```php
// In config/audit.php
'queue' => true,
'queue_connection' => 'database',
```

## Security Features

### Data Sanitization

Sensitive data is automatically removed from audit logs:

- Passwords are replaced with `[REDACTED]`
- Credit card numbers are masked
- API tokens are filtered out

### Access Control

Audit log access should be restricted:

```php
// Add authorization to audit routes
Route::prefix('audit')->middleware(['auth:sanctum', 'can:view-audit-logs'])->group(function () {
    // audit routes
});
```

### Tamper Detection

Audit logs are immutable and include request IDs for correlation with application logs.

## Compliance

The audit logging system supports various compliance requirements:

- **SOC 2**: Comprehensive logging of access and changes
- **GDPR**: User activity tracking and data access logs
- **HIPAA**: Audit trails for sensitive data access
- **ISO 27001**: Security event monitoring

## Monitoring and Alerting

Set up monitoring for:

- High volume of failed login attempts
- Unusual patterns in data access
- Bulk operations on sensitive data
- Administrative actions outside business hours

Example alert queries:

```php
// Too many failed logins
$failedLogins = AuditLog::where('action', 'failed_login')
                       ->where('created_at', '>=', now()->subMinutes(15))
                       ->count();

if ($failedLogins > 10) {
    // Send alert
}

// Administrative actions after hours
$afterHoursAdmin = AuditLog::whereIn('action', ['user_created', 'role_assigned', 'permission_changed'])
                          ->whereTime('created_at', '>', '18:00')
                          ->whereTime('created_at', '<', '08:00')
                          ->get();
```

## Best Practices

1. **Selective Auditing**: Only audit what's necessary for compliance and security
2. **Performance**: Use queues for high-volume logging
3. **Retention**: Implement appropriate data retention policies
4. **Access Control**: Restrict audit log access to authorized personnel
5. **Monitoring**: Set up alerts for suspicious patterns
6. **Documentation**: Maintain clear descriptions for custom audit events

This comprehensive audit logging system provides the foundation for security monitoring, compliance reporting, and operational troubleshooting in the OmniChain application.
