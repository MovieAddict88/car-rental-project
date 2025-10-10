<?php
require_once 'includes/header.php';

$action = $_GET['action'] ?? 'list';
$user_id = $_GET['id'] ?? null;
$status = isset($_GET['status']) ? (int)$_GET['status'] : null;
$error = '';
$success = '';

// Handle user status toggle (suspend/activate)
if ($action === 'toggle_status' && $user_id !== null && $status !== null) {
    // Prevent admin from changing their own status or other admins
    if ($user_id == $_SESSION['user_id']) {
        $error = "You cannot change your own status.";
    } else {
        try {
            // Check if the user being modified is also an admin
            $stmt_check = $pdo->prepare("SELECT role FROM users WHERE id = ?");
            $stmt_check->execute([$user_id]);
            $user_role = $stmt_check->fetchColumn();

            if ($user_role === 'admin') {
                $error = "Admin status cannot be changed from this panel.";
            } else {
                $new_status = ($status == 1) ? 0 : 1; // Toggle status
                $stmt = $pdo->prepare("UPDATE users SET status = ? WHERE id = ? AND role = 'user'");
                $stmt->execute([$new_status, $user_id]);
                $success = "User status updated successfully.";
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
    $action = 'list';
}

// Handle user deletion
if ($action === 'delete' && $user_id) {
    if ($user_id == $_SESSION['user_id']) {
        $error = "You cannot delete your own account.";
    } else {
         try {
            // Check if the user being deleted is an admin
            $stmt_check = $pdo->prepare("SELECT role FROM users WHERE id = ?");
            $stmt_check->execute([$user_id]);
            $user_role = $stmt_check->fetchColumn();

            if ($user_role === 'admin') {
                $error = "Admin accounts cannot be deleted.";
            } else {
                // Note: Deleting a user might fail if they have related bookings due to foreign key constraints.
                // The ON DELETE CASCADE in the schema should handle this.
                $stmt = $pdo->prepare("DELETE FROM users WHERE id = ? AND role = 'user'");
                $stmt->execute([$user_id]);
                $success = "User and all their associated data (bookings, feedback) have been deleted.";
            }
        } catch (PDOException $e) {
            $error = "Error deleting user. They may have dependent records in the system. " . $e->getMessage();
        }
    }
    $action = 'list';
}
?>

<h1 class="h2">Manage Users</h1>

<?php if ($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>
<?php if ($success): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h5>All Registered Users</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Registered At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    try {
                        // Fetch all users, excluding the currently logged-in admin for safety
                        $stmt = $pdo->query("SELECT * FROM users WHERE role = 'user' ORDER BY created_at DESC");
                        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($users as $user) {
                            $status_text = $user['status'] == 1 ? 'Active' : 'Suspended';
                            $status_badge = $user['status'] == 1 ? 'bg-success' : 'bg-danger';
                            $toggle_action_text = $user['status'] == 1 ? 'Suspend' : 'Activate';
                            $toggle_action_icon = $user['status'] == 1 ? 'bi-person-x' : 'bi-person-check';

                            echo '<tr>';
                            echo '<td>' . $user['id'] . '</td>';
                            echo '<td>' . htmlspecialchars($user['name']) . '</td>';
                            echo '<td>' . htmlspecialchars($user['email']) . '</td>';
                            echo '<td>' . ucfirst($user['role']) . '</td>';
                            echo '<td><span class="badge ' . $status_badge . '">' . $status_text . '</span></td>';
                            echo '<td>' . date('Y-m-d', strtotime($user['created_at'])) . '</td>';
                            echo '<td>';
                            echo '<a href="manage_users.php?action=toggle_status&id=' . $user['id'] . '&status=' . $user['status'] . '" class="btn btn-sm btn-warning me-2" title="' . $toggle_action_text . ' User"><i class="bi ' . $toggle_action_icon . '"></i></a>';
                            echo '<a href="manage_users.php?action=delete&id=' . $user['id'] . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure you want to delete this user? This will also delete all their bookings and feedback.\');" title="Delete User"><i class="bi bi-trash"></i></a>';
                            echo '</td>';
                            echo '</tr>';
                        }
                    } catch (PDOException $e) {
                        echo '<tr><td colspan="7" class="text-danger">Could not fetch user data. ' . $e->getMessage() . '</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>