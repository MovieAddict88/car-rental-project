<?php
class User {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    // Find user by email
    public function findUserByEmail($email){
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);

        $row = $this->db->single();

        // Check row
        if($this->db->rowCount() > 0){
            return $row;
        } else {
            return false;
        }
    }

    // Register user
    public function register($data){
        $this->db->query('INSERT INTO users (name, email, password) VALUES(:name, :email, :password)');
        // Bind values
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);

        // Execute
        if($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }

    // Login User
    public function login($email, $password){
        $row = $this->findUserByEmail($email);

        if($row == false){
            return false;
        }

        $hashed_password = $row->password;
        if(password_verify($password, $hashed_password)){
            return $row;
        } else {
            return false;
        }
    }

    // Update Profile
    public function updateProfile($data){
        $this->db->query('UPDATE users SET name = :name, email = :email WHERE id = :id');
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);

        if($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }

    // Change Password
    public function changePassword($data){
        $this->db->query('UPDATE users SET password = :password WHERE id = :id');
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':password', $data['new_password']);

        if($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }

    // Admin: Get all users
    public function getAllUsers(){
        $this->db->query("SELECT * FROM users WHERE role != 'admin'");
        return $this->db->resultSet();
    }

    // Admin: Toggle User Status
    public function toggleUserStatus($id){
        $this->db->query('UPDATE users SET status = !status WHERE id = :id');
        $this->db->bind(':id', $id);
        if($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }

    // Admin: Delete User
    public function deleteUser($id){
        $this->db->query('DELETE FROM users WHERE id = :id');
        $this->db->bind(':id', $id);
        if($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }

    // Get user by ID
    public function getUserById($id){
        $this->db->query('SELECT * FROM users WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
}
?>