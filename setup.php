<?php
// Include the configuration file
require_once 'config/config.php';

try {
    // 1. Connect to MySQL server (without selecting a database)
    $pdo = new PDO("mysql:host=" . DB_HOST, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 2. Create the database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "`");
    echo "Database '" . DB_NAME . "' created successfully (or already exists).\n";

    // 3. Select the database
    $pdo->exec("USE `" . DB_NAME . "`");
    echo "Database '" . DB_NAME . "' selected.\n";

    // 4. Read the SQL file
    $sql = file_get_contents('db_setup.sql');
    if ($sql === false) {
        die("Error: Cannot read db_setup.sql file.");
    }

    // 5. Execute the SQL queries
    $pdo->exec($sql);
    echo "Database schema and data imported successfully from db_setup.sql.\n";

} catch (PDOException $e) {
    die("Database setup failed: " . $e->getMessage() . "\n");
}

echo "Setup complete!\n";
?>