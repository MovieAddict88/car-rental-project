<?php
require_once '../config.php';
require_once '../classes/Car.php';
include 'includes/header.php';

$car_obj = new Car($pdo);
$car = null;
$error = '';
$is_edit = isset($_GET['id']);

if ($is_edit) {
    $car = $car_obj->findById($_GET['id']);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $type = $_POST['type'];
    $price_per_day = $_POST['price_per_day'];
    $availability = isset($_POST['availability']) ? 1 : 0;

    $image = $car ? $car['image'] : '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../images/";
        $image = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image;
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
    }

    if ($is_edit) {
        if ($car_obj->update($car['id'], $brand, $model, $type, $price_per_day, $availability, $image)) {
            header("Location: cars.php");
            exit;
        } else {
            $error = "Failed to update car.";
        }
    } else {
        if ($car_obj->create($brand, $model, $type, $price_per_day, $image)) {
            header("Location: cars.php");
            exit;
        } else {
            $error = "Failed to create car.";
        }
    }
}
?>

<h2><?php echo $is_edit ? 'Edit Car' : 'Add New Car'; ?></h2>
<?php if ($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <label for="brand">Brand</label>
        <input type="text" name="brand" id="brand" class="form-control" value="<?php echo $car['brand'] ?? ''; ?>" required>
    </div>
    <div class="form-group">
        <label for="model">Model</label>
        <input type="text" name="model" id="model" class="form-control" value="<?php echo $car['model'] ?? ''; ?>" required>
    </div>
    <div class="form-group">
        <label for="type">Type</label>
        <input type="text" name="type" id="type" class="form-control" value="<?php echo $car['type'] ?? ''; ?>" required>
    </div>
    <div class="form-group">
        <label for="price_per_day">Price per Day</label>
        <input type="number" step="0.01" name="price_per_day" id="price_per_day" class="form-control" value="<?php echo $car['price_per_day'] ?? ''; ?>" required>
    </div>
    <div class="form-group">
        <label for="image">Image</label>
        <input type="file" name="image" id="image" class="form-control-file">
        <?php if ($is_edit && $car['image']): ?>
            <img src="../images/<?php echo $car['image']; ?>" width="100" class="mt-2">
        <?php endif; ?>
    </div>
    <div class="form-group form-check">
        <input type="checkbox" name="availability" id="availability" class="form-check-input" <?php echo ($car['availability'] ?? 1) ? 'checked' : ''; ?>>
        <label class="form-check-label" for="availability">Available</label>
    </div>
    <button type="submit" class="btn btn-primary"><?php echo $is_edit ? 'Update' : 'Create'; ?></button>
</form>

<?php include 'includes/footer.php'; ?>