<?php
include('includes/header.php');

$message = '';

// Handle actions: suspend, activate, delete
$action = $_GET['action'] ?? null;
$user_id = $_GET['id'] ?? null;

if ($user_id) {
    // Prevent admin from acting on their own account
    if ($user_id == $_SESSION['user_id']) {
        $message = '<div class="alert alert-warning">You cannot perform this action on your own account.</div>';
    } else {
        if ($action === 'suspend') {
            $stmt = $pdo->prepare("UPDATE users SET status = 'suspended' WHERE id = ? AND role = 'user'");
            if ($stmt->execute([$user_id])) {
                $message = '<div class="alert alert-success">User has been suspended.</div>';
            } else {
                $message = '<div class="alert alert-danger">Failed to suspend user.</div>';
            }
        } elseif ($action === 'activate') {
            $stmt = $pdo->prepare("UPDATE users SET status = 'active' WHERE id = ? AND role = 'user'");
            if ($stmt->execute([$user_id])) {
                $message = '<div class="alert alert-success">User has been activated.</div>';
            } else {
                $message = '<div class="alert alert-danger">Failed to activate user.</div>';
            }
        } elseif ($action === 'delete') {
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ? AND role = 'user'");
            if ($stmt->execute([$user_id])) {
                $message = '<div class="alert alert-success">User has been deleted.</div>';
            } else {
                $message = '<div class="alert alert-danger">Failed to delete user.</div>';
            }
        }
    }
}

echo $message;

// Fetch all non-admin users
$stmt = $pdo->query("SELECT * FROM users WHERE role = 'user' ORDER BY created_at DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h1 class="mt-4">Manage Users</h1>
<div class="card mb-4">
    <div class="card-header">All Users</div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Registered On</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['id']); ?></td>
                        <td><?php echo htmlspecialchars($user['name']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td>
                            <span class="badge <?php echo $user['status'] === 'active' ? 'bg-success' : 'bg-danger'; ?>">
                                <?php echo htmlspecialchars(ucfirst($user['status'])); ?>
                            </span>
                        </td>
                        <td><?php echo htmlspecialchars($user['created_at']); ?></td>
                        <td>
                            <?php if ($user['status'] === 'active'): ?>
                                <a href="?action=suspend&id=<?php echo $user['id']; ?>" class="btn btn-warning btn-sm">Suspend</a>
                            <?php else: ?>
                                <a href="?action=activate&id=<?php echo $user['id']; ?>" class="btn btn-success btn-sm">Activate</a>
                            <?php endif; ?>
                            <a href="?action=delete&id=<?php echo $user['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>