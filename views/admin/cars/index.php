<?php require_once APPROOT . '/views/admin/includes/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Manage Cars</h1>
    <a href="<?php echo SITE_URL; ?>/admin/cars/add" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add New Car</a>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Brand & Model</th>
                        <th>Type</th>
                        <th>Price/Day</th>
                        <th>Availability</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['cars'] as $car): ?>
                    <tr>
                        <td><img src="<?php echo SITE_URL; ?>/assets/images/cars/<?php echo $car->image; ?>" width="100" class="img-thumbnail"></td>
                        <td><?php echo $car->brand . ' ' . $car->model; ?></td>
                        <td><?php echo $car->type; ?></td>
                        <td>$<?php echo $car->price_per_day; ?></td>
                        <td>
                            <?php if($car->availability): ?>
                                <span class="badge bg-success">Available</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Unavailable</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?php echo SITE_URL; ?>/admin/cars/edit/<?php echo $car->id; ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i></a>
                            <form action="<?php echo SITE_URL; ?>/admin/cars/delete/<?php echo $car->id; ?>" method="post" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this car?');">
                                <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once APPROOT . '/views/admin/includes/footer.php'; ?>