<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\AuditLogService;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuditMiddleware
{
    public function __construct(
        private AuditLogService $auditLogService
    ) {}

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip auditing for certain routes
        if ($this->shouldSkipAudit($request)) {
            return $next($request);
        }

        // Capture request start time
        $startTime = microtime(true);
        
        // Process the request
        $response = $next($request);
        
        // Log the request if it's a sensitive operation
        if ($this->isSensitiveOperation($request, $response)) {
            $this->logSensitiveOperation($request, $response, $startTime);
        }

        return $response;
    }

    /**
     * Check if audit should be skipped for this request
     */
    private function shouldSkipAudit(Request $request): bool
    {
        $skipRoutes = [
            'api/v1/auth/me',
            'api/v1/broadcasting/auth',
        ];

        $skipPatterns = [
            '/api\/v1\/.*\/index$/',
            '/api\/v1\/.*\/show\/\d+$/',
            '/health/',
            '/metrics/',
        ];

        $path = trim($request->getPathInfo(), '/');

        // Skip if route is in skip list
        if (in_array($path, $skipRoutes)) {
            return true;
        }

        // Skip if path matches skip patterns
        foreach ($skipPatterns as $pattern) {
            if (preg_match($pattern, $path)) {
                return true;
            }
        }

        // Skip GET requests for non-sensitive data
        if ($request->isMethod('GET') && !$this->isGetSensitive($request)) {
            return true;
        }

        return false;
    }

    /**
     * Check if this is a sensitive operation that should be audited
     */
    private function isSensitiveOperation(Request $request, Response $response): bool
    {
        // Always audit authentication operations
        if (str_contains($request->getPathInfo(), '/auth/')) {
            return true;
        }

        // Audit all non-GET requests to API endpoints
        if (str_starts_with($request->getPathInfo(), '/api/') && !$request->isMethod('GET')) {
            return true;
        }

        // Audit failed requests
        if ($response->getStatusCode() >= 400) {
            return true;
        }

        // Audit operations on sensitive models
        $sensitiveModels = [
            'users',
            'companies',
            'freight-orders',
            'purchase-orders',
            'return-authorizations',
            'supplier-contracts',
            'quality-inspections',
            'financial',
            'payments',
        ];

        foreach ($sensitiveModels as $model) {
            if (str_contains($request->getPathInfo(), $model)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if GET request is for sensitive data
     */
    private function isGetSensitive(Request $request): bool
    {
        $sensitiveGetPatterns = [
            '/export/',
            '/report/',
            '/financial/',
            '/payment/',
            '/user/',
            '/admin/',
        ];

        $path = $request->getPathInfo();

        foreach ($sensitiveGetPatterns as $pattern) {
            if (str_contains($path, $pattern)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Log the sensitive operation
     */
    private function logSensitiveOperation(Request $request, Response $response, float $startTime): void
    {
        $endTime = microtime(true);
        $duration = round(($endTime - $startTime) * 1000, 2); // in milliseconds

        $action = $this->determineAction($request);
        $metadata = [
            'method' => $request->method(),
            'path' => $request->getPathInfo(),
            'status_code' => $response->getStatusCode(),
            'duration_ms' => $duration,
            'request_size' => strlen($request->getContent()),
            'response_size' => strlen($response->getContent()),
        ];

        // Add query parameters for GET requests
        if ($request->isMethod('GET') && $request->query()) {
            $metadata['query_params'] = $request->query();
        }

        // Add request data for non-GET requests (excluding sensitive fields)
        if (!$request->isMethod('GET')) {
            $requestData = $request->all();
            $this->sanitizeRequestData($requestData);
            $metadata['request_data'] = $requestData;
        }

        // Add error information for failed requests
        if ($response->getStatusCode() >= 400) {
            $metadata['error'] = true;
            if ($response->headers->get('content-type') === 'application/json') {
                $responseData = json_decode($response->getContent(), true);
                if ($responseData && isset($responseData['message'])) {
                    $metadata['error_message'] = $responseData['message'];
                }
            }
        }

        $this->auditLogService->log(
            $action,
            null,
            null,
            null,
            $this->generateDescription($request, $response),
            $metadata
        );
    }

    /**
     * Determine the action type based on the request
     */
    private function determineAction(Request $request): string
    {
        $method = $request->method();
        $path = $request->getPathInfo();

        // Authentication actions
        if (str_contains($path, '/auth/login')) {
            return 'login_attempt';
        }
        if (str_contains($path, '/auth/logout')) {
            return 'logout';
        }

        // Export/Import actions
        if (str_contains($path, '/export')) {
            return 'export';
        }
        if (str_contains($path, '/import')) {
            return 'import';
        }

        // CRUD operations
        switch ($method) {
            case 'POST':
                return 'created';
            case 'PUT':
            case 'PATCH':
                return 'updated';
            case 'DELETE':
                return 'deleted';
            case 'GET':
                return 'accessed';
            default:
                return strtolower($method) . '_request';
        }
    }

    /**
     * Generate a human-readable description
     */
    private function generateDescription(Request $request, Response $response): string
    {
        $method = $request->method();
        $path = $request->getPathInfo();
        $status = $response->getStatusCode();
        $user = Auth::user();

        $description = "{$method} request to {$path}";
        
        if ($user) {
            $description .= " by {$user->email}";
        }

        $description .= " (Status: {$status})";

        // Add specific context for certain operations
        if (str_contains($path, '/auth/')) {
            $description = "Authentication " . $description;
        } elseif ($status >= 400) {
            $description = "Failed " . $description;
        } elseif ($status >= 200 && $status < 300) {
            $description = "Successful " . $description;
        }

        return $description;
    }

    /**
     * Remove sensitive data from request data before logging
     */
    private function sanitizeRequestData(array &$data): void
    {
        $sensitiveFields = [
            'password',
            'password_confirmation',
            'current_password',
            'new_password',
            'token',
            'api_key',
            'secret',
            'credit_card',
            'card_number',
            'cvv',
            'ssn',
            'social_security_number',
        ];

        foreach ($sensitiveFields as $field) {
            if (isset($data[$field])) {
                $data[$field] = '[REDACTED]';
            }
        }

        // Recursively sanitize nested arrays
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $this->sanitizeRequestData($data[$key]);
            }
        }
    }
}
