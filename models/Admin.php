<?php
class Admin {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    public function getTotalUsers(){
        $this->db->query("SELECT COUNT(*) as count FROM users WHERE role = 'user'");
        $row = $this->db->single();
        return $row->count;
    }

    public function getTotalCars(){
        $this->db->query("SELECT COUNT(*) as count FROM cars");
        $row = $this->db->single();
        return $row->count;
    }

    public function getTotalBookings(){
        $this->db->query("SELECT COUNT(*) as count FROM bookings");
        $row = $this->db->single();
        return $row->count;
    }

    public function getTotalRevenue(){
        $this->db->query("SELECT SUM(total_price) as total FROM bookings WHERE status = 'Completed'");
        $row = $this->db->single();
        return $row->total ?? 0;
    }
}
?>