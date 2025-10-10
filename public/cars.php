<?php require_once('../includes/header.php'); ?>

<h1 class="mb-4">Our Car Fleet</h1>
<p>Browse through our collection of high-quality rental cars. Use the filters to find the perfect vehicle for your needs.</p>

<hr>

<!-- Search and Filter Bar -->
<div class="row mb-4">
    <div class="col-md-3">
        <input type="text" class="form-control" placeholder="Search by brand or model...">
    </div>
    <div class="col-md-3">
        <select class="form-select">
            <option selected>Filter by Type...</option>
            <option value="sedan">Sedan</option>
            <option value="suv">SUV</option>
            <option value="van">Van</option>
            <option value="truck">Truck</option>
        </select>
    </div>
    <div class="col-md-3">
        <select class="form-select">
            <option selected>Sort by Price...</option>
            <option value="asc">Low to High</option>
            <option value="desc">High to Low</option>
        </select>
    </div>
    <div class="col-md-3">
        <button class="btn btn-primary w-100">Search</button>
    </div>
</div>

<!-- Car Listings Placeholder -->
<div class="row">
    <?php for ($i = 0; $i < 6; $i++): ?>
    <div class="col-md-4 mb-4">
        <div class="card">
            <img src="https://via.placeholder.com/300x200.png?text=Car+Image" class="card-img-top" alt="Car Image">
            <div class="card-body">
                <h5 class="card-title">Car Brand - Model</h5>
                <p class="card-text">Type: Sedan<br>Price: <strong>$50/day</strong></p>
                <a href="#" class="btn btn-primary">View Details</a>
                <a href="<?php echo URL_ROOT; ?>/user/login.php" class="btn btn-success">Book Now</a>
            </div>
        </div>
    </div>
    <?php endfor; ?>
</div>

<?php require_once('../includes/footer.php'); ?>