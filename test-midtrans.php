<?php
// Test Midtrans loading
require_once 'vendor/autoload.php';
require_once 'vendor/midtrans/midtrans-php/Midtrans.php';

// Check if classes exist
echo "Testing Midtrans SDK...\n";
echo "Midtrans\\Config exists: " . (class_exists('Midtrans\\Config') ? "YES\n" : "NO\n");
echo "Midtrans\\Snap exists: " . (class_exists('Midtrans\\Snap') ? "YES\n" : "NO\n");

// Test config
try {
    \Midtrans\Config::$serverKey = 'test-key';
    \Midtrans\Config::$clientKey = 'test-client-key';
    echo "Config setting: SUCCESS\n";
} catch (Exception $e) {
    echo "Config setting failed: " . $e->getMessage() . "\n";
}

echo "Midtrans SDK test completed.\n";
