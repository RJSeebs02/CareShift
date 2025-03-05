<<<<<<< HEAD
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

	/*Function that selects all the leaves records for a specific department*/
	public function list_leaves_by_department($department_id) {
		$data = []; 
		
		try {
		$sql = "SELECT l.* 
		FROM `leave` l
		JOIN `nurse` n ON l.nurse_id = n.nurse_id
		WHERE n.department_id = :department_id
		ORDER BY l.leave_date_filed DESC, l.leave_time_filed DESC";
		
		$stmt = $this->conn->prepare($sql);
		$stmt->bindParam(':department_id', $department_id, PDO::PARAM_INT);
		$stmt->execute();
		
		while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$data[] = $r;
		}
		
		return !empty($data) ? $data : false;
		
		} catch (PDOException $e) {
		error_log("Error fetching leaves by department: " . $e->getMessage());
		return false;
		}
	}

	public function countPendingLeavesByDepartment($departmentId = null) {
		$query = "SELECT d.department_name, COUNT(*) as count 
		  FROM `leave` l
		  JOIN `nurse` n ON l.nurse_id = n.nurse_id
		  JOIN `department` d ON n.department_id = d.department_id
		  WHERE l.leave_status = 'Pending'";
		if ($departmentId) {
		$query .= " AND n.department_id = :departmentId";
		}
		$query .= " GROUP BY d.department_name";
		$stmt = $this->conn->prepare($query);
		if ($departmentId) {
		$stmt->bindParam(':departmentId', $departmentId, PDO::PARAM_INT);
		}
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
		
	public function countTotalPendingLeaves() {
		$query = "SELECT COUNT(*) as count 
		  FROM `leave` 
		  WHERE leave_status = 'Pending'";
		
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		
		return $row['count'];
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
=======
<?php
require_once '/home/careshift.helioho.st/httpdocs/config/connect.php';

class Leave{
	private $conn;

    public function __construct() {
        $this->conn = Database::getInstance();
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

	/*Function that selects all the leaves records for a specific department*/
	public function list_leaves_by_department($department_id) {
		$data = []; 
		
		try {
		$sql = "SELECT l.* 
		FROM `leave` l
		JOIN `nurse` n ON l.nurse_id = n.nurse_id
		WHERE n.department_id = :department_id
		ORDER BY l.leave_date_filed DESC, l.leave_time_filed DESC";
		
		$stmt = $this->conn->prepare($sql);
		$stmt->bindParam(':department_id', $department_id, PDO::PARAM_INT);
		$stmt->execute();
		
		while ($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$data[] = $r;
		}
		
		return !empty($data) ? $data : false;
		
		} catch (PDOException $e) {
		error_log("Error fetching leaves by department: " . $e->getMessage());
		return false;
		}
	}

	public function countPendingLeavesByDepartment($departmentId = null) {
		$query = "SELECT d.department_name, COUNT(*) as count 
		  FROM `leave` l
		  JOIN `nurse` n ON l.nurse_id = n.nurse_id
		  JOIN `department` d ON n.department_id = d.department_id
		  WHERE l.leave_status = 'Pending'";
		if ($departmentId) {
		$query .= " AND n.department_id = :departmentId";
		}
		$query .= " GROUP BY d.department_name";
		$stmt = $this->conn->prepare($query);
		if ($departmentId) {
		$stmt->bindParam(':departmentId', $departmentId, PDO::PARAM_INT);
		}
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
		
	public function countTotalPendingLeaves() {
		$query = "SELECT COUNT(*) as count 
		  FROM `leave` 
		  WHERE leave_status = 'Pending'";
		
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		
		return $row['count'];
	}
	
	public function countTotalLeaves() {
		$query = "SELECT COUNT(*) as count 
		  FROM `leave`";
		
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		
		return $row['count'];
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
	
	 public function add_leave_sched($nurse_id, $leave_start_date, $leave_end_date) {
    // Convert start and end dates to DateTime objects for iteration
    $startDate = new DateTime($leave_start_date);
    $endDate = new DateTime($leave_end_date);
	$sched_type = "Leave";

    // Prepare the SQL statement
    $stmt = $this->conn->prepare("INSERT INTO schedule (nurse_id, sched_date, sched_type) VALUES (?, ?, ?)");

    try {
        $this->conn->beginTransaction();
        
        // Loop through each date in the range
        for ($date = $startDate; $date <= $endDate; $date->modify('+1 day')) {
            $sched_date = $date->format('Y-m-d'); // Format the date for SQL
            $stmt->execute([$nurse_id, $sched_date, $sched_type]);
        }
        
        $this->conn->commit();
    } catch (Exception $e) {
        $this->conn->rollback();
        throw $e;
    }

    return true;
}

}
?>
>>>>>>> fa57ef9cad725f4de6f3f7079a21b6419102a7c6
