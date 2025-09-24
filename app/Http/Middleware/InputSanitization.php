<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InputSanitization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Sanitize all input data
        $this->sanitizeInput($request);
        
        return $next($request);
    }

    /**
     * Sanitize all input data
     */
    protected function sanitizeInput(Request $request): void
    {
        $input = $request->all();
        $sanitized = $this->recursiveSanitize($input);
        
        // Replace the request input with sanitized data
        $request->replace($sanitized);
    }

    /**
     * Recursively sanitize array data
     */
    protected function recursiveSanitize($data)
    {
        if (is_array($data)) {
            return array_map([$this, 'recursiveSanitize'], $data);
        }
        
        if (is_string($data)) {
            return $this->sanitizeString($data);
        }
        
        return $data;
    }

    /**
     * Sanitize string input
     */
    protected function sanitizeString(string $input): string
    {
        // Remove null bytes
        $input = str_replace("\0", '', $input);
        
        // Trim whitespace
        $input = trim($input);
        
        // Remove excessive whitespace
        $input = preg_replace('/\s+/', ' ', $input);
        
        // Remove potentially dangerous characters
        $input = preg_replace('/[<>"\']/', '', $input);
        
        // Remove control characters except newlines and tabs
        $input = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $input);
        
        // Limit length to prevent DoS attacks
        if (strlen($input) > 10000) {
            $input = substr($input, 0, 10000);
        }
        
        return $input;
    }
}
