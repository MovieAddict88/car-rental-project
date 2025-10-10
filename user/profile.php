<?php
require_once('../includes/header.php');

// Check if the user is logged in, if not then redirect to login page
if (!isset($_SESSION["user_id"])) {
    header("location: login.php");
    exit;
}
?>

<h1 class="mb-4">User Profile</h1>
<p>Welcome, <strong><?php echo htmlspecialchars($_SESSION["user_name"]); ?></strong>!</p>
<p>This is your profile page. You can view and update your information here.</p>

<div class="card">
    <div class="card-body">
        <h5 class="card-title">Your Details</h5>
        <ul class="list-group list-group-flush">
            <li class="list-group-item"><strong>Name:</strong> <?php echo htmlspecialchars($_SESSION["user_name"]); ?></li>
            <li class="list-group-item"><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION["user_email"]); ?></li>
            <li class="list-group-item"><strong>Role:</strong> <?php echo ucfirst(htmlspecialchars($_SESSION["user_role"])); ?></li>
        </ul>
        <a href="#" class="btn btn-primary mt-3">Edit Profile</a>
        <a href="#" class="btn btn-warning mt-3">Change Password</a>
    </div>
</div>

<?php require_once('../includes/footer.php'); ?>