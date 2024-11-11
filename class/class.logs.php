<?php
/*Creates Logs Object with database connection */
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

    public function addLog($action, $description, $adm_id) {
        $log_date = date('Y-m-d');
        $log_time = date('H:i:s');

        $query = "INSERT INTO logs (log_action, log_description, log_time_managed, log_date_managed, adm_id) 
                  VALUES (:action, :description, :log_time, :log_date, :adm_id)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':action', $action);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':log_time', $log_time);
        $stmt->bindParam(':log_date', $log_date);
        $stmt->bindParam(':adm_id', $adm_id);

        return $stmt->execute();
    }

    public function list_logs() {
$sql = "SELECT logs.*, 
   admin.adm_fname, 
   admin.adm_lname, 
   nurse.nurse_fname, 
   nurse.nurse_lname 
FROM logs 
LEFT JOIN admin ON logs.adm_id = admin.adm_id 
LEFT JOIN nurse ON logs.nurse_id = nurse.nurse_id"; 

$q = $this->conn->query($sql);
$data = [];

while ($r = $q->fetch(PDO::FETCH_ASSOC)) {
$data[] = $r;
}

return empty($data) ? false : $data;
}

public function fetch_log($filter = null, $startDate = null, $endDate = null) {
        $query = "SELECT logs.*, 
                         admin.adm_fname, 
                         admin.adm_lname, 
                         nurse.nurse_fname, 
                         nurse.nurse_lname 
                  FROM logs 
                  JOIN admin ON logs.adm_id = admin.adm_id 
                  JOIN nurse ON logs.nurse_id = nurse.nurse_id WHERE 1=1"; 

        if ($filter) {
            $query .= " AND logs.log_action LIKE :filter"; 
        }

        if ($startDate) {
            $query .= " AND logs.log_date_managed >= :startDate"; 
        }

        if ($endDate) {
            $query .= " AND logs.log_date_managed <= :endDate"; 
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