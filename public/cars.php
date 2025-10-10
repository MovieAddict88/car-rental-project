<?php
$page_title = "All Cars";
require_once '../includes/header.php';

// --- Database query for cars with filtering ---
$sql = "SELECT * FROM cars WHERE availability = 'available'";
$params = [];
$types = '';

// Keyword search
if (!empty($_GET['search'])) {
    $search_term = '%' . $_GET['search'] . '%';
    $sql .= " AND (brand LIKE ? OR model LIKE ?)";
    $params[] = $search_term;
    $params[] = $search_term;
    $types .= 'ss';
}

// Brand filter
if (!empty($_GET['brand'])) {
    $sql .= " AND brand = ?";
    $params[] = $_GET['brand'];
    $types .= 's';
}

// Type filter
if (!empty($_GET['type'])) {
    $sql .= " AND type = ?";
    $params[] = $_GET['type'];
    $types .= 's';
}

// Price filter
if (!empty($_GET['price_range'])) {
    $sql .= " AND price_per_day <= ?";
    $params[] = $_GET['price_range'];
    $types .= 'd';
}

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$cars = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();


// --- Fetch distinct brands and types for filters ---
$brand_result = $conn->query("SELECT DISTINCT brand FROM cars ORDER BY brand ASC");
$brands = $brand_result->fetch_all(MYSQLI_ASSOC);

$type_result = $conn->query("SELECT DISTINCT type FROM cars ORDER BY type ASC");
$car_types = $type_result->fetch_all(MYSQLI_ASSOC);

?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar for Filtering -->
        <div class="col-lg-3">
            <div class="card sticky-top" style="top: 80px;">
                <div class="card-header">
                    <h4><i class="fas fa-filter"></i> Filter & Search</h4>
                </div>
                <div class="card-body">
                    <form>
                        <!-- Search by Keyword -->
                        <div class="mb-3">
                            <label for="search" class="form-label">Keyword</label>
                            <input type="text" class="form-control" id="search" placeholder="e.g., Camry, Honda">
                        </div>

                        <!-- Filter by Brand -->
                        <div class="mb-3">
                            <label for="brand" class="form-label">Brand</label>
                            <select class="form-select" id="brand" name="brand">
                                <option value="">All Brands</option>
                                <?php foreach ($brands as $brand): ?>
                                    <option value="<?php echo htmlspecialchars($brand['brand']); ?>" <?php if(isset($_GET['brand']) && $_GET['brand'] == $brand['brand']) echo 'selected'; ?>>
                                        <?php echo htmlspecialchars($brand['brand']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Filter by Type -->
                        <div class="mb-3">
                            <label for="type" class="form-label">Car Type</label>
                            <select class="form-select" id="type" name="type">
                                <option value="">All Types</option>
                                 <?php foreach ($car_types as $type): ?>
                                    <option value="<?php echo htmlspecialchars($type['type']); ?>" <?php if(isset($_GET['type']) && $_GET['type'] == $type['type']) echo 'selected'; ?>>
                                        <?php echo htmlspecialchars($type['type']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Filter by Price Range -->
                        <div class="mb-3">
                            <label for="price_range" class="form-label">Price per day: <span id="priceValue">$<?php echo isset($_GET['price_range']) ? $_GET['price_range'] : '200'; ?></span></label>
                            <input type="range" class="form-range" min="0" max="500" id="price_range" name="price_range" value="<?php echo isset($_GET['price_range']) ? $_GET['price_range'] : '500'; ?>">
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Car Listings -->
        <div class="col-lg-9">
            <h2>Our Car Collection</h2>
            <hr>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <?php if (empty($cars)): ?>
                    <div class="col-12">
                        <div class="alert alert-warning">No cars found matching your criteria.</div>
                    </div>
                <?php else: ?>
                    <?php foreach ($cars as $car): ?>
                        <div class="col">
                            <div class="card h-100">
                                <img src="<?php echo SITE_URL . '/assets/images/' . htmlspecialchars($car['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?>" style="height: 200px; object-fit: cover;">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?></h5>
                                    <p class="card-text">
                                        <span class="badge bg-secondary"><?php echo htmlspecialchars($car['type']); ?></span>
                                    </p>
                                    <p class="card-text fs-4">
                                        <strong>$<?php echo htmlspecialchars($car['price_per_day']); ?></strong> / day
                                    </p>
                                    <a href="car_details.php?id=<?php echo $car['id']; ?>" class="btn btn-primary w-100">
                                        View Details & Book
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
             <!-- Pagination (Placeholder) -->
            <nav aria-label="Page navigation" class="mt-4">
                <ul class="pagination justify-content-center">
                    <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">Next</a></li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<script>
// Simple script to update the price range display
document.getElementById('priceRange').addEventListener('input', function() {
    document.getElementById('priceValue').textContent = '$' + this.value;
});
</script>

<?php
require_once '../includes/footer.php';
?>