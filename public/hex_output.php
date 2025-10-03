<?php
// Output the raw response as hex to identify any invisible characters
$output = "Hello World";
echo $output;

// Write to a file for debugging
file_put_contents(__DIR__ . '/hex_output.txt', bin2hex($output));

// Force output to browser
ob_flush();
flush();
?>

