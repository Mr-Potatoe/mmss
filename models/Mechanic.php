<?php
class Mechanic {
    private $conn;
    private $table = 'mechanics';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getActive() {
        $query = "SELECT * FROM " . $this->table . " WHERE is_active = 1 ORDER BY name ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $query = "INSERT INTO " . $this->table . " (name, contact_number) VALUES (:name, :contact_number)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':name' => $data['name'],
            ':contact_number' => $data['contact_number']
        ]);
    }

    public function update($data) {
        $query = "UPDATE " . $this->table . " 
                 SET name = :name, contact_number = :contact_number, is_active = :is_active 
                 WHERE mechanic_id = :mechanic_id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ':mechanic_id' => $data['mechanic_id'],
            ':name' => $data['name'],
            ':contact_number' => $data['contact_number'],
            ':is_active' => $data['is_active']
        ]);
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE mechanic_id = :mechanic_id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([':mechanic_id' => $id]);
    }

    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE mechanic_id = :mechanic_id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':mechanic_id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
