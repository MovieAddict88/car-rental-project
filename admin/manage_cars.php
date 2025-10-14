<?php
include('includes/header.php');

$message = '';

// Handle actions: add, edit, delete
$action = $_GET['action'] ?? 'view';
$car_id = $_GET['id'] ?? null;

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $type = $_POST['type'];
    $price_per_day = $_POST['price_per_day'];
    $availability = $_POST['availability'] ?? 0;

    // Handle image upload
    $image_name = $_POST['current_image'] ?? '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../assets/images/";
        // Create directory if it doesn't exist
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $image_name = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_name;
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
    }

    if (isset($_POST['add_car'])) {
        $stmt = $pdo->prepare("INSERT INTO cars (brand, model, type, price_per_day, availability, image) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$brand, $model, $type, $price_per_day, $availability, $image_name])) {
            $message = '<div class="alert alert-success">Car added successfully.</div>';
        } else {
            $message = '<div class="alert alert-danger">Failed to add car.</div>';
        }
    } elseif (isset($_POST['edit_car']) && $car_id) {
        $stmt = $pdo->prepare("UPDATE cars SET brand = ?, model = ?, type = ?, price_per_day = ?, availability = ?, image = ? WHERE id = ?");
        if ($stmt->execute([$brand, $model, $type, $price_per_day, $availability, $image_name, $car_id])) {
            $message = '<div class="alert alert-success">Car updated successfully.</div>';
        } else {
            $message = '<div class="alert alert-danger">Failed to update car.</div>';
        }
    }
    $action = 'view'; // Reset action to view after form submission
}

// Handle delete action
if ($action === 'delete' && $car_id) {
    $stmt = $pdo->prepare("DELETE FROM cars WHERE id = ?");
    if ($stmt->execute([$car_id])) {
        $message = '<div class="alert alert-success">Car deleted successfully.</div>';
    } else {
        $message = '<div class="alert alert-danger">Failed to delete car.</div>';
    }
    $action = 'view';
}

echo $message;

if ($action === 'view') {
    // Fetch all cars
    $stmt = $pdo->query("SELECT * FROM cars ORDER BY created_at DESC");
    $cars = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
    <h1 class="mt-4">Manage Cars</h1>
    <a href="?action=add" class="btn btn-success mb-3">Add New Car</a>
    <div class="card mb-4">
        <div class="card-header">All Cars</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
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
                            <td><img src="../assets/images/<?php echo htmlspecialchars($car['image']); ?>" alt="Car Image" width="100"></td>
                            <td><?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?></td>
                            <td><?php echo htmlspecialchars($car['type']); ?></td>
                            <td><?php echo htmlspecialchars(format_currency((float)$car['price_per_day'])); ?></td>
                            <td><?php echo $car['availability'] ? '<span class="badge bg-success">Available</span>' : '<span class="badge bg-danger">Rented</span>'; ?></td>
                            <td>
                                <a href="?action=edit&id=<?php echo $car['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                                <a href="?action=delete&id=<?php echo $car['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php
} elseif ($action === 'add' || $action === 'edit') {
    $car = null;
    if ($action === 'edit' && $car_id) {
        $stmt = $pdo->prepare("SELECT * FROM cars WHERE id = ?");
        $stmt->execute([$car_id]);
        $car = $stmt->fetch(PDO::FETCH_ASSOC);
    }
?>
    <h1 class="mt-4"><?php echo ucfirst($action); ?> Car</h1>
    <div class="card mb-4">
        <div class="card-header"><?php echo ucfirst($action); ?> Car Details</div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="brand" class="form-label">Brand</label>
                    <input type="text" class="form-control" id="brand" name="brand" value="<?php echo htmlspecialchars($car['brand'] ?? ''); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="model" class="form-label">Model</label>
                    <input type="text" class="form-control" id="model" name="model" value="<?php echo htmlspecialchars($car['model'] ?? ''); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="type" class="form-label">Type</label>
                    <input type="text" class="form-control" id="type" name="type" value="<?php echo htmlspecialchars($car['type'] ?? ''); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="price_per_day" class="form-label">Price per Day</label>
                    <input type="number" step="0.01" class="form-control" id="price_per_day" name="price_per_day" value="<?php echo htmlspecialchars($car['price_per_day'] ?? ''); ?>" required>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Car Image</label>
                    <input type="file" class="form-control" id="image" name="image">
                    <?php if ($action === 'edit' && !empty($car['image'])): ?>
                        <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($car['image']); ?>">
                        <img src="../assets/images/<?php echo htmlspecialchars($car['image']); ?>" width="100" class="mt-2" alt="Current Image">
                    <?php endif; ?>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="availability" name="availability" value="1" <?php echo (isset($car['availability']) && $car['availability']) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="availability">
                        Available
                    </label>
                </div>
                <?php if ($action === 'add'): ?>
                    <button type="submit" name="add_car" class="btn btn-success">Add Car</button>
                <?php else: ?>
                    <button type="submit" name="edit_car" class="btn btn-primary">Update Car</button>
                <?php endif; ?>
                <a href="manage_cars.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
<?php
}

include('includes/footer.php');
?>