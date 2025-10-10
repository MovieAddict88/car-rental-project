<?php
$page_title = "Edit Car";
require_once 'includes/header.php';
require_once 'includes/sidebar.php';
require_once 'includes/navbar.php';

$car_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$is_editing = $car_id > 0;
$car = [];
$gallery_images = [];
$errors = [];
$success = '';

// File upload directory
define('UPLOAD_DIR', '../assets/images/');

if ($is_editing) {
    $page_title = "Edit Car #$car_id";
    $stmt = $conn->prepare("SELECT * FROM cars WHERE id = ?");
    $stmt->bind_param("i", $car_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $car = $result->fetch_assoc();
    $stmt->close();

    $stmt_gallery = $conn->prepare("SELECT id, image_path FROM car_images WHERE car_id = ?");
    $stmt_gallery->bind_param("i", $car_id);
    $stmt_gallery->execute();
    $gallery_images = $stmt_gallery->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt_gallery->close();

} else {
    $page_title = "Add New Car";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and retrieve form data
    $brand = trim($_POST['brand']);
    $model = trim($_POST['model']);
    $type = trim($_POST['type']);
    $year = (int)$_POST['year'];
    $seats = (int)$_POST['seats'];
    $transmission = trim($_POST['transmission']);
    $fuel = trim($_POST['fuel']);
    $price_per_day = (float)$_POST['price_per_day'];
    $description = trim($_POST['description']);
    $availability = trim($_POST['availability']);

    // Basic validation
    if (empty($brand) || empty($model) || empty($price_per_day)) {
        $errors[] = "Brand, Model, and Price are required.";
    }

    // Main image handling
    $main_image_name = $car['main_image'] ?? '';
    if (isset($_FILES['main_image']) && $_FILES['main_image']['error'] == 0) {
        $main_image_name = time() . '_' . basename($_FILES["main_image"]["name"]);
        move_uploaded_file($_FILES['main_image']['tmp_name'], UPLOAD_DIR . $main_image_name);
    } elseif (!$is_editing) {
        $errors[] = "Main image is required for a new car.";
    }

    if (empty($errors)) {
        if ($is_editing) {
            // Update existing car
            $stmt = $conn->prepare("UPDATE cars SET brand=?, model=?, type=?, year=?, seats=?, transmission=?, fuel=?, price_per_day=?, main_image=?, description=?, availability=? WHERE id=?");
            $stmt->bind_param("sssiisssdssi", $brand, $model, $type, $year, $seats, $transmission, $fuel, $price_per_day, $main_image_name, $description, $availability, $car_id);
            $stmt->execute();
            $success = "Car updated successfully!";
        } else {
            // Insert new car
            $stmt = $conn->prepare("INSERT INTO cars (brand, model, type, year, seats, transmission, fuel, price_per_day, main_image, description, availability) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssiisssdss", $brand, $model, $type, $year, $seats, $transmission, $fuel, $price_per_day, $main_image_name, $description, $availability);
            $stmt->execute();
            $car_id = $conn->insert_id;
            $success = "New car added successfully!";
        }
        $stmt->close();

        // Handle gallery images
        if (isset($_FILES['gallery_images']) && !empty($_FILES['gallery_images']['name'][0])) {
            foreach ($_FILES['gallery_images']['tmp_name'] as $key => $tmp_name) {
                if ($_FILES['gallery_images']['error'][$key] == 0) {
                    $gallery_image_name = time() . '_' . basename($_FILES['gallery_images']['name'][$key]);
                    move_uploaded_file($tmp_name, UPLOAD_DIR . $gallery_image_name);

                    $stmt_gallery = $conn->prepare("INSERT INTO car_images (car_id, image_path) VALUES (?, ?)");
                    $stmt_gallery->bind_param("is", $car_id, $gallery_image_name);
                    $stmt_gallery->execute();
                    $stmt_gallery->close();
                }
            }
            $success .= " Gallery images uploaded.";
        }
        // Redirect on success
        header("Location: manage_cars.php");
        exit();
    }
}

// Handle gallery image deletion
if (isset($_GET['action']) && $_GET['action'] == 'delete_img' && isset($_GET['img_id'])) {
    $img_id = (int)$_GET['img_id'];
    // First, get the image path to delete the file
    $stmt = $conn->prepare("SELECT image_path FROM car_images WHERE id = ?");
    $stmt->bind_param("i", $img_id);
    $stmt->execute();
    $img_path = $stmt->get_result()->fetch_assoc()['image_path'];
    // Then, delete the record from DB
    $stmt = $conn->prepare("DELETE FROM car_images WHERE id = ?");
    $stmt->bind_param("i", $img_id);
    $stmt->execute();
    // Finally, delete the file
    if ($img_path && file_exists(UPLOAD_DIR . $img_path)) {
        unlink(UPLOAD_DIR . $img_path);
    }
    header("Location: edit_car.php?id=" . $car_id);
    exit();
}
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?php echo $page_title; ?></h1>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error): echo "<p class='mb-0'>$error</p>"; endforeach; ?>
        </div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Car Details</h6>
        </div>
        <div class="card-body">
            <form action="edit_car.php?id=<?php echo $car_id; ?>" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="brand" class="form-label">Brand</label>
                        <input type="text" class="form-control" id="brand" name="brand" value="<?php echo htmlspecialchars($car['brand'] ?? ''); ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="model" class="form-label">Model</label>
                        <input type="text" class="form-control" id="model" name="model" value="<?php echo htmlspecialchars($car['model'] ?? ''); ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="type" class="form-label">Car Type</label>
                        <input type="text" class="form-control" id="type" name="type" value="<?php echo htmlspecialchars($car['type'] ?? ''); ?>" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="year" class="form-label">Year</label>
                        <input type="number" class="form-control" id="year" name="year" value="<?php echo htmlspecialchars($car['year'] ?? ''); ?>" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="seats" class="form-label">Seats</label>
                        <input type="number" class="form-control" id="seats" name="seats" value="<?php echo htmlspecialchars($car['seats'] ?? ''); ?>" required>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="transmission" class="form-label">Transmission</label>
                        <input type="text" class="form-control" id="transmission" name="transmission" value="<?php echo htmlspecialchars($car['transmission'] ?? ''); ?>" required>
                    </div>
                     <div class="col-md-3 mb-3">
                        <label for="fuel" class="form-label">Fuel Type</label>
                        <input type="text" class="form-control" id="fuel" name="fuel" value="<?php echo htmlspecialchars($car['fuel'] ?? ''); ?>" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="4" required><?php echo htmlspecialchars($car['description'] ?? ''); ?></textarea>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                         <label for="price_per_day" class="form-label">Price per Day ($)</label>
                        <input type="number" step="0.01" class="form-control" id="price_per_day" name="price_per_day" value="<?php echo htmlspecialchars($car['price_per_day'] ?? ''); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="availability" class="form-label">Availability</label>
                        <select class="form-select" id="availability" name="availability">
                            <option value="available" <?php if(isset($car['availability']) && $car['availability'] == 'available') echo 'selected'; ?>>Available</option>
                            <option value="booked" <?php if(isset($car['availability']) && $car['availability'] == 'booked') echo 'selected'; ?>>Booked</option>
                            <option value="maintenance" <?php if(isset($car['availability']) && $car['availability'] == 'maintenance') echo 'selected'; ?>>Under Maintenance</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="main_image" class="form-label">Main Image</label>
                        <input class="form-control" type="file" id="main_image" name="main_image" <?php if(!$is_editing) echo 'required'; ?>>
                        <?php if ($is_editing && !empty($car['main_image'])): ?>
                            <small class="form-text text-muted">Current: <?php echo htmlspecialchars($car['main_image']); ?></small>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="gallery_images" class="form-label">Gallery Images (Optional)</label>
                        <input class="form-control" type="file" id="gallery_images" name="gallery_images[]" multiple>
                    </div>
                </div>

                <?php if ($is_editing && !empty($gallery_images)): ?>
                <div class="mb-3">
                    <p><strong>Current Gallery Images:</strong></p>
                    <div class="row">
                    <?php foreach ($gallery_images as $img): ?>
                        <div class="col-md-3 text-center">
                            <img src="<?php echo SITE_URL . '/assets/images/' . htmlspecialchars($img['image_path']); ?>" width="150" class="img-thumbnail mb-2">
                            <a href="edit_car.php?action=delete_img&id=<?php echo $car_id; ?>&img_id=<?php echo $img['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this image?');">Delete</a>
                        </div>
                    <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <hr>
                <button type="submit" class="btn btn-primary"><?php echo $is_editing ? 'Update Car' : 'Add Car'; ?></button>
                <a href="manage_cars.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

<?php
require_once 'includes/footer.php';
?>