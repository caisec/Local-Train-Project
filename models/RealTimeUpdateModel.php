<?php
require_once '../config/database.php';

class RealTimeUpdateModel {
    private $conn;
    private $table_name = "real_time_updates";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function update($train_id, $station_id, $actual_departure = null, $actual_arrival = null, $delay_minutes = 0) {
        $query = "INSERT INTO " . $this->table_name . " 
                  (train_id, station_id, actual_departure, actual_arrival, delay_minutes) 
                  VALUES (:train_id, :station_id, :actual_departure, :actual_arrival, :delay_minutes)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':train_id', $train_id);
        $stmt->bindParam(':station_id', $station_id);
        $stmt->bindParam(':actual_departure', $actual_departure);
        $stmt->bindParam(':actual_arrival', $actual_arrival);
        $stmt->bindParam(':delay_minutes', $delay_minutes);
        return $stmt->execute();
    }

    public function getLatestByTrain($train_id) {
        $query = "SELECT TOP 1 * FROM " . $this->table_name . " WHERE train_id = :train_id ORDER BY update_time DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':train_id', $train_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Calculate average delay for basic prediction (ML is separate)
    public function getAverageDelay($train_id) {
        $query = "SELECT AVG(delay_minutes) as avg_delay FROM " . $this->table_name . " WHERE train_id = :train_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':train_id', $train_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['avg_delay'] ?? 0;
    }

    // ML Prediction using Python script
    public function predictDelay($train_id, $previous_delay, $time_of_day) {
        // Call Python script
        $command = "python ml/predict_delay.py $previous_delay $time_of_day $train_id 2>&1";
        $output = shell_exec($command);
        return floatval(trim($output)) ?: 0;  // Return predicted minutes
    }
}
?>