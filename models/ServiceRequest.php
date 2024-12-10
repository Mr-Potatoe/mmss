<?php
class ServiceRequest {
    private $conn;
    private $table = 'service_requests';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function countByStatus($status) {
        $query = "SELECT COUNT(*) FROM " . $this->table . " WHERE status = :status";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':status' => $status]);
        return $stmt->fetchColumn();
    }

    public function getRecent($limit = 5) {
        $query = "SELECT r.*, s.name as service_name, u.fullname as customer_name 
                 FROM " . $this->table . " r
                 JOIN service_types s ON r.service_type_id = s.service_type_id
                 JOIN users u ON r.user_id = u.user_id
                 ORDER BY r.created_at DESC LIMIT :limit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $sql = "SELECT sr.*, 
                st.name as service_name,
                u.fullname as customer_name,
                u.email as customer_email,
                sr.contact_number as customer_contact
                FROM service_requests sr
                JOIN service_types st ON sr.service_type_id = st.service_type_id
                JOIN users u ON sr.user_id = u.user_id
                WHERE sr.request_id = :id";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll() {
        $sql = "SELECT sr.*, 
                st.name as service_name,
                u.fullname as customer_name,
                m.name as mechanic_name
                FROM service_requests sr
                JOIN service_types st ON sr.service_type_id = st.service_type_id
                JOIN users u ON sr.user_id = u.user_id
                LEFT JOIN mechanics m ON sr.mechanic_id = m.mechanic_id
                ORDER BY sr.created_at DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function approve($requestId) {
        try {
            $this->conn->beginTransaction();

            // Update service request status
            $sql = "UPDATE service_requests 
                    SET status = 'approved' 
                    WHERE request_id = :request_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':request_id', $requestId, PDO::PARAM_INT);
            $result = $stmt->execute();

            if (!$result) {
                throw new Exception("Failed to update service request");
            }

            // Get service request details and default mechanic
            $sql = "SELECT service_type_id FROM service_requests WHERE request_id = :request_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':request_id', $requestId, PDO::PARAM_INT);
            $stmt->execute();
            $request = $stmt->fetch(PDO::FETCH_ASSOC);

            // Get first available mechanic (temporary solution)
            $sql = "SELECT mechanic_id FROM mechanics WHERE is_active = 1 LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $mechanic = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$mechanic) {
                throw new Exception("No active mechanic available");
            }

            // Create approved service record with a default mechanic
            $sql = "INSERT INTO approved_services (request_id, service_type_id, mechanic_id) 
                    VALUES (:request_id, :service_type_id, :mechanic_id)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':request_id', $requestId, PDO::PARAM_INT);
            $stmt->bindParam(':service_type_id', $request['service_type_id'], PDO::PARAM_INT);
            $stmt->bindParam(':mechanic_id', $mechanic['mechanic_id'], PDO::PARAM_INT);
            $result = $stmt->execute();

            if (!$result) {
                throw new Exception("Failed to create approved service record");
            }

            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log($e->getMessage()); // Log the error for debugging
            return false;
        }
    }

    public function assignMechanic($requestId, $mechanicId) {
        try {
            $this->conn->beginTransaction();

            // Update service request with mechanic
            $sql = "UPDATE service_requests 
                    SET mechanic_id = :mechanic_id 
                    WHERE request_id = :request_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':mechanic_id', $mechanicId, PDO::PARAM_INT);
            $stmt->bindParam(':request_id', $requestId, PDO::PARAM_INT);
            $stmt->execute();

            // Update approved service record
            $sql = "UPDATE approved_services 
                    SET mechanic_id = :mechanic_id 
                    WHERE request_id = :request_id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':mechanic_id', $mechanicId, PDO::PARAM_INT);
            $stmt->bindParam(':request_id', $requestId, PDO::PARAM_INT);
            $stmt->execute();

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
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

    public function getAllByUser($userId) {
        $query = "SELECT sr.*, 
                  st.name as service_name,
                  m.name as mechanic_name,
                  a.completion_notes,
                  a.completion_status
                  FROM " . $this->table . " sr
                  JOIN service_types st ON sr.service_type_id = st.service_type_id
                  LEFT JOIN mechanics m ON sr.mechanic_id = m.mechanic_id
                  LEFT JOIN approved_services a ON sr.request_id = a.request_id
                  WHERE sr.user_id = :user_id
                  ORDER BY sr.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByIdAndUser($requestId, $userId) {
        $query = "SELECT sr.*, 
                  st.name as service_name,
                  m.name as mechanic_name,
                  a.completion_notes,
                  a.completion_status
                  FROM " . $this->table . " sr
                  JOIN service_types st ON sr.service_type_id = st.service_type_id
                  LEFT JOIN mechanics m ON sr.mechanic_id = m.mechanic_id
                  LEFT JOIN approved_services a ON sr.request_id = a.request_id
                  WHERE sr.request_id = :request_id 
                  AND sr.user_id = :user_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':request_id', $requestId, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        try {
            $query = "INSERT INTO " . $this->table . " 
                     (user_id, service_type_id, schedule_date, contact_number, status) 
                     VALUES (:user_id, :service_type_id, :schedule_date, :contact_number, 'pending')";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $data['user_id'], PDO::PARAM_INT);
            $stmt->bindParam(':service_type_id', $data['service_type_id'], PDO::PARAM_INT);
            $stmt->bindParam(':schedule_date', $data['schedule_date']);
            $stmt->bindParam(':contact_number', $data['contact_number']);
            
            return $stmt->execute();
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}
