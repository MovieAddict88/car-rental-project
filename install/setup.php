<?php
// Database credentials
$db_host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'car_rental_system';
$sql_file = '../db_setup.sql';

$message = '';

// Function to execute SQL queries from a file
function execute_sql_file($pdo, $sql_file) {
    $sql = file_get_contents($sql_file);
    if ($sql === false) {
        return "Error reading SQL file.";
    }
    try {
        $pdo->exec($sql);
        return "Database tables created successfully.";
    } catch (PDOException $e) {
        return "Error executing SQL file: " . $e->getMessage();
    }
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['setup'])) {
    // Step 1: Create database if it doesn't exist
    try {
        $conn = new PDO("mysql:host=$db_host", $db_username, $db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->exec("CREATE DATABASE IF NOT EXISTS `$db_name`");
        $message .= "Database '$db_name' created or already exists.<br>";

        // Step 2: Select the database
        $conn->exec("USE `$db_name`");

        // Step 3: Import tables from db_setup.sql
        $message .= execute_sql_file($conn, $sql_file) . "<br>";

        // Step 4: Create admin account
        $admin_name = $_POST['admin_name'];
        $admin_email = $_POST['admin_email'];
        $admin_password = password_hash($_POST['admin_password'], PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'admin')");
        if ($stmt->execute([$admin_name, $admin_email, $admin_password])) {
            $message .= "Admin account created successfully.<br>";
            $message .= "<strong>Setup complete! You can now delete the 'install' directory.</strong>";
        } else {
            $message .= "Error creating admin account.<br>";
        }

    } catch (PDOException $e) {
        $message .= "Database Error: " . $e->getMessage() . "<br>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Rental Management System - Setup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
        }
        .setup-container {
            max-width: 500px;
            padding: 2rem;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="setup-container">
        <h2 class="text-center mb-4">CRMS Setup</h2>

        <?php if (!empty($message)): ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="POST">
            <p>Welcome to the Car Rental Management System setup. This will prepare the database and create an admin account.</p>
            <div class="mb-3">
                <label for="admin_name" class="form-label">Admin Name</label>
                <input type="text" class="form-control" id="admin_name" name="admin_name" required>
            </div>
            <div class="mb-3">
                <label for="admin_email" class="form-label">Admin Email</label>
                <input type="email" class="form-control" id="admin_email" name="admin_email" required>
            </div>
            <div class="mb-3">
                <label for="admin_password" class="form-label">Admin Password</label>
                <input type="password" class="form-control" id="admin_password" name="admin_password" required>
            </div>
            <button type="submit" name="setup" class="btn btn-primary w-100">Run Setup</button>
        </form>
    </div>
</body>
</html>