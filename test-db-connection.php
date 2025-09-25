<?php
// Database connection test with proper PostgreSQL encoding
require_once __DIR__ . '/vendor/autoload.php';

// Load Laravel environment
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    // Test database connection with proper encoding
    $pdo = new PDO(
        "pgsql:host=turntable.proxy.rlwy.net;port=54435;dbname=railway",
        "postgres",
        "nqHVYqKxKaBcZPPZzErDHTPZBFRUsyWR",
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
    
    // Set proper encoding for PostgreSQL
    $pdo->exec("SET client_encoding = 'UTF8'");
    
    echo "✅ Database connection successful!\n";
    echo "Database: railway\n";
    echo "Host: turntable.proxy.rlwy.net:54435\n";
    echo "Encoding: UTF8\n";
    
    // Test a simple query
    $result = $pdo->query('SELECT version() as version');
    $version = $result->fetch();
    echo "PostgreSQL Version: " . $version['version'] . "\n";
    
} catch (Exception $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "\n";
    echo "Connection details:\n";
    echo "Host: turntable.proxy.rlwy.net\n";
    echo "Port: 54435\n";
    echo "Database: railway\n";
    echo "Username: postgres\n";
}
?>
