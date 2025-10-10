<?php
class Booking {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    // Create a new booking
    public function createBooking($data){
        $this->db->query('INSERT INTO bookings (user_id, car_id, start_date, end_date, total_price) VALUES (:user_id, :car_id, :start_date, :end_date, :total_price)');

        // Bind values
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':car_id', $data['car_id']);
        $this->db->bind(':start_date', $data['start_date']);
        $this->db->bind(':end_date', $data['end_date']);
        $this->db->bind(':total_price', $data['total_price']);

        // Execute
        if($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }

    // Get bookings by User ID
    public function getBookingsByUserId($user_id){
        $this->db->query('SELECT bookings.*, cars.brand, cars.model, cars.image FROM bookings JOIN cars ON bookings.car_id = cars.id WHERE bookings.user_id = :user_id ORDER BY bookings.created_at DESC');
        $this->db->bind(':user_id', $user_id);

        $results = $this->db->resultSet();
        return $results;
    }

    // Admin: Get all bookings
    public function getAllBookings(){
        $this->db->query('SELECT bookings.*, users.name as user_name, cars.brand, cars.model FROM bookings
                        JOIN users ON bookings.user_id = users.id
                        JOIN cars ON bookings.car_id = cars.id
                        ORDER BY bookings.created_at DESC');
        return $this->db->resultSet();
    }

    // Admin: Get booking by ID
    public function getBookingById($id){
        $this->db->query('SELECT * FROM bookings WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Admin: Update booking status
    public function updateBookingStatus($id, $status){
        $this->db->query('UPDATE bookings SET status = :status WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':status', $status);

        if($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }

    // Admin: Get bookings by date range
    public function getBookingsByDateRange($start_date, $end_date){
        $this->db->query('SELECT bookings.*, users.name as user_name, cars.brand, cars.model FROM bookings
                        JOIN users ON bookings.user_id = users.id
                        JOIN cars ON bookings.car_id = cars.id
                        WHERE bookings.created_at BETWEEN :start_date AND :end_date
                        ORDER BY bookings.created_at DESC');
        $this->db->bind(':start_date', $start_date);
        $this->db->bind(':end_date', $end_date);
        return $this->db->resultSet();
    }
}
?>