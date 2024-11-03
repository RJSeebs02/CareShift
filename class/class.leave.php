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

	/*Function for approving a leave */
	public function approve_leave($leave_id,$leave_status){
		/*Updates data from the database using the parameters passed from the profile updating page */
		$sql = "UPDATE `leave` SET leave_status=:leave_status WHERE leave_id=:leave_id";

		$q = $this->conn->prepare($sql);
		$q->execute(array(':leave_status'=>$leave_status, ':leave_id'=>$leave_id));
		return true;
	}

	/*Function for denying a leave */
	public function deny_leave($leave_id,$leave_status){
		/*Updates data from the database using the parameters passed from the profile updating page */
		$sql = "UPDATE `leave` SET leave_status=:leave_status WHERE leave_id=:leave_id";

		$q = $this->conn->prepare($sql);
		$q->execute(array(':leave_status'=>$leave_status, ':leave_id'=>$leave_id));
		return true;
	}
    
    /*Function that selects all the records from the leaves table from latest to oldest */
	public function list_leaves(){
		$sql="SELECT * FROM `leave` ORDER BY leave_date_filed DESC, leave_time_filed DESC";
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

	/*Function for getting the nurse id from the database */
	function get_leave_nurse_id($id){
		$sql="SELECT nurse_id FROM `leave` WHERE leave_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$nurse_id = $q->fetchColumn();
		return $nurse_id;
	}

    /*Function for getting the nurse fname from the database */
	function get_leave_nurse_fname($id){
		$sql="SELECT nurse_fname FROM nurse INNER JOIN `leave` WHERE leave.nurse_id = nurse.nurse_id AND leave_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$nurse_fname = $q->fetchColumn();
		return $nurse_fname;
	}

	/*Function for getting the nurse lname from the database */
	function get_leave_nurse_lname($id){
		$sql="SELECT nurse_lname FROM nurse INNER JOIN `leave` WHERE leave.nurse_id = nurse.nurse_id AND leave_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$nurse_lname = $q->fetchColumn();
		return $nurse_lname;
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

	/*Function for getting the leave start date from the database */
	function get_leave_start_date($id){
		$sql="SELECT leave_start_date FROM `leave` WHERE leave_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$leave_start_date = $q->fetchColumn();

		$leave_start_date = date("F j, Y", strtotime($leave_start_date));
		return $leave_start_date;
	}

	/*Function for getting the leave end date from the database */
	function get_leave_end_date($id){
		$sql="SELECT leave_end_date FROM `leave` WHERE leave_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$leave_end_date = $q->fetchColumn();

		$leave_end_date = date("F j, Y", strtotime($leave_end_date));
		return $leave_end_date;
	}

	/*Function for getting the leave date filed from the database */
	function get_leave_date_filed($id){
		$sql="SELECT leave_date_filed FROM `leave` WHERE leave_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$leave_date_filed = $q->fetchColumn();

		$leave_date_filed = date("F j, Y", strtotime($leave_date_filed));
		return $leave_date_filed;
	}

	/*Function for getting the leave date filed from the database */
	function get_leave_time_filed($id){
		$sql="SELECT leave_time_filed FROM `leave` WHERE leave_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$leave_time_filed = $q->fetchColumn();

		$leave_time_filed = date("g:i A", strtotime($leave_time_filed));
		return $leave_time_filed;
	}

	/*Function for getting the leave type from the database */
	function get_leave_type($id){
		$sql="SELECT leave_type FROM `leave` WHERE leave_id = :id";
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$leave_type = $q->fetchColumn();
		return $leave_type;
	}

	/*Function for getting the leave desc from the database */
	function get_leave_desc($id){
		$sql="SELECT leave_desc FROM `leave` WHERE leave_id = :id";
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$leave_desc = $q->fetchColumn();
		return $leave_desc;
	}

	/*Function for getting the leave status from the database */
	function get_leave_status($id){
		$sql="SELECT leave_status FROM `leave` WHERE leave_id = :id";
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$leave_status = $q->fetchColumn();
		return $leave_status;
	}
}
?>
