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
        .installer-box {
            max-width: 500px;
            margin: 50px auto;
            padding: 30px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>

<div class="container">
    <div class="installer-box">
        <h2 class="text-center mb-4">Car Rental Management System Setup</h2>

        <?php
        // Check if installation is locked
        if (file_exists('install.lock')) {
            echo "<div class='alert alert-danger'>Installation is locked. The setup script has already been run. Please remove the 'install.lock' file if you need to run it again for some reason. For security, it's best to delete the entire /install directory.</div>";
        } else {
            $step = isset($_GET['step']) ? (int)$_GET['step'] : 1;

            if ($step === 1) {
        ?>
            <p>Welcome to the CRMS installation wizard. This will guide you through the setup process. Please provide your database details below.</p>
            <form action="setup.php?step=2" method="post">
                <div class="mb-3">
                    <label for="db_host" class="form-label">Database Host</label>
                    <input type="text" class="form-control" id="db_host" name="db_host" value="localhost" required>
                </div>
                <div class="mb-3">
                    <label for="db_name" class="form-label">Database Name</label>
                    <input type="text" class="form-control" id="db_name" name="db_name" value="crms_db" required>
                </div>
                <div class="mb-3">
                    <label for="db_user" class="form-label">Database Username</label>
                    <input type="text" class="form-control" id="db_user" name="db_user" value="root" required>
                </div>
                <div class="mb-3">
                    <label for="db_pass" class="form-label">Database Password</label>
                    <input type="password" class="form-control" id="db_pass" name="db_pass">
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Connect & Proceed</button>
                </div>
            </form>
        <?php
        } elseif ($step === 2 && $_SERVER['REQUEST_METHOD'] === 'POST') {
            // Process database setup
            $db_host = $_POST['db_host'];
            $db_name = $_POST['db_name'];
            $db_user = $_POST['db_user'];
            $db_pass = $_POST['db_pass'];

            // Store details in session to use in the next step
            session_start();
            $_SESSION['db_details'] = [
                'host' => $db_host,
                'name' => $db_name,
                'user' => $db_user,
                'pass' => $db_pass
            ];

            $message = '';
            try {
                // 1. Connect to MySQL
                $conn = new mysqli($db_host, $db_user, $db_pass);
                if ($conn->connect_error) {
                    throw new Exception("Connection failed: " . $conn->connect_error);
                }
                $message .= "<div class='alert alert-success'>Successfully connected to MySQL server.</div>";

                // 2. Create database if it doesn't exist
                $conn->query("CREATE DATABASE IF NOT EXISTS `$db_name`");
                $message .= "<div class='alert alert-success'>Database '$db_name' created or already exists.</div>";

                // 3. Select the database
                $conn->select_db($db_name);

                // 4. Import SQL from db_setup.sql
                $sql_file = file_get_contents('../db_setup.sql');
                if ($conn->multi_query($sql_file)) {
                    // Clear results from buffer
                    while ($conn->next_result()) {;}
                    $message .= "<div class='alert alert-success'>Database tables imported successfully.</div>";
                } else {
                    throw new Exception("Error importing database schema: " . $conn->error);
                }

                $conn->close();

                // Show admin creation form
                echo $message;
                ?>
                <p>Database setup is complete. Now, create your admin account.</p>
                <form action="setup.php?step=3" method="post">
                    <div class="mb-3">
                        <label for="admin_name" class="form-label">Admin Name</label>
                        <input type="text" class="form-control" id="admin_name" name="admin_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="admin_email" class="form-label">Admin Email</label>
                        <input type="email" class="form-control" id="admin_email" name="admin_email" required>
                    </div>
                    <div class="mb-3">
                        <label for="admin_pass" class="form-label">Admin Password</label>
                        <input type="password" class="form-control" id="admin_pass" name="admin_pass" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Create Admin & Finish</button>
                    </div>
                </form>
                <?php

            } catch (Exception $e) {
                echo "<div class='alert alert-danger'>An error occurred: " . $e->getMessage() . "</div>";
                echo "<a href='setup.php' class='btn btn-secondary'>Try Again</a>";
            }
        } elseif ($step === 3 && $_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
            $db = $_SESSION['db_details'];
            $admin_name = $_POST['admin_name'];
            $admin_email = $_POST['admin_email'];
            $admin_pass = password_hash($_POST['admin_pass'], PASSWORD_DEFAULT);

            try {
                // Connect to the newly created database
                $conn = new mysqli($db['host'], $db['user'], $db['pass'], $db['name']);
                if ($conn->connect_error) {
                    throw new Exception("Database connection failed: " . $conn->connect_error);
                }

                // Insert admin user
                $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'admin')");
                $stmt->bind_param("sss", $admin_name, $admin_email, $admin_pass);
                $stmt->execute();
                $stmt->close();
                $conn->close();

                // Update the config.php file
                $config_file_path = '../config/config.php';
                $config_content = file_get_contents($config_file_path);

                $config_content = preg_replace("/define\('DB_HOST', '.*?'\);/", "define('DB_HOST', '{$db['host']}');", $config_content);
                $config_content = preg_replace("/define\('DB_NAME', '.*?'\);/", "define('DB_NAME', '{$db['name']}');", $config_content);
                $config_content = preg_replace("/define\('DB_USERNAME', '.*?'\);/", "define('DB_USERNAME', '{$db['user']}');", $config_content);
                $config_content = preg_replace("/define\('DB_PASSWORD', '.*?'\);/", "define('DB_PASSWORD', '{$db['pass']}');", $config_content);

                file_put_contents($config_file_path, $config_content);

                // Create a lock file to prevent re-installation
                file_put_contents('install.lock', 'Installation completed on ' . date('Y-m-d H:i:s'));

                session_destroy();
                ?>
                <div class="alert alert-success">
                    <h4>Installation Complete!</h4>
                    <p>The Car Rental Management System has been installed successfully.</p>
                    <p>Your admin account has been created.</p>
                    <strong class="text-danger">For security reasons, please delete the `/install` directory from your server now.</strong>
                </div>
                <a href="../public/" class="btn btn-primary">Go to Homepage</a>
                <?php
            } catch (Exception $e) {
                echo "<div class='alert alert-danger'>An error occurred: " . $e->getMessage() . "</div>";
                echo "<a href='setup.php' class='btn btn-secondary'>Try Again</a>";
            }
        } else {
            echo "<div class='alert alert-warning'>Invalid step or direct access not allowed.</div>";
            echo "<a href='setup.php' class='btn btn-primary'>Start Over</a>";
        }
    } // End of lock file check
    ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>