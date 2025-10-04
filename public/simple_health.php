<?php
// Simple health check that doesn't depend on Laravel or database
// This will always return a 200 OK status

header('Content-Type: application/json');
echo json_encode([
    'status' => 'ok',
    'service' => 'OmniChain API',
    'timestamp' => date('c')
]);
