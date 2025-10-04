<?php
// This file is a simple wrapper that removes the SOH character (ASCII 0x01)
// from the beginning of the output if present

// Start output buffering
ob_start();

// Include the Laravel index.php file with output buffering
require __DIR__ . '/index.php';

// Get the output and end the buffer
$output = ob_get_contents();
@ob_end_clean();

// Remove SOH character if present at the beginning
if (strlen($output) > 0 && ord($output[0]) === 1) {
    $output = substr($output, 1);
}

// Output the cleaned content
echo $output;
?>