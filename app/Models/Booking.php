<?php
namespace App\Models;

use App\Core\Model;
use PDO;

class Booking extends Model
{
    public static function create(int $userId, int $carId, string $start, string $end, string $pickup, float $total): int
    {
        $stmt = (new static())->db->prepare('INSERT INTO bookings(user_id, car_id, start_date, end_date, pickup_location, total_price, status) VALUES(:user_id,:car_id,:start_date,:end_date,:pickup,:total_price,\'pending\')');
        $stmt->execute([
            'user_id' => $userId,
            'car_id' => $carId,
            'start_date' => $start,
            'end_date' => $end,
            'pickup' => $pickup,
            'total_price' => $total,
        ]);
        return (int)(new static())->db->lastInsertId();
    }

    public static function forUser(int $userId): array
    {
        $stmt = (new static())->db->prepare('SELECT b.*, c.brand, c.model FROM bookings b JOIN cars c ON c.id=b.car_id WHERE b.user_id=:uid ORDER BY b.created_at DESC');
        $stmt->execute(['uid' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }
}
