<?php
class Database {
    private static $instance = null;  // الـ instance الوحيد
    private $conn;

     private $host = 'DESKTOP-ERKO19L\SQLEXPRESS'; 
     private $db_name = 'LocalTrainDB';
    private $username = '';  
    private $password = '';

    private function __construct() {
        try {
            $dsn = "sqlsrv:Server={$this->host};Database={$this->db_name};TrustServerCertificate=1";
            $this->conn = new PDO($dsn, $this->username, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::SQLSRV_ATTR_ENCODING => PDO::SQLSRV_ENCODING_UTF8
            ]);
        } catch (PDOException $exception) {
            error_log("Connection error: " . $exception->getMessage());
            die("Invalid!!!");
        }
    }

    // الطريقة الوحيدة للحصول على الـ instance
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->conn;
    }

    public function __clone() {}
    public function __wakeup() {}
}
?>