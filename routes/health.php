<?php

// Health check route for Railway
Route::get('/health', function () {
    return response()->json([
        'status' => 'healthy',
        'timestamp' => now(),
        'database' => DB::connection()->getPdo() ? 'connected' : 'disconnected',
        'cache' => Cache::store()->getStore() ? 'connected' : 'disconnected'
    ]);
});

Route::get('/', function () {
    return response()->json([
        'message' => 'OmniChain API is running',
        'version' => '1.0.0',
        'timestamp' => now()
    ]);
});
