<?php
// Prevent re-installation
if (file_exists('install.lock')) {
    die("Installation is already complete. Please remove 'install.lock' from the install directory to reinstall.");
}

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $db_host = $_POST['db_host'] ?? 'localhost';
    $db_name = $_POST['db_name'] ?? 'crms_db';
    $db_user = $_POST['db_user'] ?? 'root';
    $db_pass = $_POST['db_pass'] ?? '';

    $admin_name = $_POST['admin_name'] ?? '';
    $admin_email = $_POST['admin_email'] ?? '';
    $admin_password = $_POST['admin_password'] ?? '';

    // --- Step 1: Create .env file ---
    $env_content = "DB_HOST=$db_host\nDB_USER=$db_user\nDB_PASS=$db_pass\nDB_NAME=$db_name\n";
    if (file_put_contents(__DIR__ . '/../.env', $env_content) === false) {
        $error_message = "Failed to create .env file. Please check file permissions.";
    } else {
        // --- Step 2: Create Database ---
        try {
            $pdo = new PDO("mysql:host=$db_host", $db_user, $db_pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `$db_name`");
            $pdo->exec("USE `$db_name`");

            // --- Step 3: Import SQL file ---
            $sql = file_get_contents(__DIR__ . '/../db_setup.sql');
            // Remove the CREATE DATABASE and USE statements from the SQL file content
            $sql = preg_replace('/^CREATE DATABASE .*;/m', '', $sql);
            $sql = preg_replace('/^USE .*;/m', '', $sql);
            $pdo->exec($sql);

            // --- Step 4: Create Admin User ---
            if (!empty($admin_name) && !empty($admin_email) && !empty($admin_password)) {
                $hashed_password = password_hash($admin_password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO `users` (`name`, `email`, `password`, `role`) VALUES (?, ?, ?, 'admin')");
                $stmt->execute([$admin_name, $admin_email, $hashed_password]);
            } else {
                 $error_message .= " Admin user creation skipped due to missing fields.";
            }

            // --- Step 5: Lock Installation ---
            touch('install.lock');

            $success_message = "Installation successful! The database and admin user have been created. You will be redirected to the homepage in 5 seconds.";

            // Determine the root URL dynamically
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
            $host = $_SERVER['HTTP_HOST'];
            $script_name = str_replace('/install/setup.php', '', $_SERVER['SCRIPT_NAME']);

            header("refresh:5;url=" . $protocol . $host . $script_name . "/public/");

        } catch (PDOException $e) {
            $error_message = "Database Error: " . $e->getMessage();
        } catch (Exception $e) {
            $error_message = "An error occurred: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRMS Installation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .installer-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 30px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="installer-container">
        <h2 class="text-center mb-4">Car Rental Management System Setup</h2>

        <?php if ($error_message): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <?php if ($success_message): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php else: ?>
            <form action="setup.php" method="post">
                <div class="mb-3">
                    <h4>Database Configuration</h4>
                    <p>Enter your database connection details. The installer will create the database for you.</p>
                </div>
                <div class="mb-3">
                    <label for="db_host" class="form-label">Database Host</label>
                    <input type="text" class="form-control" id="db_host" name="db_host" value="localhost" required>
                </div>
                <div class="mb-3">
                    <label for="db_name" class="form-label">Database Name</label>
                    <input type="text" class="form-control" id="db_name" name="db_name" value="crms_db" required>
                </div>
                <div class="mb-3">
                    <label for="db_user" class="form-label">Database User</label>
                    <input type="text" class="form-control" id="db_user" name="db_user" value="root" required>
                </div>
                <div class="mb-3">
                    <label for="db_pass" class="form-label">Database Password</label>
                    <input type="password" class="form-control" id="db_pass" name="db_pass">
                </div>

                <hr class="my-4">

                <div class="mb-3">
                    <h4>Admin Account Setup</h4>
                    <p>Create your administrator account.</p>
                </div>
                <div class="mb-3">
                    <label for="admin_name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="admin_name" name="admin_name" required>
                </div>
                <div class="mb-3">
                    <label for="admin_email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="admin_email" name="admin_email" required>
                </div>
                <div class="mb-3">
                    <label for="admin_password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="admin_password" name="admin_password" required>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Install Now</button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>