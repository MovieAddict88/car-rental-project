<?php
class Booking {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function create($user_id, $car_id, $start_date, $end_date, $total_price) {
        $stmt = $this->pdo->prepare("INSERT INTO bookings (user_id, car_id, start_date, end_date, total_price) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$user_id, $car_id, $start_date, $end_date, $total_price])) {
            return $this->pdo->lastInsertId();
        }
        return false;
    }

    public function findByUserId($user_id) {
        $stmt = $this->pdo->prepare("SELECT b.*, c.brand, c.model FROM bookings b JOIN cars c ON b.car_id = c.id WHERE b.user_id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM bookings WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
?>