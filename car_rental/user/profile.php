<?php
require_once '../config.php';
require_once '../classes/User.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_obj = new User($pdo);
$user = $user_obj->findById($_SESSION['user_id']);

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_profile'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        if ($user_obj->updateProfile($_SESSION['user_id'], $name, $email)) {
            $_SESSION['user_name'] = $name;
            $success = 'Profile updated successfully.';
        } else {
            $error = 'Failed to update profile.';
        }
    }

    if (isset($_POST['change_password'])) {
        $password = $_POST['password'];
        if ($user_obj->changePassword($_SESSION['user_id'], $password)) {
            $success = 'Password changed successfully.';
        } else {
            $error = 'Failed to change password.';
        }
    }
}

include '../includes/header.php';
?>

<h2>Profile Management</h2>
<?php if ($success): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>
<?php if ($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Update Profile</div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="update_profile" value="1">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" class="form-control" value="<?php echo $user['name']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control" value="<?php echo $user['email']; ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Profile</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Change Password</div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="change_password" value="1">
                    <div class="form-group">
                        <label for="password">New Password</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-warning">Change Password</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>