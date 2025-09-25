<?php
// Simple index.php for Railway testing
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>OmniChain - Railway Test</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 40px; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .container { 
            background: rgba(255,255,255,0.1); 
            padding: 40px; 
            border-radius: 15px; 
            box-shadow: 0 8px 32px rgba(0,0,0,0.3);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
        }
        h1 { color: #fff; text-align: center; margin-bottom: 30px; }
        .status { 
            background: rgba(46, 204, 113, 0.8); 
            padding: 15px; 
            border-radius: 8px; 
            margin: 20px 0; 
            text-align: center;
            font-weight: bold;
        }
        .info { 
            background: rgba(255,255,255,0.1); 
            padding: 20px; 
            border-radius: 8px; 
            margin: 20px 0; 
        }
        .links {
            text-align: center;
            margin-top: 30px;
        }
        .links a {
            color: #fff;
            text-decoration: none;
            background: rgba(255,255,255,0.2);
            padding: 10px 20px;
            border-radius: 5px;
            margin: 0 10px;
            display: inline-block;
        }
        .links a:hover {
            background: rgba(255,255,255,0.3);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 OmniChain Supply Chain Platform</h1>
        <div class="status">✅ Application is running successfully!</div>
        
        <div class="info">
            <strong>Server Information:</strong><br>
            PHP Version: <?php echo PHP_VERSION; ?><br>
            Server Time: <?php echo date('Y-m-d H:i:s T'); ?><br>
            Server: <?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Railway'; ?><br>
            Host: <?php echo $_SERVER['HTTP_HOST'] ?? 'Unknown'; ?>
        </div>
        
        <p style="text-align: center; font-size: 18px;">
            Welcome to OmniChain - Your unified supply chain management platform!
        </p>
        
        <div class="links">
            <a href="/simple.php">PHP Test</a>
            <a href="/test.php">JSON Test</a>
            <a href="/ping">Ping Test</a>
        </div>
    </div>
</body>
</html>