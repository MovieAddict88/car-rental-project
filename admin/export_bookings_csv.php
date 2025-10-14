<?php
require_once('includes/header.php');

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=bookings.csv');

$output = fopen('php://output', 'w');

fputcsv($output, [
    'Booking ID', 'User Name', 'User Email', 'Car', 'Start Date', 'End Date', 'Total Price', 'Status', 'Created At'
]);

$stmt = $pdo->query(
    "SELECT b.id, u.name AS user_name, u.email AS user_email, CONCAT(c.brand,' ',c.model) AS car,
            b.start_date, b.end_date, b.total_price, b.status, b.created_at
     FROM bookings b
     JOIN users u ON b.user_id = u.id
     JOIN cars c ON b.car_id = c.id
     ORDER BY b.created_at DESC"
);

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($output, [
        $row['id'],
        $row['user_name'],
        $row['user_email'],
        $row['car'],
        $row['start_date'],
        $row['end_date'],
        number_format((float)$row['total_price'], 2),
        $row['status'],
        $row['created_at']
    ]);
}

fclose($output);
exit;
