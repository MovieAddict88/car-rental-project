<?php
require_once 'includes/header.php';

$action = $_GET['action'] ?? 'list';
$car_id = $_GET['id'] ?? null;
$error = '';
$success = '';

// Handle POST requests for add/edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brand = sanitize_input($_POST['brand']);
    $model = sanitize_input($_POST['model']);
    $type = sanitize_input($_POST['type']);
    $price_per_day = filter_input(INPUT_POST, 'price_per_day', FILTER_VALIDATE_FLOAT);
    $availability = isset($_POST['availability']) ? 1 : 0;

    if (empty($brand) || empty($model) || empty($type) || $price_per_day === false) {
        $error = 'Please fill all required fields correctly.';
    } else {
        $pdo->beginTransaction();
        try {
            if (isset($_POST['car_id']) && !empty($_POST['car_id'])) { // Update
                $car_id_to_update = $_POST['car_id'];
                $stmt = $pdo->prepare("UPDATE cars SET brand=?, model=?, type=?, price_per_day=?, availability=? WHERE id=?");
                $stmt->execute([$brand, $model, $type, $price_per_day, $availability, $car_id_to_update]);
                $car_id = $car_id_to_update;
                $success = 'Car updated successfully!';
            } else { // Add new car
                $stmt = $pdo->prepare("INSERT INTO cars (brand, model, type, price_per_day, availability) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$brand, $model, $type, $price_per_day, $availability]);
                $car_id = $pdo->lastInsertId();
                $success = 'Car added successfully!';
            }

            // Handle multiple image uploads
            if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
                $upload_dir = __DIR__ . '/../assets/images/cars/';
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

                $total_files = count($_FILES['images']['name']);
                for ($i = 0; $i < $total_files; $i++) {
                    if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                        $image_name = uniqid() . '-' . basename($_FILES['images']['name'][$i]);
                        $target_file = $upload_dir . $image_name;
                        if (move_uploaded_file($_FILES['images']['tmp_name'][$i], $target_file)) {
                            $image_path = 'cars/' . $image_name;
                            // Check if this is the first image for a new car to set as primary
                            $is_primary = ($i == 0 && !isset($_POST['car_id']));
                            $stmt = $pdo->prepare("INSERT INTO car_images (car_id, image_path, is_primary) VALUES (?, ?, ?)");
                            $stmt->execute([$car_id, $image_path, $is_primary]);
                        }
                    }
                }
            }
            $pdo->commit();
            $action = 'list';
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = 'An error occurred: ' . $e->getMessage();
        }
    }
}

// Handle image or car deletion
if ($action === 'delete_image' && isset($_GET['image_id'])) {
    // Delete a single image
    try {
        $stmt = $pdo->prepare("SELECT image_path FROM car_images WHERE id = ?");
        $stmt->execute([$_GET['image_id']]);
        $path = $stmt->fetchColumn();
        if ($path && file_exists(__DIR__ . '/../assets/images/' . $path)) {
            unlink(__DIR__ . '/../assets/images/' . $path);
        }
        $stmt = $pdo->prepare("DELETE FROM car_images WHERE id = ?");
        $stmt->execute([$_GET['image_id']]);
        $success = "Image deleted successfully.";
    } catch (PDOException $e) {
        $error = "Failed to delete image.";
    }
    $action = 'edit'; // Stay on the edit page
} elseif ($action === 'delete' && $car_id) {
    // Delete a car and all its images
    try {
        $stmt = $pdo->prepare("SELECT image_path FROM car_images WHERE car_id = ?");
        $stmt->execute([$car_id]);
        $images = $stmt->fetchAll(PDO::FETCH_COLUMN);
        foreach ($images as $path) {
            if (file_exists(__DIR__ . '/../assets/images/' . $path)) {
                unlink(__DIR__ . '/../assets/images/' . $path);
            }
        }
        $stmt = $pdo->prepare("DELETE FROM cars WHERE id = ?");
        $stmt->execute([$car_id]); // car_images are deleted by ON DELETE CASCADE
        $success = 'Car and all associated images deleted successfully!';
        $action = 'list';
    } catch (PDOException $e) {
        $error = 'Error deleting car: ' . $e->getMessage();
    }
}

?>

<h1 class="h2">Manage Cars</h1>

<?php if ($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>
<?php if ($success): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<?php if ($action === 'add' || $action === 'edit'): ?>
    <?php
    $car = null;
    if ($action === 'edit' && $car_id) {
        $stmt = $pdo->prepare("SELECT * FROM cars WHERE id = ?");
        $stmt->execute([$car_id]);
        $car = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    ?>
    <div class="card">
        <div class="card-header">
            <h5><?php echo $action === 'add' ? 'Add New Car' : 'Edit Car'; ?></h5>
        </div>
        <div class="card-body">
            <form action="manage_cars.php" method="post" enctype="multipart/form-data">
                <?php if ($action === 'edit' && $car): ?>
                    <input type="hidden" name="car_id" value="<?php echo $car['id']; ?>">
                    <input type="hidden" name="current_image" value="<?php echo $car['image']; ?>">
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="brand" class="form-label">Brand</label>
                        <input type="text" class="form-control" id="brand" name="brand" value="<?php echo htmlspecialchars($car['brand'] ?? ''); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="model" class="form-label">Model</label>
                        <input type="text" class="form-control" id="model" name="model" value="<?php echo htmlspecialchars($car['model'] ?? ''); ?>" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="type" class="form-label">Type</label>
                        <input type="text" class="form-control" id="type" name="type" value="<?php echo htmlspecialchars($car['type'] ?? ''); ?>" placeholder="e.g., Sedan, SUV" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="price_per_day" class="form-label">Price per Day (₱)</label>
                        <input type="number" step="0.01" class="form-control" id="price_per_day" name="price_per_day" value="<?php echo htmlspecialchars($car['price_per_day'] ?? ''); ?>" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="images" class="form-label">Car Images</label>
                    <input class="form-control" type="file" id="images" name="images[]" multiple>
                    <small class="form-text text-muted">You can upload multiple images at once. The first image will be set as the primary display image.</small>
                </div>

                <?php if ($action === 'edit' && $car_id): ?>
                    <div class="mb-3">
                        <h6>Existing Images</h6>
                        <div class="row">
                        <?php
                        $stmt_images = $pdo->prepare("SELECT * FROM car_images WHERE car_id = ?");
                        $stmt_images->execute([$car_id]);
                        $images = $stmt_images->fetchAll(PDO::FETCH_ASSOC);
                        if ($images) {
                            foreach ($images as $img) {
                                echo '<div class="col-md-3 text-center mb-2">';
                                echo '<img src="' . URL_ROOT . '/assets/images/' . $img['image_path'] . '" class="img-thumbnail" style="height:100px;">';
                                echo '<a href="manage_cars.php?action=delete_image&id=' . $car_id . '&image_id=' . $img['id'] . '" class="btn btn-danger btn-sm mt-1" onclick="return confirm(\'Delete this image?\');">Delete</a>';
                                echo '</div>';
                            }
                        } else {
                            echo '<p class="text-muted">No images uploaded for this car yet.</p>';
                        }
                        ?>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="availability" name="availability" value="1" <?php echo (isset($car['availability']) && $car['availability'] == 1) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="availability">
                        Available for rent
                    </label>
                </div>
                <button type="submit" class="btn btn-primary"><?php echo $action === 'add' ? 'Add Car' : 'Update Car'; ?></button>
                <a href="manage_cars.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
<?php else: ?>
    <div class="mb-3">
        <a href="manage_cars.php?action=add" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add New Car</a>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
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
                        <?php
                        try {
                            $stmt = $pdo->query("SELECT * FROM cars ORDER BY created_at DESC");
                            $cars = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($cars as $car) {
                                $image_url = !empty($car['image']) ? URL_ROOT . '/assets/images/' . $car['image'] : URL_ROOT . '/assets/images/placeholder.png';
                                echo '<tr>';
                                echo '<td><img src="' . $image_url . '" alt="Car image" style="width: 100px; height: auto;"></td>';
                                echo '<td>' . htmlspecialchars($car['brand'] . ' ' . $car['model']) . '</td>';
                                echo '<td>' . htmlspecialchars($car['type']) . '</td>';
                                echo '<td>₱' . number_format($car['price_per_day'], 2) . '</td>';
                                echo '<td><span class="badge ' . ($car['availability'] ? 'bg-success' : 'bg-secondary') . '">' . ($car['availability'] ? 'Available' : 'Unavailable') . '</span></td>';
                                echo '<td>';
                                echo '<a href="manage_cars.php?action=edit&id=' . $car['id'] . '" class="btn btn-sm btn-info me-2"><i class="bi bi-pencil-square"></i></a>';
                                echo '<a href="manage_cars.php?action=delete&id=' . $car['id'] . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure you want to delete this car?\');"><i class="bi bi-trash"></i></a>';
                                echo '</td>';
                                echo '</tr>';
                            }
                        } catch (PDOException $e) {
                            echo '<tr><td colspan="6" class="text-danger">Could not fetch car data.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>