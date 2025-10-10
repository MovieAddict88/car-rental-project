<?php require_once APPROOT . '/views/user/includes/header.php'; ?>

<h2 class="text-center mb-4">My Bookings</h2>

<div class="card">
    <div class="card-body">
        <?php if(!empty($data['bookings'])): ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Car</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Booked On</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data['bookings'] as $booking): ?>
                <tr>
                    <td>
                        <img src="<?php echo SITE_URL; ?>/assets/images/cars/<?php echo $booking->image; ?>" width="100" class="img-thumbnail" alt="Car Image">
                        <?php echo $booking->brand . ' ' . $booking->model; ?>
                    </td>
                    <td><?php echo date('d M Y', strtotime($booking->start_date)); ?></td>
                    <td><?php echo date('d M Y', strtotime($booking->end_date)); ?></td>
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
                        <?php if($booking->status == 'Confirmed'): ?>
                             <a href="<?php echo SITE_URL; ?>/user/payments/pay/<?php echo $booking->id; ?>" class="btn btn-sm btn-success">Pay Now</a>
                        <?php endif; ?>
                        <?php if($booking->status == 'Confirmed' || $booking->status == 'Completed'): ?>
                            <a href="<?php echo SITE_URL; ?>/invoice_generator.php?booking_id=<?php echo $booking->id; ?>" class="btn btn-sm btn-info" target="_blank">Invoice</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p class="text-center">You have no bookings yet.</p>
        <?php endif; ?>
    </div>
</div>

<?php require_once APPROOT . '/views/user/includes/footer.php'; ?>