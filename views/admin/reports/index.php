<?php require_once APPROOT . '/views/admin/includes/header.php'; ?>

<h1 class="h3 mb-4 text-gray-800"><?php echo $data['title']; ?></h1>

<!-- Filter Form -->
<div class="card shadow mb-4">
    <div class="card-header">
        Filter Bookings by Date
    </div>
    <div class="card-body">
        <form action="<?php echo SITE_URL; ?>/admin/reports" method="post">
            <div class="row">
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="start_date">Start Date</label>
                        <input type="date" name="start_date" class="form-control" value="<?php echo $data['start_date']; ?>" required>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="end_date">End Date</label>
                        <input type="date" name="end_date" class="form-control" value="<?php echo $data['end_date']; ?>" required>
                    </div>
                </div>
                <div class="col-md-2">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-primary btn-block w-100">Filter</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Results Table -->
<div class="card shadow mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Filtered Results</span>
        <?php if(!empty($data['bookings'])): ?>
            <a href="<?php echo SITE_URL; ?>/admin/reports/export_csv/<?php echo $data['start_date']; ?>/<?php echo $data['end_date']; ?>" class="btn btn-success btn-sm">
                <i class="bi bi-file-earmark-spreadsheet"></i> Export to CSV
            </a>
        <?php endif; ?>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>User</th>
                        <th>Car</th>
                        <th>Dates</th>
                        <th>Total Price</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($data['bookings'])): ?>
                        <?php foreach($data['bookings'] as $booking): ?>
                        <tr>
                            <td><?php echo $booking->id; ?></td>
                            <td><?php echo $booking->user_name; ?></td>
                            <td><?php echo $booking->brand . ' ' . $booking->model; ?></td>
                            <td><?php echo date('d M Y', strtotime($booking->start_date)); ?> to <?php echo date('d M Y', strtotime($booking->end_date)); ?></td>
                            <td>$<?php echo $booking->total_price; ?></td>
                            <td><?php echo $booking->status; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No bookings found for the selected date range.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once APPROOT . '/views/admin/includes/footer.php'; ?>