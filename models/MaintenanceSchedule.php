<?php
class MaintenanceSchedule {
    private $conn;
    private $table = 'maintenance_schedules';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllByUser($userId) {
        $query = "SELECT ms.*, st.name as service_name 
                 FROM " . $this->table . " ms
                 JOIN service_types st ON ms.service_type_id = st.service_type_id
                 WHERE ms.user_id = :user_id
                 ORDER BY ms.scheduled_date DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        try {
            $query = "INSERT INTO " . $this->table . " 
                     (user_id, service_type_id, scheduled_date, status) 
                     VALUES (:user_id, :service_type_id, :scheduled_date, 'scheduled')";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $data['user_id'], PDO::PARAM_INT);
            $stmt->bindParam(':service_type_id', $data['service_type_id'], PDO::PARAM_INT);
            $stmt->bindParam(':scheduled_date', $data['scheduled_date']);
            
            return $stmt->execute();
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function updateStatus($scheduleId, $status) {
        $query = "UPDATE " . $this->table . " 
                 SET status = :status, updated_at = CURRENT_TIMESTAMP 
                 WHERE schedule_id = :schedule_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':schedule_id', $scheduleId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getById($scheduleId) {
        $query = "SELECT ms.*, st.name as service_name 
                 FROM " . $this->table . " ms
                 JOIN service_types st ON ms.service_type_id = st.service_type_id
                 WHERE ms.schedule_id = :schedule_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':schedule_id', $scheduleId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function countByUserAndStatus($userId, $status) {
        $query = "SELECT COUNT(*) FROM " . $this->table . " 
                 WHERE user_id = :user_id AND status = :status";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':status', $status);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getAllWithUserDetails() {
        $query = "SELECT ms.*, st.name as service_name, u.fullname 
                 FROM " . $this->table . " ms
                 JOIN service_types st ON ms.service_type_id = st.service_type_id
                 JOIN users u ON ms.user_id = u.user_id
                 ORDER BY ms.scheduled_date DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
