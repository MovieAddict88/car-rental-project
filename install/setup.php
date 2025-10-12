<?php
// Simple installer: creates DB and admin user
require __DIR__ . '/../app/bootstrap.php';

use App\Core\Database;

if (session_status() === PHP_SESSION_NONE) {
    session_name(app_config('security.session_name'));
    session_start();
}

$dbCfg = app_config('db');

function run_sql_file(PDO $pdo, string $filePath): void {
    $sql = file_get_contents($filePath);
    $pdo->exec($sql);
}

$errors = [];
$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $adminName = trim($_POST['admin_name'] ?? '');
    $adminEmail = trim($_POST['admin_email'] ?? '');
    $adminPass = $_POST['admin_password'] ?? '';

    if (!$adminName || !$adminEmail || !$adminPass) {
        $errors[] = 'All fields are required.';
    } elseif (!filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email address.';
    }

    if (empty($errors)) {
        try {
            $pdo = new PDO(sprintf('mysql:host=%s;port=%s;charset=%s', $dbCfg['host'], $dbCfg['port'], $dbCfg['charset']), $dbCfg['user'], $dbCfg['pass'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
            // Create DB and tables
            run_sql_file($pdo, __DIR__ . '/../db_setup.sql');

            // Insert admin user if not exists
            $pdo->exec('USE `'.$dbCfg['name'].'`');
            $stmt = $pdo->prepare('SELECT id FROM users WHERE email = :email LIMIT 1');
            $stmt->execute(['email' => $adminEmail]);
            if (!$stmt->fetch()) {
                $hash = password_hash($adminPass, PASSWORD_BCRYPT, ['cost' => app_config('security.password_cost')]);
                $stmt = $pdo->prepare('INSERT INTO users(name, email, password, role, status) VALUES(:name, :email, :password, "admin", "active")');
                $stmt->execute(['name' => $adminName, 'email' => $adminEmail, 'password' => $hash]);
            }
            $success = 'Installation completed. You can now log in as admin.';
        } catch (Throwable $e) {
            $errors[] = 'Installation failed: ' . $e->getMessage();
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>CRMS Installer</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-12 col-md-8 col-lg-6">
        <div class="card shadow-sm">
          <div class="card-body p-4">
            <h1 class="h4 mb-3">CRMS Installer</h1>
            <p class="text-muted">This will create the database and admin account.</p>

            <?php if (!empty($errors)): ?>
              <div class="alert alert-danger">
                <ul class="mb-0">
                  <?php foreach ($errors as $err): ?><li><?= htmlspecialchars($err) ?></li><?php endforeach; ?>
                </ul>
              </div>
            <?php endif; ?>

            <?php if ($success): ?>
              <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
              <a href="/login" class="btn btn-primary">Go to Login</a>
            <?php else: ?>
            <form method="post">
              <div class="mb-3">
                <label class="form-label">Admin Name</label>
                <input type="text" name="admin_name" class="form-control" required />
              </div>
              <div class="mb-3">
                <label class="form-label">Admin Email</label>
                <input type="email" name="admin_email" class="form-control" required />
              </div>
              <div class="mb-3">
                <label class="form-label">Admin Password</label>
                <input type="password" name="admin_password" class="form-control" required />
              </div>
              <button class="btn btn-primary w-100">Install</button>
            </form>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
