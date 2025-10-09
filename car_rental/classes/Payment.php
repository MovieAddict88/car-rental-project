<?php
class Payment {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function create($booking_id, $amount, $method, $transaction_id, $status) {
        $stmt = $this->pdo->prepare("INSERT INTO payments (booking_id, amount, method, transaction_id, status) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$booking_id, $amount, $method, $transaction_id, $status]);
    }

    public function updateBookingAndPaymentStatus($booking_id, $payment_id, $status) {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare("UPDATE bookings SET status = ? WHERE id = ?");
            $stmt->execute([$status, $booking_id]);

            $stmt = $this->pdo->prepare("UPDATE payments SET status = ? WHERE id = ?");
            $stmt->execute([$status, $payment_id]);

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }
}
?>