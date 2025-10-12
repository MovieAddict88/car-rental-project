<?php include __DIR__ . '/../../../../includes/header.php'; ?>
<?php include __DIR__ . '/../../../../includes/admin_navbar.php'; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h4 mb-0">Cars</h1>
  <a href="/admin/cars/create" class="btn btn-primary">Add Car</a>
</div>
<table class="table table-hover align-middle">
  <thead>
    <tr>
      <th>#</th>
      <th>Brand</th>
      <th>Model</th>
      <th>Type</th>
      <th>Price/Day</th>
      <th>Availability</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($cars as $c): ?>
      <tr>
        <td><?= (int)$c['id'] ?></td>
        <td><?= htmlspecialchars($c['brand']) ?></td>
        <td><?= htmlspecialchars($c['model']) ?></td>
        <td><?= htmlspecialchars($c['type']) ?></td>
        <td>₱<?= number_format((float)$c['price_per_day'], 2) ?></td>
        <td><span class="badge bg-<?= $c['availability']==='available'?'success':'secondary' ?>"><?= htmlspecialchars($c['availability']) ?></span></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php include __DIR__ . '/../../../../includes/footer.php'; ?>
