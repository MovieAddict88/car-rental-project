<?php
// Payment gateway facade (sandbox-ready stubs)
require __DIR__ . '/app/bootstrap.php';

use App\Models\Payment;

if (session_status() === PHP_SESSION_NONE) {
    session_name(app_config('security.session_name'));
    session_start();
}

$method = $_GET['method'] ?? 'paypal';
$bookingId = (int)($_GET['booking_id'] ?? 0);

// This is a simplified stub. Integrate actual SDKs/keys in production.
Payment::createPending($bookingId, 0, $method);

echo 'Redirecting to sandbox for ' . htmlspecialchars($method) . '...';
