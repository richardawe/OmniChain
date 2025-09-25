<?php
// Dependency check script for Laravel
echo "🔍 Checking Laravel dependencies...\n";

// Check if vendor directory exists
if (!is_dir(__DIR__ . '/vendor')) {
    echo "❌ Vendor directory not found. Run: composer install\n";
    exit(1);
}

// Check if autoload exists
if (!file_exists(__DIR__ . '/vendor/autoload.php')) {
    echo "❌ Autoload file not found. Run: composer install\n";
    exit(1);
}

// Check if bootstrap exists
if (!file_exists(__DIR__ . '/bootstrap/app.php')) {
    echo "❌ Bootstrap file not found\n";
    exit(1);
}

// Check if artisan exists
if (!file_exists(__DIR__ . '/artisan')) {
    echo "❌ Artisan file not found\n";
    exit(1);
}

// Check if .env exists
if (!file_exists(__DIR__ . '/.env')) {
    echo "⚠️  .env file not found, will be created\n";
}

// Check PHP extensions
$required_extensions = ['pdo', 'pdo_pgsql', 'mbstring', 'openssl', 'tokenizer', 'xml', 'ctype', 'json', 'bcmath'];
$missing_extensions = [];

foreach ($required_extensions as $ext) {
    if (!extension_loaded($ext)) {
        $missing_extensions[] = $ext;
    }
}

if (!empty($missing_extensions)) {
    echo "❌ Missing PHP extensions: " . implode(', ', $missing_extensions) . "\n";
    exit(1);
}

echo "✅ All dependencies check passed!\n";
echo "✅ Vendor directory exists\n";
echo "✅ Autoload file exists\n";
echo "✅ Bootstrap file exists\n";
echo "✅ Artisan file exists\n";
echo "✅ Required PHP extensions loaded\n";
?>
