<?php

// Railway server startup script
$port = $_ENV['PORT'] ?? 8000;
$host = '0.0.0.0';

echo "🚀 Starting OmniChain server on {$host}:{$port}\n";

// Start the Laravel development server
$command = "php artisan serve --host={$host} --port={$port} --no-reload";
echo "Running: {$command}\n";

passthru($command);
