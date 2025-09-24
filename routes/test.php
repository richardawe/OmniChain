<?php

// Minimal test routes for Railway debugging
Route::get('/test', function () {
    return 'OmniChain is working!';
});

Route::get('/status', function () {
    return response()->json([
        'status' => 'ok',
        'time' => date('Y-m-d H:i:s'),
        'php_version' => PHP_VERSION,
        'laravel_version' => app()->version()
    ]);
});
