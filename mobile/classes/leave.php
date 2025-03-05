<?php
class Leave {
	
    // Database connection and table name
    private $conn;
    private $table_name = "leave"; // Define the table name

    // Object properties
    public $leave_type;
    public $leave_desc;
    public $leave_start_date;
    public $leave_end_date;
    public $leave_status;
    public $nurse_id; 

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
		$query = "INSERT INTO `leave` 
				  (leave_type, leave_desc, leave_start_date, leave_end_date, leave_date_filed, leave_time_filed, leave_status, nurse_id) 
				  VALUES 
				  (:leave_type, :leave_desc, :leave_start_date, :leave_end_date, :leave_date_filed, :leave_time_filed, :leave_status, :nurse_id)";

		$stmt = $this->conn->prepare($query);

		$stmt->bindParam(":leave_type", $this->leave_type);
		$stmt->bindParam(":leave_desc", $this->leave_desc);
		$stmt->bindParam(":leave_start_date", $this->leave_start_date);
		$stmt->bindParam(":leave_end_date", $this->leave_end_date);
		$stmt->bindParam(":leave_date_filed", $this->leave_date_filed);
		$stmt->bindParam(":leave_time_filed", $this->leave_time_filed);
		$stmt->bindParam(":leave_status", $this->leave_status);
		$stmt->bindParam(":nurse_id", $this->nurse_id);

		// Execute and check for errors
		if ($stmt->execute()) {
			return true; // Successfully created
		} else {
			return false; // Creation failed
		}
	}
	
	public function readByNurseId($nurse_id) {
        $query = "SELECT * FROM `leave`   
                  WHERE nurse_id = :nurse_id 
                  ORDER BY leave_date_filed DESC, leave_time_filed DESC"; 

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nurse_id", $nurse_id);
        $stmt->execute();

        return $stmt; // Return the statement for fetching results
    }
}
?>