<?php
include_once 'config.php';

class Database {
    private static $instance = null;
    private $conn;
    private $host = "localhost";
    private $db_name = "russgarde03_careshift";
    private $username = "russgarde03_careshift";
    private $password = "romlijgards03";

    private function __construct() {
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
            $this->conn->exec("set time_zone = '+08:00'");
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance->conn;
    }
}

?>