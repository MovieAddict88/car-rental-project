<?php
// Note: For actual PDF generation, a library like FPDF or TCPDF would be ideal.
// This script generates a print-friendly HTML invoice, which can be saved as PDF from the browser's print dialog.

require_once 'config/config.php';

// Check if user is logged in (optional, but good practice)
if (!isset($_SESSION['user_id'])) {
    header("Location: " . SITE_URL . "/user/login.php?message=login_required");
    exit();
}

// Check if a booking ID is provided
if (!isset($_GET['booking_id'])) {
    die("Error: No booking ID specified.");
}

$booking_id = (int)$_GET['booking_id'];

// --- Placeholder Data ---
// In a real application, you would perform database queries to get all the necessary details
// for the booking, user, and car based on the $booking_id.
$booking = [
    'id' => $booking_id,
    'start_date' => '2025-11-01',
    'end_date' => '2025-11-05',
    'total_price' => 220.00,
    'booking_date' => '2025-10-20',
    'status' => 'Confirmed'
];
$user = [
    'name' => $_SESSION['user_name'] ?? 'John Doe',
    'email' => $_SESSION['user_email'] ?? 'john.doe@example.com'
];
$car = [
    'brand' => 'Toyota',
    'model' => 'Camry',
    'price_per_day' => 55.00
];
$settings = [
    'site_name' => 'Car Rental Management System',
    'contact_email' => 'support@crms.com',
    'contact_phone' => '+1234567890'
];

// Calculate number of days for the rental
$days = (strtotime($booking['end_date']) - strtotime($booking['start_date'])) / (60 * 60 * 24);
if ($days <= 0) $days = 1; // Minimum 1 day rental

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice #<?php echo $booking['id']; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
            color: #333;
        }
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 40px;
            border: 1px solid #ddd;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 40px;
        }
        .invoice-header h1 {
            margin: 0;
            color: #0d6efd;
        }
        .company-details {
            text-align: right;
        }
        .billing-details {
            margin-bottom: 40px;
        }
        .billing-details h3 {
            margin-top: 0;
            border-bottom: 2px solid #eee;
            padding-bottom: 5px;
        }
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .invoice-table th, .invoice-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .invoice-table th {
            background-color: #f8f8f8;
        }
        .invoice-total {
            text-align: right;
        }
        .invoice-total table {
            width: 40%;
            float: right;
        }
        .invoice-total td {
            padding: 10px;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 0.9em;
            color: #777;
        }
        .print-button {
            display: block;
            width: 150px;
            margin: 20px auto;
            padding: 10px 15px;
            background-color: #0d6efd;
            color: #fff;
            border: none;
            border-radius: 5px;
            text-align: center;
            cursor: pointer;
            font-size: 16px;
        }
        @media print {
            body {
                background-color: #fff;
            }
            .invoice-container {
                box-shadow: none;
                border: none;
                padding: 0;
            }
            .print-button {
                display: none;
            }
        }
    </style>
</head>
<body>

    <button onclick="window.print()" class="print-button">Print or Save as PDF</button>

    <div class="invoice-container">
        <div class="invoice-header">
            <div>
                <h1>INVOICE</h1>
                <p><strong>Invoice #:</strong> <?php echo $booking['id']; ?></p>
                <p><strong>Date:</strong> <?php echo date('M d, Y'); ?></p>
            </div>
            <div class="company-details">
                <strong><?php echo htmlspecialchars($settings['site_name']); ?></strong><br>
                <?php echo htmlspecialchars($settings['contact_email']); ?><br>
                <?php echo htmlspecialchars($settings['contact_phone']); ?>
            </div>
        </div>

        <div class="billing-details">
            <h3>Bill To:</h3>
            <p>
                <strong><?php echo htmlspecialchars($user['name']); ?></strong><br>
                <?php echo htmlspecialchars($user['email']); ?>
            </p>
        </div>

        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Rental Period</th>
                    <th>Rate per Day</th>
                    <th>Days</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo htmlspecialchars($car['brand'] . ' ' . $car['model']); ?></td>
                    <td><?php echo date('M d, Y', strtotime($booking['start_date'])) . ' - ' . date('M d, Y', strtotime($booking['end_date'])); ?></td>
                    <td>$<?php echo number_format($car['price_per_day'], 2); ?></td>
                    <td><?php echo $days; ?></td>
                    <td>$<?php echo number_format($booking['total_price'], 2); ?></td>
                </tr>
            </tbody>
        </table>

        <div class="invoice-total">
             <table>
                <tr>
                    <td><strong>Subtotal:</strong></td>
                    <td>$<?php echo number_format($booking['total_price'], 2); ?></td>
                </tr>
                 <tr>
                    <td><strong>Tax (0%):</strong></td>
                    <td>$0.00</td>
                </tr>
                <tr>
                    <td style="border-top: 2px solid #333;"><strong>Total Amount Due:</strong></td>
                    <td style="border-top: 2px solid #333;"><strong>$<?php echo number_format($booking['total_price'], 2); ?></strong></td>
                </tr>
            </table>
        </div>

        <div style="clear:both;"></div>

        <div class="footer">
            <p>Thank you for your business!</p>
            <p>Payment is due upon receipt. Please contact us with any questions.</p>
        </div>
    </div>

</body>
</html>