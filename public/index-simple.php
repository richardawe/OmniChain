<?php
// Ultra-simple test file for Railway debugging
header('Content-Type: application/json');
echo json_encode([
    'status' => 'working',
    'message' => 'OmniChain is running',
    'timestamp' => date('Y-m-d H:i:s'),
    'php_version' => PHP_VERSION
]);
?>
