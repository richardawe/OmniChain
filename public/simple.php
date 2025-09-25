<?php
// Ultra-simple PHP test - no Laravel dependency
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>OmniChain - PHP Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f5f5f5; }
        .container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #2c3e50; }
        .status { color: #27ae60; font-weight: bold; }
        .info { background: #ecf0f1; padding: 15px; border-radius: 4px; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 OmniChain PHP Test</h1>
        <p class="status">✅ PHP is working!</p>
        <div class="info">
            <strong>Server Information:</strong><br>
            PHP Version: <?php echo PHP_VERSION; ?><br>
            Server: <?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'; ?><br>
            Time: <?php echo date('Y-m-d H:i:s'); ?><br>
            Port: <?php echo $_SERVER['SERVER_PORT'] ?? 'Unknown'; ?>
        </div>
        <p>If you can see this page, PHP is working correctly.</p>
    </div>
</body>
</html>
