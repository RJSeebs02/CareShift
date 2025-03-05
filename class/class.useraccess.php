<<<<<<< HEAD
<!--User Access Class File-->
<?php
class UserAccess{
	private $DB_SERVER='localhost';
	private $DB_USERNAME='root';
	private $DB_PASSWORD='';
	private $DB_DATABASE='db_careshift';
	private $conn;
	public function __construct(){
		$this->conn = new PDO("mysql:host=".$this->DB_SERVER.";dbname=".$this->DB_DATABASE,$this->DB_USERNAME,$this->DB_PASSWORD);
	}

	/*Function for creating a new user access */
	public function new_access($access_name,$access_desc){
		
		$data = [
			[$access_name,$access_desc],
		];
		/*Stores parameters passed from the creation page inside the database */
		$stmt = $this->conn->prepare("INSERT INTO useraccess (useraccess_name, useraccess_desc) VALUES (?,?)");
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

	/*Function for updating a user access */
	public function update_user_access($id,$useraccess_name,$useraccess_desc){
		/*Updates data from the database using the parameters passed from the profile updating page */
		$sql = "UPDATE useraccess SET useraccess_name=:useraccess_name, useraccess_desc=:useraccess_desc WHERE useraccess_id=:id";

		$q = $this->conn->prepare($sql);
		$q->execute(array(':useraccess_name'=>$useraccess_name, ':useraccess_desc'=>$useraccess_desc,':id'=>$id));
		return true;
	}

	/*Function for deleting a user access */
	public function delete_useraccess($id)
	{
		/*Deletes data from nurse selected by the user*/
		$sql = "DELETE FROM useraccess WHERE useraccess_id = :id";
		$stmt = $this->conn->prepare($sql);
		$stmt->bindParam(':id', $id);

		if ($stmt->execute()) {
			return true;
		} else {
			return false;
		}
	}

	/*Function that selects all the records from the admins table */
	public function list_useraccess(){
		$sql="SELECT * FROM useraccess";
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
	/*Function for getting the user access id from the database */
	function get_id_by_id($id){
		$sql="SELECT useraccess_id FROM useraccess WHERE useraccess_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$id = $q->fetchColumn();
		return $id;
	}
	/*Function for getting the admin id by username from the database */
	function get_id_by_useraccess_name($useraccess_name){
		$sql="SELECT useraccess_id FROM useraccess WHERE useraccess_name = :useraccess_name";	
		$q = $this->conn->prepare($sql);
		$q->execute(['useraccess_name' => $useraccess_name]);
		$id = $q->fetchColumn();
		return $id;
	}
	/*Function for getting the admin username from the database */
	function get_useraccess_name($id){
		$sql="SELECT useraccess_name FROM useraccess WHERE useraccess_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$useraccess_name = $q->fetchColumn();
		return $useraccess_name;
	}
    /*Function for getting the admin username from the database */
	function get_useraccess_desc($id){
		$sql="SELECT useraccess_desc FROM useraccess WHERE useraccess_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$useraccess_desc = $q->fetchColumn();
		return $useraccess_desc;
	}
}
=======
<?php
require_once '/home/careshift.helioho.st/httpdocs/config/connect.php';

class UserAccess{
	private $conn;

    public function __construct() {
        $this->conn = Database::getInstance();
    }

	/*Function for creating a new user access */
	public function new_access($access_name,$access_desc){
		
		$data = [
			[$access_name,$access_desc],
		];
		/*Stores parameters passed from the creation page inside the database */
		$stmt = $this->conn->prepare("INSERT INTO useraccess (useraccess_name, useraccess_desc) VALUES (?,?)");
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

	/*Function for updating a user access */
	public function update_user_access($id,$useraccess_name,$useraccess_desc){
		/*Updates data from the database using the parameters passed from the profile updating page */
		$sql = "UPDATE useraccess SET useraccess_name=:useraccess_name, useraccess_desc=:useraccess_desc WHERE useraccess_id=:id";

		$q = $this->conn->prepare($sql);
		$q->execute(array(':useraccess_name'=>$useraccess_name, ':useraccess_desc'=>$useraccess_desc,':id'=>$id));
		return true;
	}

	/*Function for deleting a user access */
	public function delete_useraccess($id)
	{
		/*Deletes data from nurse selected by the user*/
		$sql = "DELETE FROM useraccess WHERE useraccess_id = :id";
		$stmt = $this->conn->prepare($sql);
		$stmt->bindParam(':id', $id);

		if ($stmt->execute()) {
			return true;
		} else {
			return false;
		}
	}

	/*Function that selects all the records from the admins table */
	public function list_useraccess(){
		$sql="SELECT * FROM useraccess";
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
	/*Function for getting the user access id from the database */
	function get_id_by_id($id){
		$sql="SELECT useraccess_id FROM useraccess WHERE useraccess_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$id = $q->fetchColumn();
		return $id;
	}
	/*Function for getting the admin id by username from the database */
	function get_id_by_useraccess_name($useraccess_name){
		$sql="SELECT useraccess_id FROM useraccess WHERE useraccess_name = :useraccess_name";	
		$q = $this->conn->prepare($sql);
		$q->execute(['useraccess_name' => $useraccess_name]);
		$id = $q->fetchColumn();
		return $id;
	}
	/*Function for getting the admin username from the database */
	function get_useraccess_name($id){
		$sql="SELECT useraccess_name FROM useraccess WHERE useraccess_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$useraccess_name = $q->fetchColumn();
		return $useraccess_name;
	}
    /*Function for getting the admin username from the database */
	function get_useraccess_desc($id){
		$sql="SELECT useraccess_desc FROM useraccess WHERE useraccess_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$useraccess_desc = $q->fetchColumn();
		return $useraccess_desc;
	}
}
>>>>>>> fa57ef9cad725f4de6f3f7079a21b6419102a7c6
?>