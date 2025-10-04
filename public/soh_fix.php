<?php
// This file specifically checks for and removes the SOH character (0x01)
// that appears to be causing the blank screen issue

// Define a constant to tell index.php not to end the buffer
define('SKIP_OB_END_FLUSH', true);

// Start our own buffer before including index.php
ob_start();

// Include the Laravel index.php file
require_once __DIR__ . '/index.php';

// Get the output from the buffer
$output = ob_get_contents();

// Clean the buffer
ob_end_clean();

// Remove SOH character if present at the beginning
if (strlen($output) > 0 && ord($output[0]) === 1) {
    $output = substr($output, 1);
    error_log('SOH character detected and removed by soh_fix.php.');
}

// Output the cleaned content
echo $output;
?>