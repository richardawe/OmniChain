<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;
use Illuminate\Support\Facades\Log;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $e): JsonResponse|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
    {
        // Handle API requests with JSON responses
        if ($request->is('api/*')) {
            return $this->handleApiException($request, $e);
        }

        return parent::render($request, $e);
    }

    /**
     * Handle API exceptions with standardized JSON responses
     */
    protected function handleApiException(Request $request, Throwable $e): JsonResponse
    {
        $statusCode = 500;
        $message = 'Internal Server Error';
        $errors = [];
        $debug = [];

        // Log the exception
        Log::error('API Exception', [
            'exception' => get_class($e),
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Handle specific exception types
        switch (true) {
            case $e instanceof ValidationException:
                $statusCode = 422;
                $message = 'Validation failed';
                $errors = $e->errors();
                break;

            case $e instanceof AuthenticationException:
                $statusCode = 401;
                $message = 'Unauthenticated';
                break;

            case $e instanceof AuthorizationException:
                $statusCode = 403;
                $message = 'Forbidden';
                break;

            case $e instanceof ModelNotFoundException:
                $statusCode = 404;
                $message = 'Resource not found';
                break;

            case $e instanceof NotFoundHttpException:
                $statusCode = 404;
                $message = 'Endpoint not found';
                break;

            case $e instanceof MethodNotAllowedHttpException:
                $statusCode = 405;
                $message = 'Method not allowed';
                break;

            case $e instanceof ThrottleRequestsException:
                $statusCode = 429;
                $message = 'Too many requests';
                break;

            case $e instanceof HttpException:
                $statusCode = $e->getStatusCode();
                $message = $e->getMessage() ?: 'HTTP Error';
                break;

            case $e instanceof QueryException:
                $statusCode = 500;
                $message = 'Database error occurred';
                
                // Log database errors with more detail
                Log::error('Database Query Exception', [
                    'sql' => $e->getSql(),
                    'bindings' => $e->getBindings(),
                    'message' => $e->getMessage(),
                ]);
                break;

            default:
                $statusCode = 500;
                $message = config('app.debug') ? $e->getMessage() : 'Internal Server Error';
                
                if (config('app.debug')) {
                    $debug = [
                        'exception' => get_class($e),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'trace' => $e->getTraceAsString(),
                    ];
                }
                break;
        }

        // Prepare response data
        $response = [
            'success' => false,
            'message' => $message,
            'status_code' => $statusCode,
        ];

        // Add errors if present
        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        // Add debug information in debug mode
        if (config('app.debug') && !empty($debug)) {
            $response['debug'] = $debug;
        }

        // Add request ID for tracking
        $response['request_id'] = $request->header('X-Request-ID', uniqid());

        return response()->json($response, $statusCode);
    }

    /**
     * Convert a validation exception to a JSON response.
     */
    protected function invalidJson($request, ValidationException $exception): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $exception->errors(),
            'status_code' => 422,
            'request_id' => $request->header('X-Request-ID', uniqid()),
        ], 422);
    }
}
