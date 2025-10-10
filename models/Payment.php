<?php
class Payment {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    // Log a new payment
    public function addPayment($data){
        $this->db->query('INSERT INTO payments (booking_id, amount, method, transaction_id, status) VALUES (:booking_id, :amount, :method, :transaction_id, :status)');

        // Bind values
        $this->db->bind(':booking_id', $data['booking_id']);
        $this->db->bind(':amount', $data['amount']);
        $this->db->bind(':method', $data['method']);
        $this->db->bind(':transaction_id', $data['transaction_id']);
        $this->db->bind(':status', $data['status']);

        // Execute
        if($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }

    // Get all payments for admin view
    public function getAllPayments(){
        $this->db->query('SELECT payments.*, users.name as user_name FROM payments
                        JOIN bookings ON payments.booking_id = bookings.id
                        JOIN users ON bookings.user_id = users.id
                        ORDER BY payments.payment_date DESC');
        return $this->db->resultSet();
    }
}
?>