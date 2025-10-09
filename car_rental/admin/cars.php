<?php
require_once '../config.php';
require_once '../classes/Car.php';
include 'includes/header.php';

$car_obj = new Car($pdo);

if (isset($_GET['delete_id'])) {
    $car_obj->delete($_GET['delete_id']);
    header("Location: cars.php");
    exit;
}

$cars = $pdo->query("SELECT * FROM cars")->fetchAll();
?>

<h2>Manage Cars</h2>
<a href="car_form.php" class="btn btn-success mb-3">Add New Car</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Brand</th>
            <th>Model</th>
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
                <td><img src="../images/<?php echo $car['image']; ?>" width="100"></td>
                <td><?php echo $car['brand']; ?></td>
                <td><?php echo $car['model']; ?></td>
                <td><?php echo $car['type']; ?></td>
                <td>$<?php echo $car['price_per_day']; ?></td>
                <td><?php echo $car['availability'] ? 'Available' : 'Not Available'; ?></td>
                <td>
                    <a href="car_form.php?id=<?php echo $car['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                    <a href="cars.php?delete_id=<?php echo $car['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include 'includes/footer.php'; ?>