<?php
// Database connection test script
require_once __DIR__ . '/vendor/autoload.php';

// Load Laravel environment
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    // Test database connection
    $pdo = DB::connection()->getPdo();
    echo "✅ Database connection successful!\n";
    echo "Database: " . DB::connection()->getDatabaseName() . "\n";
    echo "Driver: " . DB::connection()->getDriverName() . "\n";
    
    // Test a simple query
    $result = DB::select('SELECT version() as version');
    echo "PostgreSQL Version: " . $result[0]->version . "\n";
    
} catch (Exception $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "\n";
    echo "Connection details:\n";
    echo "Host: " . env('DB_HOST') . "\n";
    echo "Port: " . env('DB_PORT') . "\n";
    echo "Database: " . env('DB_DATABASE') . "\n";
    echo "Username: " . env('DB_USERNAME') . "\n";
}
?>
