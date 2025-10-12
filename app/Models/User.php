<?php
namespace App\Models;

use App\Core\Model;
use PDO;

class User extends Model
{
    public static function findByEmail(string $email): ?array
    {
        $stmt = (new static())->db->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public static function create(string $name, string $email, string $passwordHash): int
    {
        $stmt = (new static())->db->prepare('INSERT INTO users(name, email, password, role, status) VALUES(:name, :email, :password, "user", "active")');
        $stmt->execute([
            'name' => $name,
            'email' => $email,
            'password' => $passwordHash,
        ]);
        return (int)(new static())->db->lastInsertId();
    }
}
