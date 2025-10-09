<?php
class Car {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM cars WHERE availability = 1");
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM cars WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($brand, $model, $type, $price_per_day, $image) {
        $stmt = $this->pdo->prepare("INSERT INTO cars (brand, model, type, price_per_day, image) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$brand, $model, $type, $price_per_day, $image]);
    }

    public function update($id, $brand, $model, $type, $price_per_day, $availability, $image = null) {
        if ($image) {
            $stmt = $this->pdo->prepare("UPDATE cars SET brand = ?, model = ?, type = ?, price_per_day = ?, availability = ?, image = ? WHERE id = ?");
            return $stmt->execute([$brand, $model, $type, $price_per_day, $availability, $image, $id]);
        } else {
            $stmt = $this->pdo->prepare("UPDATE cars SET brand = ?, model = ?, type = ?, price_per_day = ?, availability = ? WHERE id = ?");
            return $stmt->execute([$brand, $model, $type, $price_per_day, $availability, $id]);
        }
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM cars WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>