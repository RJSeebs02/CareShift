<!--Department Type Class File-->
<?php
class Dept_Type{
	private $DB_SERVER='localhost';
	private $DB_USERNAME='root';
	private $DB_PASSWORD='';
	private $DB_DATABASE='db_careshift';
	private $conn;
	public function __construct(){
		$this->conn = new PDO("mysql:host=".$this->DB_SERVER.";dbname=".$this->DB_DATABASE,$this->DB_USERNAME,$this->DB_PASSWORD);
	}

	/*Function that selects all the records from the admins table */
	public function list_dept_type(){
		$sql="SELECT * FROM dept_type";
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
	/*Function for getting the department type id from the database */
	function get_id_by_id($id){
		$sql="SELECT dept_type_id FROM dept_type WHERE dept_type_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$id = $q->fetchColumn();
		return $id;
	}
	/*Function for getting the department type id by department type name from the database */
	function get_id_by_dept_type_name($department_name){
		$sql="SELECT dept_type_id FROM dept_type WHERE dept_type_name = :dept_type_name";	
		$q = $this->conn->prepare($sql);
		$q->execute(['dept_type_name' => $dept_type_name]);
		$id = $q->fetchColumn();
		return $id;
	}
	/*Function for getting the department type name from the database */
	function get_dept_type_name($id){
		$sql="SELECT dept_type_name FROM dept_type WHERE dept_type_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$dept_type_name = $q->fetchColumn();
		return $dept_type_name;
	}
    /*Function for getting the department type desc from the database */
	function get_dept_type_desc($id){
		$sql="SELECT dept_type_desc FROM dept_type WHERE dept_type_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$dept_type_desc = $q->fetchColumn();
		return $dept_type_desc;
	}
}
?>