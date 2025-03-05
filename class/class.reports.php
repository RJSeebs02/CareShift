<?php
require_once '/home/careshift.helioho.st/httpdocs/config/connect.php';

class Report{
	private $conn;

    public function __construct() {
        $this->conn = Database::getInstance();
    }

    public function getNursesWithScheduleStatus() {
        // This query ensures that each nurse appears only once
        $query = "SELECT nurse.nurse_id, 
                         CONCAT(nurse.nurse_fname, ' ', nurse.nurse_lname) AS name, 
                         CASE 
                             WHEN COUNT(schedule.sched_id) > 0 THEN 'Scheduled' 
                             ELSE 'Unscheduled' 
                         END AS schedule_status
                  FROM nurse
                  LEFT JOIN schedule ON nurse.nurse_id = schedule.nurse_id
                  GROUP BY nurse.nurse_id"; // Group by nurse_id to avoid duplicates

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSchedulesByNurseId($nurse_id) {
        // Fetch all schedules for a particular nurse
        $query = "SELECT sched_id, start_date, end_date, start_time, end_time
                  FROM schedule
                  WHERE nurse_id = :nurse_id";
    
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nurse_id', $nurse_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function generateReports() {
        return $this->getNursesWithScheduleStatus();
    }
}
?>