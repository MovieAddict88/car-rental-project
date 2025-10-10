<?php
class Setting {
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    // Get all settings and return as an associative array
    public function getSettings(){
        $this->db->query("SELECT * FROM settings");
        $results = $this->db->resultSet();

        $settings = [];
        foreach($results as $row){
            $settings[$row->setting_key] = $row->setting_value;
        }
        return $settings;
    }

    // Update settings
    public function updateSettings($data){
        foreach($data as $key => $value){
            $this->db->query("UPDATE settings SET setting_value = :value WHERE setting_key = :key");
            $this->db->bind(':key', $key);
            $this->db->bind(':value', $value);
            // Execute inside loop, can be optimized but is fine for few settings
            if(!$this->db->execute()){
                return false;
            }
        }
        return true;
    }
}
?>