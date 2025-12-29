<?php
require_once '../config/database.php';

class ScheduleModel {
    private $conn;
    private $table_name = "schedules";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($train_id, $from_station, $to_station, $departure_time, $expected_arrival) {
    // Format dates for SQL Server (remove T and add space)
    $departure_formatted = str_replace('T', ' ', $departure_time) . ':00';  // Add seconds if missing
    $arrival_formatted = str_replace('T', ' ', $expected_arrival) . ':00';  // Add seconds if missing

    $query = "INSERT INTO " . $this->table_name . " 
              (train_id, from_station, to_station, departure_time, expected_arrival) 
              VALUES (:train_id, :from_station, :to_station, :departure_time, :expected_arrival)";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':train_id', $train_id, PDO::PARAM_INT);
    $stmt->bindParam(':from_station', $from_station, PDO::PARAM_INT);
    $stmt->bindParam(':to_station', $to_station, PDO::PARAM_INT);
    $stmt->bindParam(':departure_time', $departure_formatted);
    $stmt->bindParam(':expected_arrival', $arrival_formatted);
    return $stmt->execute();
}

    public function getAll() {
        $query = "SELECT s.*, t.name as train_name, fs.name as from_name, ts.name as to_name 
                  FROM " . $this->table_name . " s 
                  JOIN trains t ON s.train_id = t.id 
                  JOIN stations fs ON s.from_station = fs.id 
                  JOIN stations ts ON s.to_station = ts.id";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByStations($from, $to) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE from_station = :from AND to_station = :to";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':from', $from);
        $stmt->bindParam(':to', $to);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Get all stations for booking form
    public function getAllStations() {
    $query = "SELECT * FROM stations";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}
?>