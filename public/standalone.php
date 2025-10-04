<?php
// Standalone PHP server that doesn't depend on Laravel
// This is a fallback for Railway deployment

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set headers
header('Content-Type: text/html; charset=utf-8');

// Define routes
$uri = $_SERVER['REQUEST_URI'];

// Remove query string
if (($pos = strpos($uri, '?')) !== false) {
    $uri = substr($uri, 0, $pos);
}

// Remove trailing slash
$uri = rtrim($uri, '/');

// Health check endpoint
if ($uri === '/simple_health.php' || $uri === '/health') {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'ok',
        'service' => 'OmniChain API - Standalone Mode',
        'timestamp' => date('c')
    ]);
    exit;
}

// Root endpoint
if ($uri === '' || $uri === '/') {
    echo '<!DOCTYPE html>
<html>
<head>
    <title>OmniChain - Supply Chain Platform</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; line-height: 1.6; }
        .container { max-width: 800px; margin: 0 auto; }
        h1 { color: #333; }
        .card { background: #f9f9f9; border: 1px solid #ddd; padding: 20px; margin-bottom: 20px; border-radius: 4px; }
        .status { display: inline-block; padding: 5px 10px; border-radius: 4px; font-size: 14px; }
        .status.ok { background: #d4edda; color: #155724; }
        .status.error { background: #f8d7da; color: #721c24; }
        .links { margin-top: 20px; }
        .links a { display: block; margin-bottom: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>OmniChain Supply Chain Platform</h1>
        <div class="card">
            <h2>Application Status</h2>
            <p><span class="status ok">ONLINE</span> Standalone server is running</p>
            <p>This is a simplified version of the application running in standalone mode.</p>
            <p>The main Laravel application may still be initializing or experiencing issues.</p>
        </div>
        <div class="card">
            <h2>Available Endpoints</h2>
            <div class="links">
                <a href="/health">/health - Health check endpoint</a>
                <a href="/simple_health.php">/simple_health.php - Simple health check</a>
                <a href="/info.php">/info.php - PHP information</a>
            </div>
        </div>
    </div>
</body>
</html>';
    exit;
}

// PHP info endpoint
if ($uri === '/info.php') {
    phpinfo();
    exit;
}

// Default 404 response
header('HTTP/1.1 404 Not Found');
echo '<!DOCTYPE html>
<html>
<head>
    <title>404 Not Found</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; line-height: 1.6; }
        .container { max-width: 800px; margin: 0 auto; text-align: center; }
        h1 { color: #333; }
    </style>
</head>
<body>
    <div class="container">
        <h1>404 Not Found</h1>
        <p>The requested URL was not found on this server.</p>
        <p><a href="/">Go to homepage</a></p>
    </div>
</body>
</html>';
exit;
