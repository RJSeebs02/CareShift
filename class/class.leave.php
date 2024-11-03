<?php
class Leave {
    private $DB_SERVER = 'localhost';
    private $DB_USERNAME = 'root';
    private $DB_PASSWORD = '';
    private $DB_DATABASE = 'db_careshift';
    private $conn;
    public function __construct() {
        $this->conn = new PDO("mysql:host=" . $this->DB_SERVER . ";dbname=" . $this->DB_DATABASE, $this->DB_USERNAME, $this->DB_PASSWORD);
    }

    /*Function for creating a new leave */
	public function new_leave($nurse_id,$leave_start_date,$leave_end_date,$leave_type,$leave_desc,$leave_status){

        /* Setting Timezone for DB */
		$NOW = new DateTime('now', new DateTimeZone('Asia/Manila'));
		$NOW = $NOW->format('Y-m-d H:i:s');
		
		$data = [
			[$nurse_id,$leave_start_date,$leave_end_date,$leave_type,$leave_desc,$leave_status,$NOW,$NOW],
		];
		/*Stores parameters passed from the creation page inside the database */
		$stmt = $this->conn->prepare("INSERT INTO `leave` (nurse_id, leave_start_date, leave_end_date, leave_type, leave_desc, leave_status, leave_date_filed, leave_time_filed) VALUES (?,?,?,?,?,?,?,?)");
		try {
			$this->conn->beginTransaction();
			foreach ($data as $row)
			{
				$stmt->execute($row);
			}
			$this->conn->commit();
		}catch (Exception $e){
			$this->conn->rollback();
			throw $e;
		}

		return true;

	}
    
    /*Function that selects all the records from the leaves table */
	public function list_leaves(){
		$sql="SELECT * FROM `leave`";
		$q = $this->conn->query($sql) or die("failed!");
		while($r = $q->fetch(PDO::FETCH_ASSOC)){
		$data[]=$r;
		}
		if(empty($data)){
		   return false;
		}else{
			return $data;	
		}
	}
    /*Function for getting the leave id from the database */
	function get_id_by_id($id){
		$sql="SELECT leave_id FROM `leave` WHERE leave_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$id = $q->fetchColumn();
		return $id;
	}
	/*Function for getting the leave nurse name from nurse table from the database */
	function get_leave_nurse_name($id){
		$sql="SELECT nurse_fname, nurse_mname, nurse_lname FROM nurse INNER JOIN `leave` WHERE leave.nurse_id = nurse.nurse_id AND leave_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
        
        // Fetch the result as an associative array
        $nurse = $q->fetch(PDO::FETCH_ASSOC);
    
        // Combine first and last name into a full name
        if ($nurse) {
            $nurse_name = $nurse['nurse_fname'] . ' ' . $nurse['nurse_mname'] .' '. $nurse['nurse_lname'];
            return $nurse_name;
        }
	}
}
?>
