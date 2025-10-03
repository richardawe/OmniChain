<?php
// Check if there's a null byte or other control character being output
header('Content-Type: text/plain');

// Try to disable any output buffering
while (ob_get_level()) {
    ob_end_clean();
}

// Output a test string
$output = "Test output string";
echo $output;

// Log what was actually sent
$log_file = __DIR__ . '/output_check.log';
file_put_contents($log_file, "Output length: " . strlen($output) . "\n");
file_put_contents($log_file, "Output hex: " . bin2hex($output) . "\n", FILE_APPEND);

// Force output to be sent
flush();
?>

