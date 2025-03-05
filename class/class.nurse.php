<<<<<<< HEAD
<?php
class Nurse{
	private $DB_SERVER='localhost';
	private $DB_USERNAME='root';
	private $DB_PASSWORD='';
	private $DB_DATABASE='db_careshift';
	private $conn;
	public function __construct(){
		$this->conn = new PDO("mysql:host=".$this->DB_SERVER.";dbname=".$this->DB_DATABASE,$this->DB_USERNAME,$this->DB_PASSWORD);
	}

	public function new_nurse($password,$first_name,$middle_name,$last_name,$email,$sex,$contact_no,$position,$department){
		
		$data = [
			[$password,$first_name,$middle_name,$last_name,$email,$sex,$contact_no,$position,$department],
		];
		/*Stores parameters passed from the creation page inside the database */
		$stmt = $this->conn->prepare("INSERT INTO nurse (nurse_password, nurse_fname, nurse_mname, nurse_lname, nurse_email, nurse_sex, nurse_contact, nurse_position, department_id) VALUES (?,?,?,?,?,?,?,?,?)");
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

	/*Function for updating an nurse */
	public function update_nurse($id,$first_name,$middle_name,$last_name,$email,$sex,$contact_no,$position,$department){
		/*Updates data from the database using the parameters passed from the profile updating page */
		$sql = "UPDATE nurse SET nurse_fname=:first_name, nurse_mname=:middle_name, nurse_lname=:last_name, nurse_email=:email, nurse_sex=:sex, nurse_contact=:contact_no, nurse_position=:position, department_id=:department WHERE nurse_id=:id";

		$q = $this->conn->prepare($sql);
		$q->execute(array(':first_name'=>$first_name, ':middle_name'=>$middle_name,':last_name'=>$last_name,':email'=>$email,':sex'=>$sex,':contact_no'=>$contact_no, ':position'=>$position, ':department'=>$department, ':id'=>$id));
		return true;
	}

	/*Function for deleting a nurse */
	public function delete_nurse($id)
	{
		/*Deletes data from nurse selected by the user*/
		$sql = "DELETE FROM nurse WHERE nurse_id = :id";
		$stmt = $this->conn->prepare($sql);
		$stmt->bindParam(':id', $id);

		if ($stmt->execute()) {
			return true;
		} else {
			return false;
		}
	}

	/*Function that selects all the records from the nurse table */
	public function list_nurses(){
		$sql="SELECT * FROM nurse";
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

	public function list_nurses_by_department($department_id) {
        $sql = "SELECT * FROM nurse WHERE department_id = :department_id";  
        $q = $this->conn->prepare($sql);
        $q->execute(['department_id' => $department_id]);
        return $q->fetchAll(PDO::FETCH_ASSOC); 
    }

	public function countAvailableNursesByDepartment($departmentId) {
		$query = "SELECT COUNT(*) as count 
				  FROM nurse n
				  JOIN department d ON n.department_id = d.department_id 
				  WHERE d.department_id = :departmentId";  // Changed to use department_id
	
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':departmentId', $departmentId, PDO::PARAM_INT);  // Make sure to bind as integer
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row['count'];
	}

	public function countTotalNurses() {
		$query = "SELECT COUNT(*) as count FROM nurse";
		
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		
		return $row['count'];
	}

	public function countNursesByDepartment() {
		$query = "SELECT department, COUNT(*) as count 
				  FROM nurse 
				  GROUP BY department_id";
	
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC); 
		// Example output: [{"department": "ICU", "count": 10}, {"department": "ER", "count": 15}]
	}

	/*Function for getting the nurse id from the database */
	function get_id($id){
		$sql="SELECT nurse_id FROM nurse WHERE nurse_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$id = $q->fetchColumn();
		return $id;
	}
	/*Function for getting the nurse id from the database */
	function get_id_by_name($nurse_fname){
		$sql="SELECT nurse_id FROM nurse WHERE nurse_fname = :nurse_fname";	
		$q = $this->conn->prepare($sql);
		$q->execute(['nurse_fname' => $nurse_fname]);
		$id = $q->fetchColumn();
		return $id;
	}
	/*Function for getting the nurse fname from the database */
	function get_fname($id){
		$sql="SELECT nurse_fname FROM nurse WHERE nurse_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$fname = $q->fetchColumn();
		return $fname;
	}
	/*Function for getting the nurse mname from the database */
	function get_mname($id){
		$sql="SELECT nurse_mname FROM nurse WHERE nurse_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$mname = $q->fetchColumn();
		return $mname;
	}
	/*Function for getting the nurse lname from the database */
	function get_lname($id){
		$sql="SELECT nurse_lname FROM nurse WHERE nurse_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$lname = $q->fetchColumn();
		return $lname;
	}
	/*Function for getting the nurse email from the database */
	function get_email($id){
		$sql="SELECT nurse_email FROM nurse WHERE nurse_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$email = $q->fetchColumn();
		return $email;
	}
	/*Function for getting the nurse sex from the database */
	function get_sex($id){
		$sql="SELECT nurse_sex FROM nurse WHERE nurse_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$sex = $q->fetchColumn();
		return $sex;
	}
	/*Function for getting the nurse contact from the database */
	function get_contact($id){
		$sql="SELECT nurse_contact FROM nurse WHERE nurse_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$contact = $q->fetchColumn();
		return $contact;
	}
	/*Function for getting the nurse position from the database */
	function get_position($id){
		$sql="SELECT nurse_position FROM nurse WHERE nurse_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$position = $q->fetchColumn();
		return $position;
	}
	/*Function for getting the nurse department from the database */
	function get_department($id){
		$sql="SELECT nurse_department FROM nurse WHERE nurse_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$department = $q->fetchColumn();
		return $department;
	}
	/*Function for getting the department id from the database */
	function get_nurse_department_id($id){
		$sql="SELECT department_id FROM nurse WHERE nurse_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$department_id = $q->fetchColumn();
		return $department_id;
	}
    /*Function for getting the department name of a room from the database */
	function get_nurse_department_name($id){
		$sql="SELECT department_name FROM department INNER JOIN nurse WHERE nurse.department_id = department.department_id AND nurse_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$department_name = $q->fetchColumn();
		return $department_name;
	}
}
=======
<?php
require_once '/home/careshift.helioho.st/httpdocs/config/connect.php';

class Nurse{
	private $conn;

    public function __construct() {
        $this->conn = Database::getInstance();
    }

	public function new_nurse($password,$first_name,$middle_name,$last_name,$email,$sex,$contact_no,$position,$department){
		
		$data = [
			[$password,$first_name,$middle_name,$last_name,$email,$sex,$contact_no,$position,$department],
		];
		/*Stores parameters passed from the creation page inside the database */
		$stmt = $this->conn->prepare("INSERT INTO nurse (nurse_password, nurse_fname, nurse_mname, nurse_lname, nurse_email, nurse_sex, nurse_contact, nurse_position, department_id) VALUES (?,?,?,?,?,?,?,?,?)");
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

	/*Function for updating an nurse */
	public function update_nurse($id,$first_name,$middle_name,$last_name,$email,$sex,$contact_no,$position,$department){
		/*Updates data from the database using the parameters passed from the profile updating page */
		$sql = "UPDATE nurse SET nurse_fname=:first_name, nurse_mname=:middle_name, nurse_lname=:last_name, nurse_email=:email, nurse_sex=:sex, nurse_contact=:contact_no, nurse_position=:position, department_id=:department WHERE nurse_id=:id";

		$q = $this->conn->prepare($sql);
		$q->execute(array(':first_name'=>$first_name, ':middle_name'=>$middle_name,':last_name'=>$last_name,':email'=>$email,':sex'=>$sex,':contact_no'=>$contact_no, ':position'=>$position, ':department'=>$department, ':id'=>$id));
		return true;
	}

	/*Function for deleting a nurse */
	public function delete_nurse($id)
	{
		/*Deletes data from nurse selected by the user*/
		$sql = "DELETE FROM nurse WHERE nurse_id = :id";
		$stmt = $this->conn->prepare($sql);
		$stmt->bindParam(':id', $id);

		if ($stmt->execute()) {
			return true;
		} else {
			return false;
		}
	}

	/*Function that selects all the records from the nurse table */
	public function list_nurses(){
		$sql="SELECT * FROM nurse";
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
	
	public function fetchNursesWithDutiesForWeek($department = 'all') {
		$query = "SELECT n.nurse_fname, n.nurse_lname, d.department_name, s.sched_date
				  FROM schedule s
				  INNER JOIN nurse n ON s.nurse_id = n.nurse_id
				  INNER JOIN department d ON n.department_id = d.department_id
				  WHERE WEEK(s.sched_date, 1) = WEEK(NOW(), 1)";

		if ($department !== 'all') {
			$query .= " AND d.department_name = :department";
		}

		// Prepare the query
		$stmt = $this->conn->prepare($query);

		// Bind the department parameter if it's not 'all'
		if ($department !== 'all') {
			$stmt->bindParam(':department', $department, PDO::PARAM_STR);
		}

		// Execute the query
		$stmt->execute();

		// Fetch all results
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

		return $result;
	}
	
	public function fetchNursesForDay($date) {
		// SQL query to get nurses scheduled for the specific date
		$query = "SELECT n.nurse_fname, n.nurse_lname, d.department_name, s.sched_date 
				  FROM schedule s 
				  INNER JOIN nurse n ON s.nurse_id = n.nurse_id 
				  INNER JOIN department d ON n.department_id = d.department_id 
				  WHERE s.sched_date = :date 
				  ORDER BY s.sched_date ASC"; 

		// Prepare the query
		$stmt = $this->conn->prepare($query);

		// Bind the date parameter to prevent SQL injection
		$stmt->bindParam(':date', $date, PDO::PARAM_STR);

		// Execute the query
		$stmt->execute();

		// Fetch all results and return them
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function list_nurses_by_department($department_id) {
        $sql = "SELECT * FROM nurse WHERE department_id = :department_id";  
        $q = $this->conn->prepare($sql);
        $q->execute(['department_id' => $department_id]);
        return $q->fetchAll(PDO::FETCH_ASSOC); 
    }

	public function countAvailableNursesByDepartment($departmentId) {
		$query = "SELECT COUNT(*) as count 
				  FROM nurse n
				  JOIN department d ON n.department_id = d.department_id 
				  WHERE d.department_id = :departmentId";
	
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':departmentId', $departmentId, PDO::PARAM_INT);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row['count'];
	}

	public function countTotalNurses() {
		$query = "SELECT COUNT(*) as count FROM nurse";
		
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		
		return $row['count'];
	}

	public function countNursesByDepartment() {
		$query = "SELECT department, COUNT(*) as count 
				  FROM nurse 
				  GROUP BY department_id";
	
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC); 
		// Example output: [{"department": "ICU", "count": 10}, {"department": "ER", "count": 15}]
	}

	/*Function for getting the nurse id from the database */
	function get_id($id){
		$sql="SELECT nurse_id FROM nurse WHERE nurse_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$id = $q->fetchColumn();
		return $id;
	}
	/*Function for getting the nurse id from the database */
	function get_id_by_name($nurse_fname){
		$sql="SELECT nurse_id FROM nurse WHERE nurse_fname = :nurse_fname";	
		$q = $this->conn->prepare($sql);
		$q->execute(['nurse_fname' => $nurse_fname]);
		$id = $q->fetchColumn();
		return $id;
	}
	/*Function for getting the nurse fname from the database */
	function get_fname($id){
		$sql="SELECT nurse_fname FROM nurse WHERE nurse_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$fname = $q->fetchColumn();
		return $fname;
	}
	/*Function for getting the nurse mname from the database */
	function get_mname($id){
		$sql="SELECT nurse_mname FROM nurse WHERE nurse_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$mname = $q->fetchColumn();
		return $mname;
	}
	/*Function for getting the nurse lname from the database */
	function get_lname($id){
		$sql="SELECT nurse_lname FROM nurse WHERE nurse_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$lname = $q->fetchColumn();
		return $lname;
	}
	/*Function for getting the nurse email from the database */
	function get_email($id){
		$sql="SELECT nurse_email FROM nurse WHERE nurse_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$email = $q->fetchColumn();
		return $email;
	}
	/*Function for getting the nurse sex from the database */
	function get_sex($id){
		$sql="SELECT nurse_sex FROM nurse WHERE nurse_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$sex = $q->fetchColumn();
		return $sex;
	}
	/*Function for getting the nurse contact from the database */
	function get_contact($id){
		$sql="SELECT nurse_contact FROM nurse WHERE nurse_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$contact = $q->fetchColumn();
		return $contact;
	}
	/*Function for getting the nurse position from the database */
	function get_position($id){
		$sql="SELECT nurse_position FROM nurse WHERE nurse_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$position = $q->fetchColumn();
		return $position;
	}
	/*Function for getting the nurse department from the database */
	function get_department($id){
		$sql="SELECT nurse_department FROM nurse WHERE nurse_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$department = $q->fetchColumn();
		return $department;
	}
	/*Function for getting the department id from the database */
	function get_nurse_department_id($id){
		$sql="SELECT department_id FROM nurse WHERE nurse_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$department_id = $q->fetchColumn();
		return $department_id;
	}
    /*Function for getting the department name of a room from the database */
	function get_nurse_department_name($id){
		$sql="SELECT department_name FROM department INNER JOIN nurse WHERE nurse.department_id = department.department_id AND nurse_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$department_name = $q->fetchColumn();
		return $department_name;
	}
	public function countAttendancePerDepartment() {
    $query = "
        SELECT d.department_name, COUNT(a.att_id) as attendance_count
        FROM attendance a
        JOIN nurse n ON a.nurse_id = n.nurse_id
        JOIN department d ON n.department_id = d.department_id
        GROUP BY d.department_name
    ";

    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC); 
	}
	
	public function fetchAllAttendance($startDate = null, $endDate = null) {
        try {
            $query = "SELECT 
                        a.att_id, 
                        n.nurse_id, 
                        n.nurse_fname, 
                        n.nurse_lname, 
                        a.att_date, 
                        a.att_status 
                    FROM attendance a
                    INNER JOIN nurse n ON a.nurse_id = n.nurse_id";
            
            // Add date range filtering if provided
            if ($startDate && $endDate) {
                $query .= " WHERE DATE(a.att_date) BETWEEN ? AND ?";
            }

            $stmt = $this->conn->prepare($query);

            if ($startDate && $endDate) {
                $stmt->bind_param("ss", $startDate, $endDate);
            }

            $stmt->execute();
            $result = $stmt->get_result();

            $attendanceRecords = [];
            while ($row = $result->fetch_assoc()) {
                $attendanceRecords[] = $row;
            }

            return $attendanceRecords;
        } catch (Exception $e) {
            error_log("Error in fetchAllAttendance: " . $e->getMessage());
            return false;
        }
    }
	
	public function fetchAttendanceByDepartment($department, $startDate = null, $endDate = null) {
        try {
            $query = "SELECT 
                        a.att_id, 
                        n.nurse_id, 
                        n.nurse_fname, 
                        n.nurse_lname, 
                        a.att_date, 
                        a.att_status,
                        d.department_name
                    FROM attendance a
                    INNER JOIN nurse n ON a.nurse_id = n.nurse_id
                    INNER JOIN department d ON n.department_id = d.department_id
                    WHERE d.department_name = ?";
            
            // Add date range filtering if provided
            if ($startDate && $endDate) {
                $query .= " AND DATE(a.att_date) BETWEEN ? AND ?";
            }

            $stmt = $this->conn->prepare($query);

            if ($startDate && $endDate) {
                $stmt->bind_param("sss", $department, $startDate, $endDate);
            } else {
                $stmt->bind_param("s", $department);
            }

            $stmt->execute();
            $result = $stmt->get_result();

            $attendanceRecords = [];
            while ($row = $result->fetch_assoc()) {
                $attendanceRecords[] = $row;
            }

            return $attendanceRecords;
        } catch (Exception $e) {
            error_log("Error in fetchAttendanceByDepartment: " . $e->getMessage());
            return false;
        }
    }
	
}
>>>>>>> fa57ef9cad725f4de6f3f7079a21b6419102a7c6
?>