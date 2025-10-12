<h1 class="h4 mb-3">My Bookings</h1>
<table class="table table-striped align-middle">
  <thead>
    <tr>
      <th>#</th>
      <th>Car</th>
      <th>Dates</th>
      <th>Status</th>
      <th>Total</th>
      <th>Invoice</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($bookings as $b): ?>
      <tr>
        <td><?= (int)$b['id'] ?></td>
        <td><?= htmlspecialchars(($b['brand'] ?? '') . ' ' . ($b['model'] ?? '')) ?></td>
        <td><?= htmlspecialchars($b['start_date']) ?> → <?= htmlspecialchars($b['end_date']) ?></td>
        <td><span class="badge bg-secondary"><?= htmlspecialchars($b['status']) ?></span></td>
        <td>₱<?= number_format((float)$b['total_price'], 2) ?></td>
        <td><a href="/invoice_generator.php?booking_id=<?= (int)$b['id'] ?>" class="btn btn-sm btn-outline-primary">Download</a></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
