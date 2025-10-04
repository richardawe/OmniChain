<?php

// Database Diagnostic Script for Railway
// Access this via: https://your-railway-app-url.up.railway.app/db_diagnostic.php

// Set headers for plain text output
header('Content-Type: text/plain');

echo "=== OmniChain MySQL Diagnostic Tool ===\n\n";
echo "Timestamp: " . date('Y-m-d H:i:s') . "\n\n";

// Check if we're on Railway
echo "=== Environment Check ===\n";
$isRailway = isset($_ENV['RAILWAY_ENVIRONMENT']) || isset($_ENV['RAILWAY_SERVICE_ID']);
echo "Running on Railway: " . ($isRailway ? "Yes" : "No") . "\n\n";

// Display relevant environment variables (without sensitive values)
echo "=== Environment Variables ===\n";
$envVars = [
    'DB_CONNECTION',
    'MYSQL_DATABASE' => 'Value hidden',
    'MYSQLDATABASE' => 'Value hidden',
    'MYSQLHOST' => 'Value hidden',
    'MYSQLPORT',
    'MYSQLUSER' => 'Value hidden',
    'RAILWAY_ENVIRONMENT',
    'RAILWAY_SERVICE_ID',
    'RAILWAY_PRIVATE_DOMAIN' => 'Value hidden'
];

foreach ($envVars as $key => $value) {
    if (is_numeric($key)) {
        $varName = $value;
        echo "$varName: " . (isset($_ENV[$varName]) ? "Set" : "Not set") . "\n";
    } else {
        echo "$key: " . (isset($_ENV[$key]) ? $value : "Not set") . "\n";
    }
}
echo "\n";

// Try to connect to the database
echo "=== Database Connection Test ===\n";
try {
    // Get connection details from environment
    $dbConnection = $_ENV['DB_CONNECTION'] ?? 'mysql';
    
    if ($dbConnection === 'mysql') {
        $host = $_ENV['MYSQLHOST'] ?? $_ENV['DB_HOST'] ?? '127.0.0.1';
        $port = $_ENV['MYSQLPORT'] ?? $_ENV['DB_PORT'] ?? '3306';
        $database = $_ENV['MYSQLDATABASE'] ?? $_ENV['DB_DATABASE'] ?? 'railway';
        $username = $_ENV['MYSQLUSER'] ?? $_ENV['DB_USERNAME'] ?? 'root';
        $password = $_ENV['MYSQLPASSWORD'] ?? $_ENV['DB_PASSWORD'] ?? '';
        
        echo "Attempting to connect to MySQL...\n";
        echo "Host: $host\n";
        echo "Port: $port\n";
        echo "Database: $database\n";
        echo "Username: $username\n";
        
        // Create connection
        $conn = new mysqli($host, $username, $password, $database, $port);
        
        // Check connection
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }
        
        echo "Connection successful!\n\n";
        
        // Check if tables exist
        echo "=== Database Tables ===\n";
        $result = $conn->query("SHOW TABLES");
        
        if ($result) {
            if ($result->num_rows > 0) {
                echo "Tables found in database:\n";
                while($row = $result->fetch_array()) {
                    echo "- " . $row[0] . "\n";
                }
            } else {
                echo "No tables found in the database.\n";
            }
        } else {
            echo "Error listing tables: " . $conn->error . "\n";
        }
        
        // Close connection
        $conn->close();
    } else {
        echo "Not using MySQL. Current connection: $dbConnection\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n=== End of Diagnostic ===\n";
