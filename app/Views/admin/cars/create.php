<?php include __DIR__ . '/../../../../includes/header.php'; ?>
<?php include __DIR__ . '/../../../../includes/admin_navbar.php'; ?>
<h1 class="h4 mb-3">Add Car</h1>
<?php if (!empty($error)): ?><div class="alert alert-danger"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<form method="post" class="row g-3">
  <input type="hidden" name="csrf_token" value="<?= App\Core\Security::csrfToken() ?>">
  <div class="col-12 col-md-6">
    <label class="form-label">Brand</label>
    <input type="text" name="brand" class="form-control" required>
  </div>
  <div class="col-12 col-md-6">
    <label class="form-label">Model</label>
    <input type="text" name="model" class="form-control" required>
  </div>
  <div class="col-12 col-md-4">
    <label class="form-label">Type</label>
    <select name="type" class="form-select">
      <?php foreach (['SUV','Sedan','Van','Truck','Coupe','Hatchback','Other'] as $t): ?>
        <option value="<?= $t ?>"><?= $t ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="col-12 col-md-4">
    <label class="form-label">Price per Day (PHP)</label>
    <input type="number" step="0.01" name="price_per_day" class="form-control" required>
  </div>
  <div class="col-12 col-md-4">
    <label class="form-label">Availability</label>
    <select name="availability" class="form-select">
      <option value="available">Available</option>
      <option value="unavailable">Unavailable</option>
    </select>
  </div>
  <div class="col-12">
    <label class="form-label">Image URL</label>
    <input type="url" name="image" class="form-control" placeholder="https://example.com/car.jpg">
  </div>
  <div class="col-12">
    <label class="form-label">Description</label>
    <textarea name="description" class="form-control" rows="4"></textarea>
  </div>
  <div class="col-12">
    <button class="btn btn-primary">Save</button>
  </div>
</form>
<?php include __DIR__ . '/../../../../includes/footer.php'; ?>
