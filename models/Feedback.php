<?php
class Feedback {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    // Add feedback
    public function addFeedback($data){
        $this->db->query('INSERT INTO feedback (user_id, message) VALUES (:user_id, :message)');
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':message', $data['message']);

        if($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }

    // Get all feedback for admin
    public function getAllFeedback(){
        $this->db->query('SELECT feedback.*, users.name as user_name, users.email as user_email FROM feedback
                        JOIN users ON feedback.user_id = users.id
                        ORDER BY feedback.created_at DESC');
        return $this->db->resultSet();
    }

    // Delete feedback
    public function deleteFeedback($id){
        $this->db->query('DELETE FROM feedback WHERE id = :id');
        $this->db->bind(':id', $id);

        if($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }
}
?>