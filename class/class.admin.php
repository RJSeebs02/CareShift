<!--Admin Class File-->
<?php
/*Creates Admin Object with database connection */
class Admin{
	private $DB_SERVER='localhost';
	private $DB_USERNAME='root';
	private $DB_PASSWORD='';
	private $DB_DATABASE='db_careshift';
	private $conn;
	public function __construct(){
		$this->conn = new PDO("mysql:host=".$this->DB_SERVER.";dbname=".$this->DB_DATABASE,$this->DB_USERNAME,$this->DB_PASSWORD);
	}

	/*Function that selects all the records from the admins table */
	public function list_admins(){
		$sql="SELECT * FROM admin";
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
	/*Function for getting the admin id from the database */
	function get_id($username){
		$sql="SELECT adm_id FROM admin WHERE adm_username = :username";	
		$q = $this->conn->prepare($sql);
		$q->execute(['username' => $username]);
		$id = $q->fetchColumn();
		return $id;
	}
    /*Function for getting the admin username from the database */
	function get_username($id){
		$sql="SELECT adm_username FROM admin WHERE adm_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$username = $q->fetchColumn();
		return $username;
	}
    /*Function for getting the admin password from the database */
	function get_password($id){
		$sql="SELECT adm_password FROM admin WHERE adm_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$password = $q->fetchColumn();
		return $password;
	}
	/*Function for getting the admin fname from the database */
	function get_fname($id){
		$sql="SELECT adm_fname FROM admin WHERE adm_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$fname = $q->fetchColumn();
		return $fname;
	}
	/*Function for getting the admin mname from the database */
	function get_mname($id){
		$sql="SELECT adm_mname FROM admin WHERE adm_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$mname = $q->fetchColumn();
		return $mname;
	}
	/*Function for getting the admin lname from the database */
	function get_lname($id){
		$sql="SELECT adm_lname FROM admin WHERE adm_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$lname = $q->fetchColumn();
		return $lname;
	}
	/*Function for getting the admin email from the database */
	function get_email($id){
		$sql="SELECT adm_email FROM admin WHERE adm_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$email = $q->fetchColumn();
		return $email;
	}
	/*Function for getting the admin contact from the database */
	function get_contact($id){
		$sql="SELECT adm_contact FROM admin WHERE adm_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$contact = $q->fetchColumn();
		return $contact;
	}
	/*Function for getting the admin department from the database */
	function get_department($id){
		$sql="SELECT adm_department FROM admin WHERE adm_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$department = $q->fetchColumn();
		return $department;
	}
    /*Function for getting the session from the database for logging in */
	function get_session(){
		if(isset($_SESSION['login']) && $_SESSION['login'] == true){
			return true;
		}else{
			return false;
		}
	}
    /*Function for checking if the user inputs match from that of the database */
	public function check_login($username,$password){
		
		$sql = "SELECT count(*) FROM admin WHERE adm_username = :username AND adm_password = :password"; 
		$q = $this->conn->prepare($sql);
		$q->execute(['username' => $username,'password' => $password ]);
		$number_of_rows = $q->fetchColumn();
		/*$password = md5($password);*/
		if($number_of_rows == 1){
			
			$_SESSION['login']=true;
			$_SESSION['adm_username']=$username;
			return true;
		}else{
			return false;
		}
	}
}
?>