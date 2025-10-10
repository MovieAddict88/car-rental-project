<?php
$page_title = "Login";
require_once '../includes/header.php';

// Redirect logged-in users
if (isset($_SESSION['user_id'])) {
    // Redirect admins to their dashboard, users to their profile
    if ($_SESSION['user_role'] === 'admin') {
        header("Location: " . SITE_URL . "/admin/dashboard.php");
    } else {
        header("Location: " . SITE_URL . "/user/profile.php");
    }
    exit();
}

$errors = [];
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Basic validation
    if (empty($email) || empty($password)) {
        $errors[] = "Both email and password are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    } else {
        // Fetch user from the database
        $stmt = $conn->prepare("SELECT id, name, email, password, role, status FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Verify password
            if (password_verify($password, $user['password'])) {
                // Check if user account is active
                if ($user['status'] === 'active') {
                    // Set session variables
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['user_role'] = $user['role'];

                    // Redirect based on role
                    if ($user['role'] === 'admin') {
                        header("Location: " . SITE_URL . "/admin/dashboard.php");
                    } else {
                        header("Location: " . SITE_URL . "/user/profile.php");
                    }
                    exit();
                } else {
                    $errors[] = "Your account has been suspended. Please contact support.";
                }
            } else {
                $errors[] = "Invalid email or password.";
            }
        } else {
            $errors[] = "Invalid email or password.";
        }
        $stmt->close();
    }
}
?>

<div class="row justify-content-center">
    <div class="col-lg-5 col-md-7">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white">
                <h3 class="text-center">Secure Login</h3>
            </div>
            <div class="card-body">
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errors as $error): ?>
                            <p class="mb-0"><?php echo $error; ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <form action="login.php" method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
            </div>
            <div class="card-footer text-center">
                <p class="mb-0">Don't have an account? <a href="register.php">Register Here</a></p>
            </div>
        </div>
    </div>
</div>

<?php
require_once '../includes/footer.php';
?>