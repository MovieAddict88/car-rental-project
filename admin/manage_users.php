<?php
$page_title = "Manage Users";
require_once 'includes/header.php';
require_once 'includes/sidebar.php';
require_once 'includes/navbar.php';

// --- Handle User Actions ---
if (isset($_GET['action']) && isset($_GET['id'])) {
    $user_id = (int)$_GET['id'];
    $action = $_GET['action'];

    if ($action === 'delete') {
        // Prevent deleting the main admin account (assuming ID 1)
        if ($user_id !== 1) {
            $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
        }
    } elseif ($action === 'suspend') {
        $stmt = $conn->prepare("UPDATE users SET status = 'suspended' WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
    } elseif ($action === 'activate') {
        $stmt = $conn->prepare("UPDATE users SET status = 'active' WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
    }
    header("Location: manage_users.php");
    exit();
}

// --- Fetch all users from the database ---
$result = $conn->query("SELECT * FROM users ORDER BY created_at DESC");
$users = $result->fetch_all(MYSQLI_ASSOC);

function getRoleBadgeClass($role) {
    return strtolower($role) === 'admin' ? 'bg-primary' : 'bg-secondary';
}

function getStatusBadgeClass($status) {
    return strtolower($status) === 'active' ? 'bg-success' : 'bg-danger';
}
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Manage Users</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">All Registered Users</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Registered On</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo $user['id']; ?></td>
                                <td><?php echo htmlspecialchars($user['name']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td>
                                    <span class="badge <?php echo getRoleBadgeClass($user['role']); ?>">
                                        <?php echo ucfirst($user['role']); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge <?php echo getStatusBadgeClass($user['status']); ?>">
                                        <?php echo ucfirst($user['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                                <td>
                                    <?php if ($user['id'] !== 1): // Prevent actions on main admin account ?>
                                        <div class="btn-group">
                                            <?php if ($user['status'] === 'active'): ?>
                                                <a href="manage_users.php?action=suspend&id=<?php echo $user['id']; ?>" class="btn btn-sm btn-warning" title="Suspend User">
                                                    <i class="fas fa-user-slash"></i> Suspend
                                                </a>
                                            <?php else: ?>
                                                <a href="manage_users.php?action=activate&id=<?php echo $user['id']; ?>" class="btn btn-sm btn-success" title="Activate User">
                                                    <i class="fas fa-user-check"></i> Activate
                                                </a>
                                            <?php endif; ?>
                                            <a href="manage_users.php?action=delete&id=<?php echo $user['id']; ?>" class="btn btn-sm btn-danger" title="Delete User" onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
                                                <i class="fas fa-trash"></i> Delete
                                            </a>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted">No actions</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
require_once 'includes/footer.php';
?>