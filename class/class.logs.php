<?php
class Log {
    private $DB_SERVER = 'localhost';
    private $DB_USERNAME = 'root';
    private $DB_PASSWORD = '';
    private $DB_DATABASE = 'db_careshift';
    private $conn;

    public function __construct() {
        $this->conn = new PDO("mysql:host=" . $this->DB_SERVER . ";dbname=" . $this->DB_DATABASE, $this->DB_USERNAME, $this->DB_PASSWORD);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
    }

    public function addLog($action, $description, $adm_id, $nurse_id = null) {
        $log_date = date('Y-m-d');
        $log_time = date('H:i:s');

        $query = "INSERT INTO logs (log_action, log_description, log_time_managed, log_date_managed, adm_id, nurse_id) 
                  VALUES (:action, :description, :log_time, :log_date, :adm_id, :nurse_id)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':action', $action);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':log_time', $log_time);
        $stmt->bindParam(':log_date', $log_date);
        $stmt->bindParam(':adm_id', $adm_id);
        $stmt->bindParam(':nurse_id', $nurse_id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function list_logs() {
        $sql = "SELECT l.*, 
                       a.adm_fname, a.adm_lname, 
                       n.nurse_fname, n.nurse_lname 
                FROM logs AS l
                LEFT JOIN admin AS a ON l.adm_id = a.adm_id 
                LEFT JOIN nurse AS n ON l.nurse_id = n.nurse_id"; 

        $stmt = $this->conn->query($sql);
        $data = [];

        while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $r;
        }

        return !empty($data) ? $data : false;
    }
    

    public function fetch_log($filter = null, $startDate = null, $endDate = null) {
        $query = "SELECT l.*, 
                         a.adm_fname, 
                         a.adm_lname, 
                         n.nurse_fname, 
                         n.nurse_lname 
                  FROM logs AS l
                  JOIN admin AS a ON l.adm_id = a.adm_id 
                  JOIN nurse AS n ON l.nurse_id = n.nurse_id WHERE 1=1"; 

        if ($filter) {
            $query .= " AND l.log_action LIKE :filter"; 
        }

        if ($startDate) {
            $query .= " AND l.log_date_managed >= :startDate"; 
        }

        if ($endDate) {
            $query .= " AND l.log_date_managed <= :endDate"; 
        }

        $query .= " ORDER BY logs.log_date_managed DESC"; 

        $stmt = $this->conn->prepare($query);

        if ($filter) {
            $stmt->bindValue(':filter', "%$filter%");
        }
        if ($startDate) {
            $stmt->bindValue(':startDate', $startDate);
        }
        if ($endDate) {
            $stmt->bindValue(':endDate', $endDate);
        }

        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return empty($data) ? false : $data; 
    }
}
?>