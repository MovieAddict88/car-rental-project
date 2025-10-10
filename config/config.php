<?php
// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Function to parse .env file
function parseEnv($filePath)
{
    if (!file_exists($filePath)) {
        return;
    }

    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);

        if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
            putenv(sprintf('%s=%s', $name, $value));
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
}

// Load .env file from the root directory
$dotenv_path = dirname(dirname(__FILE__)) . '/.env';
parseEnv($dotenv_path);

// Database credentials from environment variables or use defaults
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_NAME', getenv('DB_NAME') ?: 'crms_db');

// App Root
define('APP_ROOT', dirname(dirname(__FILE__)));

// URL Root
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
$script_name = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);
// Remove /public from the end if it exists
if (substr($script_name, -7) == '/public') {
    $script_name = substr($script_name, 0, -7);
}
define('URL_ROOT', $protocol . $host . $script_name);


// Site Name
define('SITE_NAME', 'Car Rental Management System');

// Database connection
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Check if the database exists
    if ($e->getCode() === 1049) { // SQLSTATE[HY000] [1049] Unknown database
        // We can't handle this here, but the install script will.
        // For now, we die gracefully.
        die("Database not found. Please run the installation script at <a href='" . URL_ROOT . "/install/setup.php'>" . URL_ROOT . "/install/setup.php</a>");
    } else {
        die("ERROR: Could not connect. " . $e->getMessage());
    }
}

// Function to sanitize user input
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>