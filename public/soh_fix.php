<?php
// This file specifically checks for and removes the SOH character (0x01)
// that appears to be causing the blank screen issue

// Define a constant to tell index.php not to end the buffer
define('SKIP_OB_END_FLUSH', true);

// Include the Laravel index.php file
require_once __DIR__ . '/index.php';

// At this point, Laravel has already sent the response
// We don't need to do anything else
?>
