<?php
require_once '../config/database.php';

class UserModel {
    private $conn;
    private $table_name = "users";
    
    public function __construct($db) {
        $this->conn = $db;
    }

    // Register new user
    public function create($name, $email, $password, $role = 'user') {
    try {
        $query = "INSERT INTO " . $this->table_name . " 
                  (name, email, password, balance, role)
                  VALUES (:name, :email, :password, 0, :role)";

        $stmt = $this->conn->prepare($query);
        $hashed_pass = password_hash($password, PASSWORD_DEFAULT);

        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $hashed_pass);
        $stmt->bindParam(":role", $role);

        return $stmt->execute();

    } catch (PDOException $e) {
        // Email already exists
        if ($e->getCode() == 23000) {
            return false;
        }
        throw $e;
    }

    }
    public function login($email, $password) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row && password_verify($password, $row['password'])) {
            return $row;
        }
        return false;
    }

    public function recharge($user_id, $amount) {
    if ($user_id <= 0 || $amount <= 0) {
        error_log("Recharge invalid: user_id=$user_id, amount=$amount");  // Log for debug
        return false;
    }
    $query = "UPDATE " . $this->table_name . " SET balance = balance + :amount WHERE id = :id";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':amount', $amount);  // التصحيح: بدون PARAM_DECIMAL، auto-type
    $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
    if ($stmt->execute()) {
        $rows_affected = $stmt->rowCount();  // Check if updated
        if ($rows_affected > 0) {
            error_log("Recharge success for user $user_id, amount $amount");
            return true;
        } else {
            error_log("Recharge no rows affected for user $user_id");
            return false;
        }
    } else {
        error_log("Recharge execute failed: " . print_r($stmt->errorInfo(), true));
        return false;
    }
}
    

    public function getAll() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    
    public function getBalance($user_id) {
        $query = "SELECT balance FROM users WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $user_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['balance'] ?? 0;
    }
}
?>