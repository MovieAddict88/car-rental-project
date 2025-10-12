<?php
// Composer autoload if available
$vendorAutoload = __DIR__ . '/../vendor/autoload.php';
if (file_exists($vendorAutoload)) {
    require_once $vendorAutoload;
}

// Load config
$configPath = __DIR__ . '/../config/config.php';
if (!file_exists($configPath)) {
    // Try to redirect to installer if config missing
    header('Location: /install/setup.php');
    exit;
}
$config = require $configPath;

// Error reporting
if (($config['env'] ?? 'production') === 'development') {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT);
}

// Simple PSR-4 autoloader for App\ namespace
spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return; // not our namespace
    }
    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

// Make config globally accessible through function
function app_config(string $key = null, $default = null) {
    static $cfg;
    if ($cfg === null) {
        $cfg = require __DIR__ . '/../config/config.php';
    }
    if ($key === null) return $cfg;
    $segments = explode('.', $key);
    $value = $cfg;
    foreach ($segments as $segment) {
        if (!is_array($value) || !array_key_exists($segment, $value)) {
            return $default;
        }
        $value = $value[$segment];
    }
    return $value;
}
