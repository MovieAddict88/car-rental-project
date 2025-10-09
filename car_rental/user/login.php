<?php
require_once '../config.php';
require_once '../classes/User.php';

$user = new User($pdo);
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $loggedin_user = $user->login($email, $password);
    if ($loggedin_user) {
        $_SESSION['user_id'] = $loggedin_user['id'];
        $_SESSION['user_name'] = $loggedin_user['name'];
        $_SESSION['user_role'] = $loggedin_user['role'];

        if ($loggedin_user['role'] == 'admin') {
            header("Location: ../admin/dashboard.php");
        } else {
            header("Location: index.php");
        }
        exit;
    } else {
        $error = 'Invalid email or password.';
    }
}

include '../includes/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Login</div>
            <div class="card-body">
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                <form method="POST">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Login</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>