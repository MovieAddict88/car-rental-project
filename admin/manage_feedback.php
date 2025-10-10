<?php
require_once 'includes/header.php';

$action = $_GET['action'] ?? 'list';
$feedback_id = $_GET['id'] ?? null;
$error = '';
$success = '';

// Handle feedback deletion
if ($action === 'delete' && $feedback_id) {
    try {
        $stmt = $pdo->prepare("DELETE FROM feedback WHERE id = ?");
        $stmt->execute([$feedback_id]);
        $success = 'Feedback message deleted successfully!';
    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }
    $action = 'list'; // Go back to the list view
}
?>

<h1 class="h2">Manage User Feedback</h1>

<?php if ($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>
<?php if ($success): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h5>All Feedback Messages</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Message</th>
                        <th>Received At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    try {
                        $stmt = $pdo->query(
                            "SELECT f.*, u.name as user_name, u.email as user_email
                             FROM feedback f
                             JOIN users u ON f.user_id = u.id
                             ORDER BY f.created_at DESC"
                        );
                        $feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($feedbacks as $feedback) {
                            echo '<tr>';
                            echo '<td>' . $feedback['id'] . '</td>';
                            echo '<td>' . htmlspecialchars($feedback['user_name']) . '<br><small>' . htmlspecialchars($feedback['user_email']) . '</small></td>';
                            echo '<td>' . nl2br(htmlspecialchars($feedback['message'])) . '</td>';
                            echo '<td>' . date('Y-m-d H:i', strtotime($feedback['created_at'])) . '</td>';
                            echo '<td>';
                            echo '<a href="manage_feedback.php?action=delete&id=' . $feedback['id'] . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure you want to delete this feedback?\');" title="Delete Feedback"><i class="bi bi-trash"></i></a>';
                            echo '</td>';
                            echo '</tr>';
                        }
                    } catch (PDOException $e) {
                        echo '<tr><td colspan="5" class="text-danger">Could not fetch feedback data. ' . $e->getMessage() . '</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>