<?php
// Test if there's a binary character causing issues
$output = "Hello from OmniChain!";
$binary_output = '';

// Output each character with its ASCII value
for ($i = 0; $i < strlen($output); $i++) {
    $char = $output[$i];
    $ascii = ord($char);
    $binary_output .= "$char (ASCII: $ascii), ";
}

// Write to a file
file_put_contents(__DIR__ . '/binary_debug.log', $binary_output);

// Try to output a single character
echo "X";
?>

