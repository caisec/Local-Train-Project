<?php
require_once '../config/database.php';

class TicketModel {
    private $conn;
    private $table_name = "tickets";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function book($user_id, $schedule_id, $ticket_type, $class, $price) {
        $query = "INSERT INTO " . $this->table_name . " 
                  (user_id, schedule_id, ticket_type, class, price, status) 
                  VALUES (:user_id, :schedule_id, :ticket_type, :class, :price, 'booked')";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':schedule_id', $schedule_id);
        $stmt->bindParam(':ticket_type', $ticket_type);
        $stmt->bindParam(':class', $class);
        $stmt->bindParam(':price', $price);

        if ($stmt->execute()) {
            // Deduct from user balance
            $update_balance = "UPDATE users SET balance = balance - :price WHERE id = :user_id";
            $stmt_balance = $this->conn->prepare($update_balance);
            $stmt_balance->bindParam(':price', $price);
            $stmt_balance->bindParam(':user_id', $user_id);
            $stmt_balance->execute();
            return $this->conn->lastInsertId();
        }
        return false;
    }

    public function getByUser($user_id) {
        $query = "SELECT t.*, s.departure_time, s.expected_arrival, s.from_station, s.to_station 
                  FROM " . $this->table_name . " t 
                  JOIN schedules s ON t.schedule_id = s.id 
                  WHERE t.user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getReceipt($ticket_id) {
    $query = "SELECT 
                t.*, 
                u.name as user_name,
                tr.name as train_name,
                sf.name as from_station,
                st.name as to_station,
                s.departure_time,
                s.expected_arrival,
                rtu.delay_minutes
              FROM " . $this->table_name . " t
              JOIN users u ON t.user_id = u.id
              JOIN schedules s ON t.schedule_id = s.id
              JOIN trains tr ON s.train_id = tr.id
              JOIN stations sf ON s.from_station = sf.id
              JOIN stations st ON s.to_station = st.id
              LEFT JOIN real_time_updates rtu ON s.train_id = rtu.train_id
              WHERE t.id = :id";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $ticket_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
    public function delete($ticket_id) {
    $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $ticket_id, PDO::PARAM_INT);
    return $stmt->execute();
}


public function getAll() {
    $query = "SELECT * FROM " . $this->table_name;
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getAllTickets() {

    $query = "SELECT 
                t.*, 
                u.name as user_name,
                tr.name as train_name,
                sf.name as from_station,
                st.name as to_station,
                s.departure_time,
                s.expected_arrival
              FROM tickets t
              JOIN users u ON t.user_id = u.id
              JOIN schedules s ON t.schedule_id = s.id
              JOIN trains tr ON s.train_id = tr.id
              JOIN stations sf ON s.from_station = sf.id
              JOIN stations st ON s.to_station = st.id";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getTicketCount() {
    $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total'] ?? 0;
}
}
?>