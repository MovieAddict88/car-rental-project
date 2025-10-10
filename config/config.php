<?php
// Turn on error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Timezone
date_default_timezone_set('America/New_York');

// Database credentials
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'crms_db');

// App Root
define('APP_ROOT', dirname(dirname(__FILE__)));

// URL Root - dynamically determined
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
// Dynamically determine the subdirectory if the app is not in the root
$script_path = dirname($_SERVER['PHP_SELF']);
// Go up one level from directories like /public, /user, /admin
$app_path = dirname($script_path);
// If the app is in the web root, the path will be '/', otherwise it's the subdirectory path
$url_root = ($app_path === '/' || $app_path === '\\') ? '' : $app_path;
define('URL_ROOT', $protocol . $host . $url_root);

// Site Name
define('SITE_NAME', 'Car Rental Management System');

// Establish database connection
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("ERROR: Could not connect. " . $e->getMessage());
}
?>