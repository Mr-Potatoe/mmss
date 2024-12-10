<?php
class User {
    private $conn;
    private $table = 'users';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function login($email, $password) {
        $query = "SELECT user_id, fullname, password, role FROM users WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            return [
                'user_id' => $user['user_id'],
                'fullname' => $user['fullname'],
                'role' => $user['role']
            ];
        }
        
        return false;
    }

    public function register($data) {
        $query = "INSERT INTO " . $this->table . " 
                 (fullname, email, password, role) 
                 VALUES (:fullname, :email, :password, :role)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':fullname', $data['fullname']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $data['password']);
        $stmt->bindParam(':role', $data['role']);

        return $stmt->execute();
    }

    public function emailExists($email) {
        $query = "SELECT email FROM " . $this->table . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }

    public function getAllRiders() {
        $query = "SELECT * FROM users WHERE role = 'rider' ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function toggleStatus($userId) {
        $query = "UPDATE users SET is_active = NOT is_active WHERE user_id = :user_id AND role = 'rider'";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([':user_id' => $userId]);
    }

    public function getById($userId) {
        $query = "SELECT * FROM users WHERE user_id = :user_id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
