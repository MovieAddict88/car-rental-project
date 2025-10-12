<?php
return [
    'app_name' => 'Car Rental Management System (CRMS)',
    'env' => getenv('APP_ENV') ?: 'production',

    // Base URL is derived dynamically; override via APP_BASE_URL env if needed
    'base_url' => rtrim(getenv('APP_BASE_URL') ?: ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? '/'), '/')), '/'),

    'db' => [
        'host' => getenv('DB_HOST') ?: 'localhost',
        'name' => getenv('DB_NAME') ?: 'crms',
        'user' => getenv('DB_USER') ?: 'root',
        'pass' => getenv('DB_PASS') ?: '',
        'charset' => 'utf8mb4',
        'port' => getenv('DB_PORT') ?: '3306',
    ],

    'security' => [
        'session_name' => 'crms_session',
        'csrf_token_key' => 'csrf_token',
        'password_cost' => 12,
    ],

    'currency' => [
        'code' => 'PHP',
        'symbol' => '₱',
        'locale' => 'en_PH',
    ],
];
