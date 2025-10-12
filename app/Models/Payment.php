<?php
namespace App\Models;

use App\Core\Model;
use PDO;

class Payment extends Model
{
    public static function createPending(int $bookingId, float $amount, string $method): int
    {
        $stmt = (new static())->db->prepare('INSERT INTO payments(booking_id, amount, method, status) VALUES(:booking_id,:amount,:method,\'pending\')');
        $stmt->execute([
            'booking_id' => $bookingId,
            'amount' => $amount,
            'method' => $method,
        ]);
        return (int)(new static())->db->lastInsertId();
    }
}
