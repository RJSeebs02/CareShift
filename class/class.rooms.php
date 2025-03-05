<<<<<<< HEAD
<!--User Access Class File-->
<?php
class Rooms{
	private $DB_SERVER='localhost';
	private $DB_USERNAME='root';
	private $DB_PASSWORD='';
	private $DB_DATABASE='db_careshift';
	private $conn;
	public function __construct(){
		$this->conn = new PDO("mysql:host=".$this->DB_SERVER.";dbname=".$this->DB_DATABASE,$this->DB_USERNAME,$this->DB_PASSWORD);
	}

	/*Function for creating a new room */
	public function new_room($room_name,$room_slots,$status_id,$department_id){
		
		$data = [
			[$room_name,$room_slots,$status_id,$department_id],
		];
		/*Stores parameters passed from the creation page inside the database */
		$stmt = $this->conn->prepare("INSERT INTO room (room_name, room_slots, status_id, department_id) VALUES (?,?,?,?)");
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

	/*Function for updating a room */
	public function update_room($id,$room_name,$room_slots,$status_id,$department_id){
		/*Updates data from the database using the parameters passed from the profile updating page */
		$sql = "UPDATE room SET room_name=:room_name, room_slots=:room_slots, status_id=:status_id, department_id=:department_id WHERE room_id=:id";

		$q = $this->conn->prepare($sql);
		$q->execute(array(':room_name'=>$room_name, ':room_slots'=>$room_slots, ':status_id'=>$status_id, ':department_id'=>$department_id,':id'=>$id));
		return true;
	}

	/*Function for deleting a room */
	public function delete_room($id)
	{
		/*Deletes data from room selected by the user*/
		$sql = "DELETE FROM room WHERE room_id = :id";
		$stmt = $this->conn->prepare($sql);
		$stmt->bindParam(':id', $id);

		if ($stmt->execute()) {
			return true;
		} else {
			return false;
		}
	}

	/*Function that selects all the records from the admins table */
	public function list_room(){
		$sql="SELECT * FROM room";
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
	/*Function for getting the room id from the database */
	function get_id_by_id($id){
		$sql="SELECT room_id FROM room WHERE room_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$id = $q->fetchColumn();
		return $id;
	}
	/*Function for getting the room id by room name from the database */
	function get_id_by_room_name($room_name){
		$sql="SELECT room_id FROM room WHERE room_name = :room_name";	
		$q = $this->conn->prepare($sql);
		$q->execute(['room_name' => $room_name]);
		$id = $q->fetchColumn();
		return $id;
	}
	/*Function for getting the room name from the database */
	function get_room_name($id){
		$sql="SELECT room_name FROM room WHERE room_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$room_name = $q->fetchColumn();
		return $room_name;
	}
    /*Function for getting the room slots from the database */
	function get_room_slots($id){
		$sql="SELECT room_slots FROM room WHERE room_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$room_slots = $q->fetchColumn();
		return $room_slots;
	}
    /*Function for getting the room status from the database */
	function get_room_status($id){
		$sql="SELECT room_status FROM room WHERE room_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$room_status = $q->fetchColumn();
		return $room_status;
	}
    /*Function for getting the department id from the database */
	function get_room_department_id($id){
		$sql="SELECT department_id FROM room WHERE room_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$department_id = $q->fetchColumn();
		return $department_id;
	}
    /*Function for getting the department name of a room from the database */
	function get_room_department_name($id){
		$sql="SELECT department_name FROM department INNER JOIN room WHERE room.department_id = department.department_id AND room_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$department_name = $q->fetchColumn();
		return $department_name;
	}
    /*Function for getting the status id from the database */
	function get_room_status_id($id){
		$sql="SELECT status_id FROM room WHERE room_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$status_id = $q->fetchColumn();
		return $status_id;
	}
    /*Function for getting the status name of a room from the database */
	function get_room_status_name($id){
		$sql="SELECT status_name FROM status INNER JOIN room WHERE room.status_id = status.status_id AND room_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$status_name = $q->fetchColumn();
		return $status_name;
	}
}
=======
<?php
require_once '/home/careshift.helioho.st/httpdocs/config/connect.php';

class Rooms{
	private $conn;

    public function __construct() {
        $this->conn = Database::getInstance();
    }

	/*Function for creating a new room */
	public function new_room($room_name,$room_slots,$status_id,$department_id){
		
		$data = [
			[$room_name,$room_slots,$status_id,$department_id],
		];
		/*Stores parameters passed from the creation page inside the database */
		$stmt = $this->conn->prepare("INSERT INTO room (room_name, room_slots, status_id, department_id) VALUES (?,?,?,?)");
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

	/*Function for updating a room */
	public function update_room($id,$room_name,$room_slots,$status_id,$department_id){
		/*Updates data from the database using the parameters passed from the profile updating page */
		$sql = "UPDATE room SET room_name=:room_name, room_slots=:room_slots, status_id=:status_id, department_id=:department_id WHERE room_id=:id";

		$q = $this->conn->prepare($sql);
		$q->execute(array(':room_name'=>$room_name, ':room_slots'=>$room_slots, ':status_id'=>$status_id, ':department_id'=>$department_id,':id'=>$id));
		return true;
	}

	/*Function for deleting a room */
	public function delete_room($id)
	{
		/*Deletes data from room selected by the user*/
		$sql = "DELETE FROM room WHERE room_id = :id";
		$stmt = $this->conn->prepare($sql);
		$stmt->bindParam(':id', $id);

		if ($stmt->execute()) {
			return true;
		} else {
			return false;
		}
	}

	/*Function that selects all the records from the admins table */
	public function list_room(){
		$sql="SELECT * FROM room";
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
	/*Function for getting the room id from the database */
	function get_id_by_id($id){
		$sql="SELECT room_id FROM room WHERE room_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$id = $q->fetchColumn();
		return $id;
	}
	/*Function for getting the room id by room name from the database */
	function get_id_by_room_name($room_name){
		$sql="SELECT room_id FROM room WHERE room_name = :room_name";	
		$q = $this->conn->prepare($sql);
		$q->execute(['room_name' => $room_name]);
		$id = $q->fetchColumn();
		return $id;
	}
	/*Function for getting the room name from the database */
	function get_room_name($id){
		$sql="SELECT room_name FROM room WHERE room_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$room_name = $q->fetchColumn();
		return $room_name;
	}
    /*Function for getting the room slots from the database */
	function get_room_slots($id){
		$sql="SELECT room_slots FROM room WHERE room_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$room_slots = $q->fetchColumn();
		return $room_slots;
	}
    /*Function for getting the room status from the database */
	function get_room_status($id){
		$sql="SELECT room_status FROM room WHERE room_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$room_status = $q->fetchColumn();
		return $room_status;
	}
    /*Function for getting the department id from the database */
	function get_room_department_id($id){
		$sql="SELECT department_id FROM room WHERE room_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$department_id = $q->fetchColumn();
		return $department_id;
	}
    /*Function for getting the department name of a room from the database */
	function get_room_department_name($id){
		$sql="SELECT department_name FROM department INNER JOIN room WHERE room.department_id = department.department_id AND room_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$department_name = $q->fetchColumn();
		return $department_name;
	}
    /*Function for getting the status id from the database */
	function get_room_status_id($id){
		$sql="SELECT status_id FROM room WHERE room_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$status_id = $q->fetchColumn();
		return $status_id;
	}
    /*Function for getting the status name of a room from the database */
	function get_room_status_name($id){
		$sql="SELECT status_name FROM status INNER JOIN room WHERE room.status_id = status.status_id AND room_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$status_name = $q->fetchColumn();
		return $status_name;
	}
}
>>>>>>> fa57ef9cad725f4de6f3f7079a21b6419102a7c6
?>