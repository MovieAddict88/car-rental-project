<?php
require_once '../config.php';
include 'includes/header.php';

// Handle user actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $id = $_GET['id'];

    if ($action == 'deactivate') {
        $stmt = $pdo->prepare("UPDATE users SET status = 0 WHERE id = ?");
        $stmt->execute([$id]);
    } elseif ($action == 'activate') {
        $stmt = $pdo->prepare("UPDATE users SET status = 1 WHERE id = ?");
        $stmt->execute([$id]);
    } elseif ($action == 'delete') {
        // You might want to handle related records (bookings, etc.) before deleting
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
    }
    header("Location: users.php");
    exit;
}

// Fetch all users except admin
$users = $pdo->query("SELECT * FROM users WHERE role != 'admin'")->fetchAll();
?>

<h2>Manage Users</h2>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo $user['id']; ?></td>
                <td><?php echo $user['name']; ?></td>
                <td><?php echo $user['email']; ?></td>
                <td><?php echo ucfirst($user['role']); ?></td>
                <td><?php echo $user['status'] ? 'Active' : 'Inactive'; ?></td>
                <td>
                    <?php if ($user['status']): ?>
                        <a href="users.php?action=deactivate&id=<?php echo $user['id']; ?>" class="btn btn-warning btn-sm">Deactivate</a>
                    <?php else: ?>
                        <a href="users.php?action=activate&id=<?php echo $user['id']; ?>" class="btn btn-success btn-sm">Activate</a>
                    <?php endif; ?>
                    <a href="users.php?action=delete&id=<?php echo $user['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include 'includes/footer.php'; ?>