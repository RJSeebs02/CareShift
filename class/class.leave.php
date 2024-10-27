<?php
class Leave {
    private $DB_SERVER = 'localhost';
    private $DB_USERNAME = 'root';
    private $DB_PASSWORD = '';
    private $DB_DATABASE = 'db_careshift';
    private $conn;
    public function __construct() {
        $this->conn = new PDO("mysql:host=" . $this->DB_SERVER . ";dbname=" . $this->DB_DATABASE, $this->DB_USERNAME, $this->DB_PASSWORD);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Set error mode to exception
    }

    public function new_leave($leave_type, $start_date, $end_date, $description, $status, $nurse_id, $admin) {
        $data = [
            [$leave_type, $start_date, $end_date, $description, $status, $nurse_id, $admin]
        ];
    
        // Escape the table name
        $stmt = $this->conn->prepare("INSERT INTO `leave` (leave_type, leave_start_date, leave_end_date, leave_desc, leave_status, nurse_id, adm_id) VALUES (?,?,?,?,?,?,?)");
        
        try {
            $this->conn->beginTransaction();
            foreach ($data as $row) {
                $stmt->execute($row);
            }
            $this->conn->commit();
        } catch (Exception $e) {
            $this->conn->rollback();
            throw $e;
        }
        return true;
    }
    
    public function list_leave_applications() {
        $sql = "
            SELECT 
                l.leave_id,
                n.nurse_id,
                n.nurse_lname,
                n.nurse_fname,
                n.nurse_email,
                n.nurse_department,
                l.leave_type,
                l.leave_start_date,
                l.leave_end_date,
                l.leave_desc,
                l.leave_status
            FROM 
                `leave` l
            JOIN 
                nurse n ON l.nurse_id = n.nurse_id"; // Make sure this matches your actual FK relationship
        
        $q = $this->conn->query($sql) or die("failed!");
        
        $data = []; // Initialize the array before using it
        while ($r = $q->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $r;
        }
        
        if (empty($data)) {
            return false;
        } else {
            return $data;    
        }
    }
    
    public function get_leave_id($leave_id) {
        $sql = "SELECT leave_id FROM `leave` WHERE leave_id = :leave_id"; // Added backticks
        $q = $this->conn->prepare($sql);
        $q->execute(['leave_id' => $leave_id]);
        return $q->fetchColumn();
    }

    public function get_leave_details($leave_id) {
        $sql = "SELECT * FROM `leave` WHERE leave_id = :leave_id"; // Added backticks
        $q = $this->conn->prepare($sql);
        $q->execute(['leave_id' => $leave_id]);
        return $q->fetch(PDO::FETCH_ASSOC);
    }

    public function get_leaves_by_nurse($nurse_id) {
        $sql = "SELECT * FROM `leave` WHERE nurse_id = :nurse_id"; // Added backticks
        $q = $this->conn->prepare($sql);
        $q->execute(['nurse_id' => $nurse_id]);
        return $q->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get_leaves_by_status($status) {
        $sql = "SELECT * FROM `leave` WHERE leave_status = :status"; // Added backticks
        $q = $this->conn->prepare($sql);
        $q->execute(['status' => $status]);
        return $q->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
