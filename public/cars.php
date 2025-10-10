<?php
require_once '../includes/header.php';

// Base query with primary image join
$query = "
    SELECT c.*, ci.image_path
    FROM cars c
    LEFT JOIN (
        SELECT car_id, image_path
        FROM car_images
        WHERE is_primary = 1
    ) ci ON c.id = ci.car_id
    WHERE c.availability = 1
";
$params = [];

// Filtering logic
$brand = $_GET['brand'] ?? '';
$type = $_GET['type'] ?? '';
$price_range = $_GET['price_range'] ?? '';

if (!empty($brand)) {
    $query .= " AND c.brand = :brand";
    $params[':brand'] = $brand;
}
if (!empty($type)) {
    $query .= " AND c.type = :type";
    $params[':type'] = $type;
}
if (!empty($price_range)) {
    list($min_price, $max_price) = explode('-', $price_range);
    $query .= " AND c.price_per_day BETWEEN :min_price AND :max_price";
    $params[':min_price'] = $min_price;
    $params[':max_price'] = $max_price;
}

$query .= " ORDER BY c.created_at DESC";

try {
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $cars = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Get distinct brands and types for filter dropdowns
    $brands = $pdo->query("SELECT DISTINCT brand FROM cars ORDER BY brand ASC")->fetchAll(PDO::FETCH_COLUMN);
    $types = $pdo->query("SELECT DISTINCT type FROM cars ORDER BY type ASC")->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    // Graceful error handling for the public
    $cars = [];
    $brands = [];
    $types = [];
    echo '<div class="alert alert-danger">Could not fetch car data. Please try again later.</div>';
}
?>

<div class="row">
    <div class="col-md-3">
        <h4>Filter Cars</h4>
        <hr>
        <form action="cars.php" method="get">
            <div class="mb-3">
                <label for="brand" class="form-label">Brand</label>
                <select class="form-select" id="brand" name="brand">
                    <option value="">All Brands</option>
                    <?php foreach ($brands as $b): ?>
                        <option value="<?php echo htmlspecialchars($b); ?>" <?php echo ($brand === $b) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($b); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="type" class="form-label">Type</label>
                <select class="form-select" id="type" name="type">
                    <option value="">All Types</option>
                     <?php foreach ($types as $t): ?>
                        <option value="<?php echo htmlspecialchars($t); ?>" <?php echo ($type === $t) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($t); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="price_range" class="form-label">Price Range</label>
                <select class="form-select" id="price_range" name="price_range">
                    <option value="">All Prices</option>
                    <option value="0-1000" <?php echo ($price_range === '0-1000') ? 'selected' : ''; ?>>₱0 - ₱1,000</option>
                    <option value="1001-2000" <?php echo ($price_range === '1001-2000') ? 'selected' : ''; ?>>₱1,001 - ₱2,000</option>
                    <option value="2001-5000" <?php echo ($price_range === '2001-5000') ? 'selected' : ''; ?>>₱2,001 - ₱5,000</option>
                    <option value="5001-10000" <?php echo ($price_range === '5001-10000') ? 'selected' : ''; ?>>₱5,001 - ₱10,000</option>
                </select>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="cars.php" class="btn btn-secondary mt-2">Reset</a>
            </div>
        </form>
    </div>

    <div class="col-md-9">
        <h2>Our Car Collection</h2>
        <hr>
        <div class="row">
            <?php if ($cars): ?>
                <?php foreach ($cars as $car): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100">
                            <?php $image_url = !empty($car['image_path']) ? URL_ROOT . '/assets/images/' . $car['image_path'] : URL_ROOT . '/assets/images/placeholder.png'; ?>
                            <img src="<?php echo $image_url; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?></h5>
                                <p class="card-text">Type: <?php echo htmlspecialchars($car['type']); ?></p>
                                <p class="card-text"><strong>₱<?php echo htmlspecialchars(number_format($car['price_per_day'], 2)); ?>/day</strong></p>
                            </div>
                            <div class="card-footer">
                                <a href="car_details.php?id=<?php echo $car['id']; ?>" class="btn btn-primary w-100">View Details</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col">
                    <p>No cars found matching your criteria. Please try different filters.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>