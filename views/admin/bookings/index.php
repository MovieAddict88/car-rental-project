<?php require_once APPROOT . '/views/admin/includes/header.php'; ?>

<h1 class="h3 mb-4 text-gray-800">Manage Bookings</h1>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Car</th>
                        <th>Dates</th>
                        <th>Total Price</th>
                        <th>Status</th>
                        <th>Booked On</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['bookings'] as $booking): ?>
                    <tr>
                        <td><?php echo $booking->user_name; ?></td>
                        <td><?php echo $booking->brand . ' ' . $booking->model; ?></td>
                        <td><?php echo date('d M Y', strtotime($booking->start_date)); ?> to <?php echo date('d M Y', strtotime($booking->end_date)); ?></td>
                        <td>$<?php echo $booking->total_price; ?></td>
                        <td>
                            <?php
                                $status_class = '';
                                switch($booking->status){
                                    case 'Pending': $status_class = 'bg-warning text-dark'; break;
                                    case 'Confirmed': $status_class = 'bg-success'; break;
                                    case 'Cancelled': $status_class = 'bg-danger'; break;
                                    case 'Completed': $status_class = 'bg-info text-dark'; break;
                                }
                            ?>
                            <span class="badge <?php echo $status_class; ?>"><?php echo $booking->status; ?></span>
                        </td>
                        <td><?php echo date('d M Y', strtotime($booking->created_at)); ?></td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                    Actions
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/admin/bookings/update_status/<?php echo $booking->id; ?>/Confirmed">Confirm</a></li>
                                    <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/admin/bookings/update_status/<?php echo $booking->id; ?>/Completed">Mark as Completed</a></li>
                                    <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>/admin/bookings/update_status/<?php echo $booking->id; ?>/Cancelled">Cancel</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once APPROOT . '/views/admin/includes/footer.php'; ?>