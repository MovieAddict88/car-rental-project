<?php
require_once '../config.php';
require_once '../classes/Booking.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['booking_id'])) {
    die("Booking ID is required.");
}

$booking_id = $_GET['booking_id'];

$stmt = $pdo->prepare("
    SELECT b.*, u.name as user_name, u.email as user_email, c.brand, c.model, c.price_per_day
    FROM bookings b
    JOIN users u ON b.user_id = u.id
    JOIN cars c ON b.car_id = c.id
    WHERE b.id = ? AND b.user_id = ?
");
$stmt->execute([$booking_id, $_SESSION['user_id']]);
$booking = $stmt->fetch();

if (!$booking) {
    die("Invoice not found or you do not have permission to view it.");
}

// Fetch company settings
$settings_stmt = $pdo->query("SELECT * FROM settings");
$settings = $settings_stmt->fetchAll(PDO::FETCH_KEY_PAIR);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #<?php echo $booking['id']; ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .invoice-container { max-width: 800px; margin: 50px auto; background: #fff; padding: 30px; border: 1px solid #dee2e6; }
        .invoice-header { text-align: center; margin-bottom: 30px; }
        .invoice-header h1 { font-size: 2.5rem; }
        @media print {
            body { background-color: #fff; }
            .no-print { display: none; }
            .invoice-container { border: none; margin: 0; max-width: 100%; }
        }
    </style>
</head>
<body>
<div class="invoice-container">
    <div class="invoice-header">
        <h1>Invoice</h1>
        <p><strong>Invoice #:</strong> <?php echo $booking['id']; ?></p>
        <p><strong>Date:</strong> <?php echo date('Y-m-d'); ?></p>
    </div>

    <div class="row">
        <div class="col-md-6">
            <h4>From:</h4>
            <p>
                <strong><?php echo $settings['company_name'] ?? 'Car Rental Inc.'; ?></strong><br>
                Email: <?php echo $settings['contact_email'] ?? 'contact@carrental.com'; ?>
            </p>
        </div>
        <div class="col-md-6 text-md-right">
            <h4>To:</h4>
            <p>
                <strong><?php echo $booking['user_name']; ?></strong><br>
                Email: <?php echo $booking['user_email']; ?>
            </p>
        </div>
    </div>

    <h4 class="mt-4">Booking Details</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Car</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Price per Day</th>
                <th>Total Price</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo $booking['brand'] . ' ' . $booking['model']; ?></td>
                <td><?php echo $booking['start_date']; ?></td>
                <td><?php echo $booking['end_date']; ?></td>
                <td>$<?php echo $booking['price_per_day']; ?></td>
                <td>$<?php echo $booking['total_price']; ?></td>
            </tr>
        </tbody>
    </table>

    <div class="text-right mt-4">
        <h4><strong>Total Amount: $<?php echo $booking['total_price']; ?></strong></h4>
    </div>

    <div class="mt-5 text-center no-print">
        <p class="text-muted">
            <em>
                DEVELOPER NOTE: PDF generation requires a library like FPDF or Dompdf.
                Due to environment limitations, this feature is not implemented.
                You can install a library and generate a PDF from this HTML content.
            </em>
        </p>
        <button class="btn btn-primary" onclick="window.print()">Print Invoice</button>
        <a href="bookings.php" class="btn btn-secondary">Back to Bookings</a>
    </div>
</div>
</body>
</html>