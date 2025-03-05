<?php
class Schedule {
	
    // Database connection and table name
    private $conn;

    // Object properties
    public $sched_date;
    public $sched_start_time;
    public $sched_end_time; 
	public $nurse_id; 

    public function __construct($db) {
        $this->conn = $db;
    }
	
	public function readSchedByNurseId($nurse_id) {
    $query = "SELECT * FROM `schedule` WHERE nurse_id = :nurse_id";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":nurse_id", $nurse_id);
    $stmt->execute();

    // Fetch results
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch the results

    return $results; // Return the fetched results
}
}
?>