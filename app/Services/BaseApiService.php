<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

abstract class BaseApiService
{
    protected $baseUrl;
    protected $apiKey;
    protected $timeout;
    protected $cacheTtl;

    public function __construct()
    {
        $this->initializeConfig();
    }

    abstract protected function initializeConfig();

    protected function makeRequest(string $endpoint, array $params = [], string $method = 'GET')
    {
        $cacheKey = $this->generateCacheKey($endpoint, $params);
        
        // Try to get from cache first
        $cachedResponse = Cache::get($cacheKey);
        if ($cachedResponse) {
            return $cachedResponse;
        }

        try {
            $url = $this->baseUrl . '/' . ltrim($endpoint, '/');
            
            $response = Http::timeout($this->timeout)
                ->when($this->apiKey, function ($http) {
                    return $http->withQueryParameters(['api_key' => $this->apiKey]);
                })
                ->$method($url, $params);

            if ($response->successful()) {
                $data = $response->json();
                
                // Cache the response
                Cache::put($cacheKey, $data, $this->cacheTtl);
                
                return $data;
            }

            Log::error('API request failed', [
                'service' => static::class,
                'endpoint' => $endpoint,
                'status' => $response->status(),
                'response' => $response->body()
            ]);

            return null;

        } catch (\Exception $e) {
            Log::error('API request exception', [
                'service' => static::class,
                'endpoint' => $endpoint,
                'error' => $e->getMessage()
            ]);

            return null;
        }
    }

    protected function generateCacheKey(string $endpoint, array $params = []): string
    {
        $serviceName = strtolower(class_basename(static::class));
        $endpointHash = md5($endpoint . serialize($params));
        return "api_cache_{$serviceName}_{$endpointHash}";
    }

    protected function clearCache(?string $pattern = null): void
    {
        if ($pattern) {
            Cache::forget($pattern);
        }
    }
}
