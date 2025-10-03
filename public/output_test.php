<?php
// Debug output buffering issues
ob_start();
echo "Testing output buffering in PHP";
$output = ob_get_contents();
ob_end_clean();

// Write to a log file
file_put_contents(__DIR__ . '/debug_output.log', "Output was: " . $output . "\n", FILE_APPEND);

// Try direct output
echo "Direct output test";

// Force output flushing
flush();
?>

