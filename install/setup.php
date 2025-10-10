<?php
// Car Rental Management System
// Setup Script
//
// This script guides the user through the database setup process.
// It creates the database, tables, and the initial admin user.

$db_host = 'localhost';
$db_user = 'root'; // Default XAMPP/WAMP username
$db_pass = '';     // Default XAMPP/WAMP password
$db_name = 'crms_db';
$sql_file = '../db_setup.sql';

$error = '';
$success = '';

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get user-provided database credentials
    $db_host = trim($_POST['db_host']);
    $db_user = trim($_POST['db_user']);
    $db_pass = trim($_POST['db_pass']);
    $db_name = trim($_POST['db_name']);

    // --- Step 1: Establish Connection to MySQL Server ---
    $conn = new mysqli($db_host, $db_user, $db_pass);

    if ($conn->connect_error) {
        $error = "Connection failed: " . $conn->connect_error;
    } else {
        // --- Step 2: Create Database if it doesn't exist ---
        $sql_create_db = "CREATE DATABASE IF NOT EXISTS `$db_name`";
        if (!$conn->query($sql_create_db)) {
            $error = "Error creating database: " . $conn->error;
        } else {
            // --- Step 3: Select the new database ---
            $conn->select_db($db_name);

            // --- Step 4: Read and Execute SQL from db_setup.sql ---
            $sql_script = file_get_contents($sql_file);
            if ($sql_script === false) {
                $error = "Error reading SQL setup file.";
            } else {
                // Execute multi-query
                if ($conn->multi_query($sql_script)) {
                    // Clear results from multi_query
                    do {
                        if ($result = $conn->store_result()) {
                            $result->free();
                        }
                    } while ($conn->next_result());

                    $success = "Database and tables created successfully!";

                    // --- Step 5: Create config.php file ---
                    $config_content = "<?php
// Database Configuration
define('DB_HOST', '$db_host');
define('DB_USER', '$db_user');
define('DB_PASS', '$db_pass');
define('DB_NAME', '$db_name');

// Site Configuration
define('SITE_URL', 'http://' . \$_SERVER['SERVER_NAME'] . str_replace('/install/setup.php', '', \$_SERVER['PHP_SELF']));

// Establish database connection
\$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (\$conn->connect_error) {
    die('Connection failed: ' . \$conn->connect_error);
}
?>";
                    if (!file_put_contents('../config/config.php', $config_content)) {
                        $error .= " Could not create config.php. Please create it manually.";
                    } else {
                         // --- Step 6: Setup Admin User ---
                        $admin_name = trim($_POST['admin_name']);
                        $admin_email = trim($_POST['admin_email']);
                        $admin_password = trim($_POST['admin_password']);

                        if (empty($admin_name) || empty($admin_email) || empty($admin_password)) {
                            $error .= " Please fill in all admin details.";
                        } else {
                            // Hash the password for security
                            $hashed_password = password_hash($admin_password, PASSWORD_DEFAULT);

                            // Check if admin already exists, otherwise insert
                            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? OR role = 'admin'");
                            $stmt->bind_param("s", $admin_email);
                            $stmt->execute();
                            $stmt->store_result();

                            if ($stmt->num_rows > 0) {
                                // Update existing admin record
                                $stmt_update = $conn->prepare("UPDATE users SET name = ?, email = ?, password = ? WHERE role = 'admin'");
                                $stmt_update->bind_param("sss", $admin_name, $admin_email, $hashed_password);
                                if(!$stmt_update->execute()){
                                     $error .= " Failed to update admin user: " . $stmt_update->error;
                                }
                                $stmt_update->close();
                            } else {
                                // Insert new admin record
                                $stmt_insert = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'admin')");
                                $stmt_insert->bind_param("sss", $admin_name, $admin_email, $hashed_password);
                                if(!$stmt_insert->execute()){
                                    $error .= " Failed to create admin user: " . $stmt_insert->error;
                                }
                                $stmt_insert->close();
                            }
                            $stmt->close();
                            $success .= " Admin user configured successfully. You can now login.";
                            $success .= "<br><strong style='color:red;'>For security, please delete the '/install' directory.</strong>";
                        }
                    }

                } else {
                    $error = "Error importing tables: " . $conn->error;
                }
            }
        }
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRMS - System Installation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 700px;
            margin-top: 50px;
        }
        .card-header {
            background-color: #0d6efd;
            color: white;
        }
        .form-label {
            font-weight: 500;
        }
        .alert-danger, .alert-success {
            word-wrap: break-word;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3 class="text-center">Car Rental Management System - Setup</h3>
            </div>
            <div class="card-body">
                <p class="text-center">Welcome! This wizard will guide you through the installation.</p>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger">
                        <strong>Error!</strong><br><?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                    <div class="alert alert-success">
                        <strong>Success!</strong><br><?php echo $success; ?>
                        <hr>
                        <a href="../admin/" class="btn btn-primary">Go to Admin Login</a>
                    </div>
                <?php else: ?>
                    <form action="setup.php" method="POST">
                        <fieldset class="mb-4">
                            <legend>1. Database Settings</legend>
                            <div class="mb-3">
                                <label for="db_host" class="form-label">Database Host</label>
                                <input type="text" class="form-control" id="db_host" name="db_host" value="<?php echo htmlspecialchars($db_host); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="db_name" class="form-label">Database Name</label>
                                <input type="text" class="form-control" id="db_name" name="db_name" value="<?php echo htmlspecialchars($db_name); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="db_user" class="form-label">Database User</label>
                                <input type="text" class="form-control" id="db_user" name="db_user" value="<?php echo htmlspecialchars($db_user); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="db_pass" class="form-label">Database Password</label>
                                <input type="password" class="form-control" id="db_pass" name="db_pass">
                            </div>
                        </fieldset>

                        <fieldset>
                            <legend>2. Admin Account Setup</legend>
                            <div class="mb-3">
                                <label for="admin_name" class="form-label">Admin Name</label>
                                <input type="text" class="form-control" id="admin_name" name="admin_name" required value="Admin">
                            </div>
                            <div class="mb-3">
                                <label for="admin_email" class="form-label">Admin Email</label>
                                <input type="email" class="form-control" id="admin_email" name="admin_email" required value="admin@example.com">
                            </div>
                             <div class="mb-3">
                                <label for="admin_password" class="form-label">Admin Password</label>
                                <input type="password" class="form-control" id="admin_password" name="admin_password" required>
                            </div>
                        </fieldset>

                        <hr>
                        <button type="submit" class="btn btn-primary w-100">Install Now</button>
                    </form>
                <?php endif; ?>
            </div>
            <div class="card-footer text-center text-muted">
                &copy; <?php echo date('Y'); ?> CRMS
            </div>
        </div>
    </div>
</body>
</html>