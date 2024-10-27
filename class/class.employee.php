<!--Employee Class File-->
<?php
class Employee{
	private $DB_SERVER='localhost';
	private $DB_USERNAME='root';
	private $DB_PASSWORD='';
	private $DB_DATABASE='db_careshift';
	private $conn;
	public function __construct(){
		$this->conn = new PDO("mysql:host=".$this->DB_SERVER.";dbname=".$this->DB_DATABASE,$this->DB_USERNAME,$this->DB_PASSWORD);
	}

	/*Function for creating a new employee */
	public function new_employee($password,$first_name,$middle_name,$last_name,$email,$contact_no,$department){
		
		$data = [
			[$password,$first_name,$middle_name,$last_name,$email,$contact_no,$department],
		];
		/*Stores parameters passed from the creation page inside the database */
		$stmt = $this->conn->prepare("INSERT INTO employee (emp_password, emp_fname, emp_mname, emp_lname, emp_email, emp_contact, emp_department) VALUES (?,?,?,?,?,?,?)");
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

	/*Function for updating an employee */
	public function update_employee($id,$first_name,$middle_name,$last_name,$email,$contact_no,$department){
		/*Updates data from the database using the parameters passed from the profile updating page */
		$sql = "UPDATE employee SET emp_fname=:first_name, emp_mname=:middle_name, emp_lname=:last_name, emp_email=:email, emp_contact=:contact_no, emp_department=:department WHERE emp_id=:id";

		$q = $this->conn->prepare($sql);
		$q->execute(array(':first_name'=>$first_name, ':middle_name'=>$middle_name,':last_name'=>$last_name,':email'=>$email,':contact_no'=>$contact_no, ':department'=>$department, ':id'=>$id));
		return true;
	}

	/*Function that selects all the records from the employees table */
	public function list_employees(){
		$sql="SELECT * FROM employee";
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
	/*Function for getting the employee id from the database */
	function get_id($id){
		$sql="SELECT emp_id FROM employee WHERE emp_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$id = $q->fetchColumn();
		return $id;
	}
	/*Function for getting the employee fname from the database */
	function get_fname($id){
		$sql="SELECT emp_fname FROM employee WHERE emp_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$fname = $q->fetchColumn();
		return $fname;
	}
	/*Function for getting the employee mname from the database */
	function get_mname($id){
		$sql="SELECT emp_mname FROM employee WHERE emp_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$mname = $q->fetchColumn();
		return $mname;
	}
	/*Function for getting the employee lname from the database */
	function get_lname($id){
		$sql="SELECT emp_lname FROM employee WHERE emp_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$lname = $q->fetchColumn();
		return $lname;
	}
	/*Function for getting the employee email from the database */
	function get_email($id){
		$sql="SELECT emp_email FROM employee WHERE emp_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$email = $q->fetchColumn();
		return $email;
	}
	/*Function for getting the employee contact from the database */
	function get_contact($id){
		$sql="SELECT emp_contact FROM employee WHERE emp_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$contact = $q->fetchColumn();
		return $contact;
	}
	/*Function for getting the employee department from the database */
	function get_department($id){
		$sql="SELECT emp_department FROM employee WHERE emp_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$department = $q->fetchColumn();
		return $department;
	}
}
?>