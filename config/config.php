<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'car_rental_system');

// Establish database connection
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("ERROR: Could not connect. " . $e->getMessage());
}

// Start session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Base URL
define('BASE_URL', 'http://' . $_SERVER['HTTP_HOST']); // Adjust '/crms' to your project's root folder

// Currency settings
define('CURRENCY_SYMBOL', '₱');

if (!function_exists('format_currency')) {
    /**
     * Format a numeric amount into Philippine Peso string.
     */
    function format_currency(float $amount, int $decimals = 2): string
    {
        return CURRENCY_SYMBOL . number_format((float)$amount, $decimals);
    }
}
?>