<?php
$page_title = "Manage Feedback";
require_once 'includes/header.php';
require_once 'includes/sidebar.php';
require_once 'includes/navbar.php';

// --- Placeholder Data ---
$feedback_items = [
    [
        'id' => 1, 'user_name' => 'Alice Johnson', 'user_email' => 'alice@example.com',
        'message' => 'The booking process was incredibly smooth and easy. The car was clean and ready on time. Five stars!',
        'created_at' => '2025-11-06'
    ],
    [
        'id' => 2, 'user_name' => 'Bob Williams', 'user_email' => 'bob@example.com',
        'message' => 'I had a small issue with the car\'s Bluetooth connection, but customer support was very helpful in resolving it quickly.',
        'created_at' => '2025-11-04'
    ],
    [
        'id' => 3, 'user_name' => 'Charlie Brown', 'user_email' => 'charlie@example.com',
        'message' => 'Could you consider adding more pickup locations in the downtown area? It would be very convenient.',
        'created_at' => '2025-11-02'
    ],
];
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Manage User Feedback</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Inbox</h6>
        </div>
        <div class="card-body">
            <?php if (empty($feedback_items)): ?>
                <div class="text-center">
                    <p>No feedback messages to show.</p>
                </div>
            <?php else: ?>
                <div class="list-group">
                    <?php foreach ($feedback_items as $item): ?>
                        <div class="list-group-item list-group-item-action flex-column align-items-start mb-3 border">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1"><?php echo htmlspecialchars($item['user_name']); ?></h5>
                                <small><?php echo date('M d, Y', strtotime($item['created_at'])); ?></small>
                            </div>
                            <p class="mb-1"><?php echo htmlspecialchars($item['message']); ?></p>
                            <small class="text-muted"><?php echo htmlspecialchars($item['user_email']); ?></small>
                            <div class="mt-2">
                                <a href="#" class="btn btn-sm btn-success" title="Mark as Read">
                                    <i class="fas fa-check"></i> Mark as Read
                                </a>
                                <a href="#" class="btn btn-sm btn-danger" title="Delete Feedback" onclick="return confirm('Are you sure you want to delete this message?');">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
require_once 'includes/footer.php';
?>