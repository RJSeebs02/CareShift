<?php
class Notifications {
	
    // Database connection and table name
    private $conn;

    // Object properties
    public $notif_type;
    public $notif_title;
    public $notif_msg; 
	public $nurse_id; 

    public function __construct($db) {
        $this->conn = $db;
    }
	
	public function readNotifByNurseId($nurse_id) {
    $query = "SELECT * FROM `notifications` 
          WHERE nurse_id = :nurse_id
          ORDER BY STR_TO_DATE(CONCAT(notif_date, ' ', notif_time), '%Y-%m-%d %H:%i:%s') DESC";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":nurse_id", $nurse_id);
    $stmt->execute();

    // Fetch results
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch the results

    return $results; // Return the fetched results
}
}
?>