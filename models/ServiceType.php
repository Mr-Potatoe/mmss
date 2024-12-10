<?php
class ServiceType {
    private $conn;
    private $table = 'service_types';
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function getAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY name ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE service_type_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($name, $description, $isActive) {
        $query = "INSERT INTO " . $this->table . " (name, description, is_active) 
                 VALUES (:name, :description, :is_active)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $isActive = $isActive ? 1 : 0;
        $stmt->bindParam(':is_active', $isActive, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    public function update($id, $name, $description, $isActive) {
        $query = "UPDATE " . $this->table . " 
                 SET name = :name, description = :description, is_active = :is_active 
                 WHERE service_type_id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $isActive = $isActive ? 1 : 0;
        $stmt->bindParam(':is_active', $isActive, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    public function delete($id) {
        // First check if the service type is being used
        $checkQuery = "SELECT COUNT(*) FROM service_requests WHERE service_type_id = :id";
        $stmt = $this->conn->prepare($checkQuery);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        if ($stmt->fetchColumn() > 0) {
            // If service type is being used, just deactivate it
            $query = "UPDATE " . $this->table . " SET is_active = 0 WHERE service_type_id = :id";
        } else {
            // If not being used, delete it
            $query = "DELETE FROM " . $this->table . " WHERE service_type_id = :id";
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getActiveServices() {
        $query = "SELECT * FROM " . $this->table . " WHERE is_active = 1 ORDER BY name ASC";
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
}
