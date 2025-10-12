<?php
namespace App\Models;

use App\Core\Model;
use PDO;

class Car extends Model
{
    public static function getFeatured(int $limit = 6): array
    {
        $stmt = (new static())->db->prepare('SELECT * FROM cars WHERE availability = "available" ORDER BY created_at DESC LIMIT :limit');
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public static function filter(array $filters): array
    {
        $sql = 'SELECT * FROM cars WHERE 1=1';
        $params = [];
        if (!empty($filters['brand'])) { $sql .= ' AND brand LIKE :brand'; $params['brand'] = '%' . $filters['brand'] . '%'; }
        if (!empty($filters['type'])) { $sql .= ' AND type = :type'; $params['type'] = $filters['type']; }
        if (!empty($filters['min_price'])) { $sql .= ' AND price_per_day >= :min_price'; $params['min_price'] = $filters['min_price']; }
        if (!empty($filters['max_price'])) { $sql .= ' AND price_per_day <= :max_price'; $params['max_price'] = $filters['max_price']; }
        if (!empty($filters['availability'])) { $sql .= ' AND availability = :availability'; $params['availability'] = $filters['availability']; }
        $sql .= ' ORDER BY created_at DESC';
        $stmt = (new static())->db->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue(':' . $k, $v);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public static function find(int $id): ?array
    {
        $stmt = (new static())->db->prepare('SELECT * FROM cars WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }
}
