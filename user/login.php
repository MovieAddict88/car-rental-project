<?php
require_once('../includes/header.php');

// If user is already logged in, redirect to homepage
if (isset($_SESSION['user_id'])) {
    header("location: ../public/index.php");
    exit;
}

// Define variables for form data and errors
$email = $password = "";
$errors = [];

// Check for success message from registration
$success_message = '';
if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Sanitize and validate inputs
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email)) {
        $errors['email'] = "Email is required.";
    }
    if (empty($password)) {
        $errors['password'] = "Password is required.";
    }

    // 2. If no validation errors, query database
    if (empty($errors)) {
        $sql = "SELECT id, name, email, password, role FROM users WHERE email = :email AND status = 'active'";

        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);

            if ($stmt->execute()) {
                // Check if email exists
                if ($stmt->rowCount() == 1) {
                    if ($row = $stmt->fetch()) {
                        $id = $row['id'];
                        $name = $row['name'];
                        $hashed_password = $row['password'];
                        $role = $row['role'];

                        // Verify password
                        if (password_verify($password, $hashed_password)) {
                            // Password is correct, so start a new session
                            session_start();

                            // Store data in session variables
                            $_SESSION["user_id"] = $id;
                            $_SESSION["user_name"] = $name;
                            $_SESSION["user_email"] = $email;
                            $_SESSION["user_role"] = $role;

                            // Redirect user to appropriate dashboard
                            if ($role === 'admin') {
                                header("location: ../admin/dashboard.php");
                            } else {
                                header("location: ../public/index.php");
                            }
                            exit();
                        } else {
                            // Password is not valid
                            $errors['login_fail'] = "Invalid email or password.";
                        }
                    }
                } else {
                    // Email doesn't exist
                    $errors['login_fail'] = "Invalid email or password.";
                }
            } else {
                $errors['db'] = "Oops! Something went wrong. Please try again later.";
            }
            unset($stmt);
        }
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h2 class="text-center">User Login</h2>
            </div>
            <div class="card-body">
                <?php if(!empty($success_message)): ?>
                    <div class="alert alert-success"><?php echo $success_message; ?></div>
                <?php endif; ?>
                <?php if(!empty($errors['login_fail'])): ?>
                    <div class="alert alert-danger"><?php echo $errors['login_fail']; ?></div>
                <?php endif; ?>
                 <?php if(!empty($errors['db'])): ?>
                    <div class="alert alert-danger"><?php echo $errors['db']; ?></div>
                <?php endif; ?>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" name="email" id="email" class="form-control <?php echo (!empty($errors['email'])) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($email); ?>">
                        <span class="invalid-feedback"><?php echo $errors['email'] ?? ''; ?></span>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" id="password" class="form-control <?php echo (!empty($errors['password'])) ? 'is-invalid' : ''; ?>">
                        <span class="invalid-feedback"><?php echo $errors['password'] ?? ''; ?></span>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Login</button>
                    </div>
                    <p class="text-center mt-3">
                        Don't have an account? <a href="register.php">Sign up now</a>.
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once('../includes/footer.php'); ?>