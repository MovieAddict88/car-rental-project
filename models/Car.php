<?php
class Car {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    // Get all cars
    public function getCars(){
        $this->db->query('SELECT * FROM cars WHERE availability = 1 ORDER BY created_at DESC');
        $results = $this->db->resultSet();
        return $results;
    }

    // Get car by ID
    public function getCarById($id){
        $this->db->query('SELECT * FROM cars WHERE id = :id');
        $this->db->bind(':id', $id);
        $row = $this->db->single();
        return $row;
    }

    // Admin: Get all cars regardless of availability
    public function getAllCars(){
        $this->db->query('SELECT * FROM cars ORDER BY created_at DESC');
        $results = $this->db->resultSet();
        return $results;
    }

    // Admin: Add Car
    public function addCar($data){
        $this->db->query('INSERT INTO cars (brand, model, type, price_per_day, image) VALUES (:brand, :model, :type, :price_per_day, :image)');
        $this->db->bind(':brand', $data['brand']);
        $this->db->bind(':model', $data['model']);
        $this->db->bind(':type', $data['type']);
        $this->db->bind(':price_per_day', $data['price_per_day']);
        $this->db->bind(':image', $data['image']);

        if($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }

    // Admin: Update Car
    public function updateCar($data){
        $this->db->query('UPDATE cars SET brand = :brand, model = :model, type = :type, price_per_day = :price_per_day, availability = :availability WHERE id = :id');
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':brand', $data['brand']);
        $this->db->bind(':model', $data['model']);
        $this->db->bind(':type', $data['type']);
        $this->db->bind(':price_per_day', $data['price_per_day']);
        $this->db->bind(':availability', $data['availability']);

        if($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }

    // Admin: Delete Car
    public function deleteCar($id){
        $this->db->query('DELETE FROM cars WHERE id = :id');
        $this->db->bind(':id', $id);

        if($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }

    // Admin: Update Car Availability
    public function updateAvailability($id, $availability){
        $this->db->query('UPDATE cars SET availability = :availability WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':availability', $availability);

        if($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }
}
?>