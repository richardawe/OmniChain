<?php
// This file specifically checks for and removes the SOH character (0x01)
// that appears to be causing the blank screen issue

// Define a constant to tell index.php not to end the buffer
define('SKIP_OB_END_FLUSH', true);

// Include the Laravel index.php file
require_once __DIR__ . '/index.php';

// Now we can safely end the buffer ourselves
ob_end_clean();

// Start a new buffer for our output
ob_start();

// Get the application output from the previous buffer
$output = ob_get_clean();

// Remove SOH character if present at the beginning
if (strlen($output) > 0 && ord($output[0]) === 1) {
    $output = substr($output, 1);
}

// Output the cleaned content
echo $output;
?>