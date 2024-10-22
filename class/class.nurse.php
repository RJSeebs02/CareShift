<!--Nurse Class File-->
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

	public function new_nurse($password,$first_name,$middle_name,$last_name,$email,$contact_no,$position,$department){
		
		$data = [
			[$password,$first_name,$middle_name,$last_name,$email,$contact_no,$position,$department],
		];
		/*Stores parameters passed from the creation page inside the database */
		$stmt = $this->conn->prepare("INSERT INTO nurse (nurse_password, nurse_fname, nurse_mname, nurse_lname, nurse_email, nurse_contact, nurse_position, nurse_department) VALUES (?,?,?,?,?,?,?,?)");
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
	public function update_nurse($id,$first_name,$middle_name,$last_name,$email,$contact_no,$position,$department){
		/*Updates data from the database using the parameters passed from the profile updating page */
		$sql = "UPDATE nurse SET nurse_fname=:first_name, nurse_mname=:middle_name, nurse_lname=:last_name, nurse_email=:email, nurse_contact=:contact_no, nurse_position=:position, nurse_department=:department WHERE nurse_id=:id";

		$q = $this->conn->prepare($sql);
		$q->execute(array(':first_name'=>$first_name, ':middle_name'=>$middle_name,':last_name'=>$last_name,':email'=>$email,':contact_no'=>$contact_no, ':position'=>$position, ':department'=>$department, ':id'=>$id));
		return true;
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
	/*Function for getting the nurse id from the database */
	function get_id($id){
		$sql="SELECT nurse_id FROM nurse WHERE nurse_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
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
}
?>