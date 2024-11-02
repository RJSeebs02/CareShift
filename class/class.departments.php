<!--Department Class File-->
<?php
class Departments{
	private $DB_SERVER='localhost';
	private $DB_USERNAME='root';
	private $DB_PASSWORD='';
	private $DB_DATABASE='db_careshift';
	private $conn;
	public function __construct(){
		$this->conn = new PDO("mysql:host=".$this->DB_SERVER.";dbname=".$this->DB_DATABASE,$this->DB_USERNAME,$this->DB_PASSWORD);
	}

	/*Function for creating a new department */
	public function new_department($department_name,$department_desc,$dept_type_id){
		
		$data = [
			[$department_name,$department_desc,$dept_type_id],
		];
		/*Stores parameters passed from the creation page inside the database */
		$stmt = $this->conn->prepare("INSERT INTO department (department_name, department_desc, dept_type_id) VALUES (?,?,?)");
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

	/*Function for updating a department */
	public function update_department($id,$department_name,$department_desc,$dept_type_id){
		/*Updates data from the database using the parameters passed from the profile updating page */
		$sql = "UPDATE department SET department_name=:department_name, department_desc=:department_desc, dept_type_id=:dept_type_id WHERE department_id=:id";

		$q = $this->conn->prepare($sql);
		$q->execute(array(':department_name'=>$department_name, ':department_desc'=>$department_desc, ':dept_type_id'=>$dept_type_id,':id'=>$id));
		return true;
	}

	/*Function for deleting a department */
	public function delete_department($id)
	{
		/*Deletes data from department selected by the user*/
		$sql = "DELETE FROM department WHERE department_id = :id";
		$stmt = $this->conn->prepare($sql);
		$stmt->bindParam(':id', $id);

		if ($stmt->execute()) {
			return true;
		} else {
			return false;
		}
	}

	/*Function that selects all the records from the department table */
	public function list_department(){
		$sql="SELECT * FROM department";
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
	/*Function for getting the department id from the database */
	function get_id_by_id($id){
		$sql="SELECT department_id FROM department WHERE department_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$id = $q->fetchColumn();
		return $id;
	}
	/*Function for getting the department id by department name from the database */
	function get_id_by_department_name($department_name){
		$sql="SELECT department_id FROM department WHERE department_name = :department_name";	
		$q = $this->conn->prepare($sql);
		$q->execute(['department_name' => $department_name]);
		$id = $q->fetchColumn();
		return $id;
	}
	/*Function for getting the department name from the database */
	function get_department_name($id){
		$sql="SELECT department_name FROM department WHERE department_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$department_name = $q->fetchColumn();
		return $department_name;
	}
    /*Function for getting the department desc from the database */
	function get_department_desc($id){
		$sql="SELECT department_desc FROM department WHERE department_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$department_desc = $q->fetchColumn();
		return $department_desc;
	}
    /*Function for getting the department_type id from the database */
	function get_department_dept_type_id($id){
		$sql="SELECT dept_type_id FROM department WHERE department_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$dept_type_id = $q->fetchColumn();
		return $dept_type_id;
	}
    /*Function for getting the department_type name from department_type table from the database */
	function get_department_dept_type_name($id){
		$sql="SELECT dept_type_name FROM dept_type INNER JOIN department WHERE department.dept_type_id = dept_type.dept_type_id AND department_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$dept_type_name = $q->fetchColumn();
		return $dept_type_name;
	}
}
?>