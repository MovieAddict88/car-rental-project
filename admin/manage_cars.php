<?php
$page_title = "Manage Cars";
require_once 'includes/header.php';
require_once 'includes/sidebar.php';
require_once 'includes/navbar.php';

// --- Handle Delete Request ---
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $car_id_to_delete = (int)$_GET['id'];
    // You should also delete associated images from the server and the `car_images` table
    $stmt = $conn->prepare("DELETE FROM cars WHERE id = ?");
    $stmt->bind_param("i", $car_id_to_delete);
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Car deleted successfully.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error deleting car.</div>";
    }
    $stmt->close();
}

// --- Fetch all cars from the database ---
$result = $conn->query("SELECT * FROM cars ORDER BY created_at DESC");
$cars = $result->fetch_all(MYSQLI_ASSOC);


function getAvailabilityBadgeClass($status) {
    switch (strtolower($status)) {
        case 'available': return 'bg-success';
        case 'booked': return 'bg-warning text-dark';
        case 'maintenance': return 'bg-secondary';
        default: return 'bg-light text-dark';
    }
}
?>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manage Cars</h1>
        <a href="edit_car.php" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm"></i> Add New Car
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Car Inventory</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Brand & Model</th>
                            <th>Type</th>
                            <th>Price/Day</th>
                            <th>Availability</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cars as $car): ?>
                            <tr>
                                <td><?php echo $car['id']; ?></td>
                                <td><img src="<?php echo SITE_URL . '/assets/images/' . htmlspecialchars($car['main_image']); ?>" alt="<?php echo htmlspecialchars($car['brand']); ?>" width="100"></td>
                                <td><?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?></td>
                                <td><?php echo htmlspecialchars($car['type']); ?></td>
                                <td>$<?php echo number_format($car['price_per_day'], 2); ?></td>
                                <td>
                                    <span class="badge <?php echo getAvailabilityBadgeClass($car['availability']); ?>">
                                        <?php echo ucfirst($car['availability']); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="edit_car.php?id=<?php echo $car['id']; ?>" class="btn btn-sm btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="manage_cars.php?action=delete&id=<?php echo $car['id']; ?>" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Are you sure you want to delete this car? This will also remove all associated images and cannot be undone.');">
                                        <i class="fas fa-trash"></i>
                                    </a>
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