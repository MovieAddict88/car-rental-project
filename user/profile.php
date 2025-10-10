<?php
$page_title = "My Profile";
require_once '../includes/header.php';

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: " . SITE_URL . "/user/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$errors = [];
$success = '';

// --- Handle Profile Information Update ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);

    if (empty($name) || empty($email)) {
        $errors[] = "Name and email cannot be empty.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    } else {
        // Check if email is already taken by another user
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->bind_param("si", $email, $user_id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errors[] = "This email is already registered to another account.";
        } else {
            // Update user information
            $stmt = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
            $stmt->bind_param("ssi", $name, $email, $user_id);
            if ($stmt->execute()) {
                $_SESSION['user_name'] = $name; // Update session variable
                $_SESSION['user_email'] = $email;
                $success = "Profile updated successfully!";
            } else {
                $errors[] = "Failed to update profile. Please try again.";
            }
        }
        $stmt->close();
    }
}

// --- Handle Password Change ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_new_password = $_POST['confirm_new_password'];

    if (empty($current_password) || empty($new_password) || empty($confirm_new_password)) {
        $errors[] = "All password fields are required.";
    } elseif ($new_password !== $confirm_new_password) {
        $errors[] = "New passwords do not match.";
    } elseif (strlen($new_password) < 6) {
        $errors[] = "New password must be at least 6 characters long.";
    } else {
        // Fetch current password from DB to verify
        $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if (password_verify($current_password, $user['password'])) {
            // Hash new password and update it
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->bind_param("si", $hashed_password, $user_id);
            if ($stmt->execute()) {
                $success = "Password changed successfully!";
            } else {
                $errors[] = "Failed to change password. Please try again.";
            }
        } else {
            $errors[] = "Incorrect current password.";
        }
        $stmt->close();
    }
}

// Fetch user's current data to display in the form
$stmt = $conn->prepare("SELECT name, email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();
$stmt->close();

?>

<div class="row">
    <!-- Sidebar Navigation -->
    <div class="col-lg-3">
        <div class="card">
            <div class="card-header">
                <h5>User Menu</h5>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><a href="profile.php" class="text-decoration-none text-dark active">My Profile</a></li>
                <li class="list-group-item"><a href="bookings.php" class="text-decoration-none text-dark">My Bookings</a></li>
                <li class="list-group-item"><a href="logout.php" class="text-decoration-none text-danger">Logout</a></li>
            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <div class="col-lg-9">
        <h2>My Profile</h2>
        <hr>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): echo "<p class='mb-0'>$error</p>"; endforeach; ?>
            </div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <!-- Profile Information Form -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>Update Profile Information</h5>
            </div>
            <div class="card-body">
                <form action="profile.php" method="POST">
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($user_data['name']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user_data['email']); ?>" required>
                    </div>
                    <button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
                </form>
            </div>
        </div>

        <!-- Change Password Form -->
        <div class="card">
            <div class="card-header">
                <h5>Change Password</h5>
            </div>
            <div class="card-body">
                <form action="profile.php" method="POST">
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password</label>
                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_new_password" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" id="confirm_new_password" name="confirm_new_password" required>
                    </div>
                    <button type="submit" name="change_password" class="btn btn-primary">Change Password</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
require_once '../includes/footer.php';
?>