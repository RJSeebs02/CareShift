<?php
class Report {
    private $DB_SERVER = 'localhost';
    private $DB_USERNAME = 'root';
    private $DB_PASSWORD = '';
    private $DB_DATABASE = 'db_careshift';
    private $conn;
    public function __construct(){
        $this->conn = new PDO("mysql:host=".$this->DB_SERVER.";dbname=".$this->DB_DATABASE, $this->DB_USERNAME, $this->DB_PASSWORD);
    }

    public function getNursesWithScheduleStatus() {
        // Prepare the query
        $query = "SELECT nurse.nurse_id, 
                         CONCAT(nurse.nurse_fname, ' ', nurse.nurse_lname) AS name, 
                         CASE 
                             WHEN schedule.sched_id IS NOT NULL THEN 'Scheduled' 
                             ELSE 'Unscheduled' 
                         END AS schedule_status
                  FROM nurse
                  LEFT JOIN schedule ON nurse.nurse_id = schedule.nurse_id";
    
        // Use prepared statements for better security
        $stmt = $this->conn->prepare($query);
        $stmt->execute(); // Execute the query
    
        // Fetch results as an associative array
        $nurses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        return $nurses;
    }

    public function generateReports() {
        return $this->getNursesWithScheduleStatus();
    }
}
?>
