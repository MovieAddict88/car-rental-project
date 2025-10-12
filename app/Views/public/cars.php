<h1 class="h3">Available Cars</h1>
<form class="row g-3 mt-1 mb-3" method="get">
  <div class="col-12 col-sm-6 col-lg-3">
    <input type="text" name="brand" value="<?= htmlspecialchars($filters['brand'] ?? '') ?>" class="form-control" placeholder="Brand">
  </div>
  <div class="col-12 col-sm-6 col-lg-2">
    <select name="type" class="form-select">
      <option value="">All Types</option>
      <?php foreach (['SUV','Sedan','Van','Truck','Coupe','Hatchback','Other'] as $t): ?>
        <option value="<?= $t ?>" <?= ($filters['type'] ?? '') === $t ? 'selected' : '' ?>><?= $t ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="col-6 col-lg-2">
    <input type="number" step="0.01" name="min_price" value="<?= htmlspecialchars($filters['min_price'] ?? '') ?>" class="form-control" placeholder="Min Price">
  </div>
  <div class="col-6 col-lg-2">
    <input type="number" step="0.01" name="max_price" value="<?= htmlspecialchars($filters['max_price'] ?? '') ?>" class="form-control" placeholder="Max Price">
  </div>
  <div class="col-12 col-lg-2">
    <select name="availability" class="form-select">
      <?php foreach (['available','unavailable'] as $a): ?>
        <option value="<?= $a ?>" <?= ($filters['availability'] ?? 'available') === $a ? 'selected' : '' ?>><?= ucfirst($a) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="col-12 col-lg-1 d-grid">
    <button class="btn btn-primary">Filter</button>
  </div>
</form>

<div class="row g-4">
  <?php if (empty($cars)): ?>
    <div class="col-12">
      <div class="alert alert-info">No cars found.</div>
    </div>
  <?php endif; ?>

  <?php foreach ($cars as $car): ?>
    <div class="col-12 col-sm-6 col-lg-4">
      <div class="card h-100 shadow-sm">
        <img src="<?= htmlspecialchars($car['image'] ?: '/assets/images/placeholder-car.jpg') ?>" class="card-img-top" alt="<?= htmlspecialchars($car['brand'].' '.$car['model']) ?>">
        <div class="card-body d-flex flex-column">
          <h3 class="h6 mb-1"><?= htmlspecialchars($car['brand'].' '.$car['model']) ?></h3>
          <div class="text-muted mb-2"><?= htmlspecialchars($car['type']) ?></div>
          <div class="mt-auto fw-bold text-primary">₱<?= number_format((float)$car['price_per_day'], 2) ?>/day</div>
          <a href="/car?id=<?= (int)$car['id'] ?>" class="btn btn-outline-primary w-100 mt-2">View Details</a>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>
