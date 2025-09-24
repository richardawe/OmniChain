<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Audit Logging Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for audit logging in the application.
    | You can enable/disable auditing globally and configure specific settings.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Enable Audit Logging
    |--------------------------------------------------------------------------
    |
    | Set this to false to disable all audit logging functionality.
    | This is useful for testing or when audit logging is not required.
    |
    */

    'enabled' => env('AUDIT_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Audit Database Connection
    |--------------------------------------------------------------------------
    |
    | The database connection to use for storing audit logs.
    | If null, the default database connection will be used.
    |
    */

    'connection' => env('AUDIT_DB_CONNECTION', null),

    /*
    |--------------------------------------------------------------------------
    | Audit Log Retention
    |--------------------------------------------------------------------------
    |
    | How long to keep audit logs in the database (in days).
    | Set to null to keep logs indefinitely.
    | Old logs will be cleaned up by a scheduled job.
    |
    */

    'retention_days' => env('AUDIT_RETENTION_DAYS', 365),

    /*
    |--------------------------------------------------------------------------
    | Sensitive Models
    |--------------------------------------------------------------------------
    |
    | Models that should always be audited regardless of other settings.
    | These are considered sensitive and all operations should be logged.
    |
    */

    'sensitive_models' => [
        'App\Models\User',
        'App\Models\Company',
        'App\Models\FreightOrder',
        'App\Models\PurchaseOrder',
        'App\Models\SupplierContract',
        'App\Models\QualityInspection',
        'App\Models\ReturnAuthorization',
        'App\Models\SupplierPerformance',
    ],

    /*
    |--------------------------------------------------------------------------
    | Audit Events
    |--------------------------------------------------------------------------
    |
    | Which events should be audited by default for models using the Auditable trait.
    | Individual models can override this by defining their own $auditEvents property.
    |
    */

    'events' => [
        'created',
        'updated',
        'deleted',
        'restored',
    ],

    /*
    |--------------------------------------------------------------------------
    | Excluded Attributes
    |--------------------------------------------------------------------------
    |
    | Attributes that should never be included in audit logs.
    | These are typically sensitive fields like passwords.
    |
    */

    'excluded_attributes' => [
        'password',
        'remember_token',
        'api_token',
        'secret',
        'private_key',
        'credit_card',
        'ssn',
    ],

    /*
    |--------------------------------------------------------------------------
    | User Resolver
    |--------------------------------------------------------------------------
    |
    | A closure to resolve the current user for audit logs.
    | By default, it uses Laravel's Auth facade.
    |
    */

    'user_resolver' => function () {
        return auth()->user();
    },

    /*
    |--------------------------------------------------------------------------
    | IP Address Resolver
    |--------------------------------------------------------------------------
    |
    | A closure to resolve the current IP address for audit logs.
    | By default, it uses the request's IP address.
    |
    */

    'ip_resolver' => function () {
        return request()?->ip();
    },

    /*
    |--------------------------------------------------------------------------
    | User Agent Resolver
    |--------------------------------------------------------------------------
    |
    | A closure to resolve the current user agent for audit logs.
    | By default, it uses the request's user agent.
    |
    */

    'user_agent_resolver' => function () {
        return request()?->userAgent();
    },

    /*
    |--------------------------------------------------------------------------
    | Audit Middleware Settings
    |--------------------------------------------------------------------------
    |
    | Settings for the audit middleware that automatically logs API requests.
    |
    */

    'middleware' => [
        /*
        |--------------------------------------------------------------------------
        | Enable Middleware Auditing
        |--------------------------------------------------------------------------
        |
        | Enable automatic auditing of API requests through middleware.
        |
        */

        'enabled' => env('AUDIT_MIDDLEWARE_ENABLED', true),

        /*
        |--------------------------------------------------------------------------
        | Skip Routes
        |--------------------------------------------------------------------------
        |
        | Routes that should be skipped by the audit middleware.
        |
        */

        'skip_routes' => [
            'api/v1/auth/me',
            'api/v1/broadcasting/auth',
            'health',
            'metrics',
        ],

        /*
        |--------------------------------------------------------------------------
        | Skip Patterns
        |--------------------------------------------------------------------------
        |
        | Route patterns that should be skipped by the audit middleware.
        |
        */

        'skip_patterns' => [
            '/api\/v1\/.*\/index$/',
            '/api\/v1\/.*\/show\/\d+$/',
            '/health/',
            '/metrics/',
        ],

        /*
        |--------------------------------------------------------------------------
        | Audit Failed Requests
        |--------------------------------------------------------------------------
        |
        | Whether to audit failed HTTP requests (4xx and 5xx status codes).
        |
        */

        'audit_failed_requests' => true,

        /*
        |--------------------------------------------------------------------------
        | Audit Successful GET Requests
        |--------------------------------------------------------------------------
        |
        | Whether to audit successful GET requests.
        | Usually disabled to reduce log volume.
        |
        */

        'audit_get_requests' => false,

        /*
        |--------------------------------------------------------------------------
        | Sensitive GET Patterns
        |--------------------------------------------------------------------------
        |
        | GET request patterns that should be audited even if 
        | audit_get_requests is false.
        |
        */

        'sensitive_get_patterns' => [
            '/export/',
            '/report/',
            '/financial/',
            '/payment/',
            '/user/',
            '/admin/',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Queue Audit Logs
    |--------------------------------------------------------------------------
    |
    | Whether to queue audit log creation to improve performance.
    | Set to true if you have a queue worker running.
    |
    */

    'queue' => env('AUDIT_QUEUE', false),

    /*
    |--------------------------------------------------------------------------
    | Queue Connection
    |--------------------------------------------------------------------------
    |
    | The queue connection to use for audit log jobs.
    | If null, the default queue connection will be used.
    |
    */

    'queue_connection' => env('AUDIT_QUEUE_CONNECTION', null),

    /*
    |--------------------------------------------------------------------------
    | Console Auditing
    |--------------------------------------------------------------------------
    |
    | Whether to audit console commands.
    | This can generate a lot of logs, so use with caution.
    |
    */

    'console' => env('AUDIT_CONSOLE', false),

    /*
    |--------------------------------------------------------------------------
    | Audit Log Cleanup
    |--------------------------------------------------------------------------
    |
    | Settings for automatic cleanup of old audit logs.
    |
    */

    'cleanup' => [
        /*
        |--------------------------------------------------------------------------
        | Enable Cleanup
        |--------------------------------------------------------------------------
        |
        | Whether to enable automatic cleanup of old audit logs.
        |
        */

        'enabled' => env('AUDIT_CLEANUP_ENABLED', true),

        /*
        |--------------------------------------------------------------------------
        | Cleanup Schedule
        |--------------------------------------------------------------------------
        |
        | How often to run the cleanup job (daily, weekly, monthly).
        |
        */

        'schedule' => env('AUDIT_CLEANUP_SCHEDULE', 'daily'),

        /*
        |--------------------------------------------------------------------------
        | Batch Size
        |--------------------------------------------------------------------------
        |
        | How many records to delete in each batch to avoid database locks.
        |
        */

        'batch_size' => env('AUDIT_CLEANUP_BATCH_SIZE', 1000),
    ],

];
