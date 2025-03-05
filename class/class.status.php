<?php
require_once '/home/careshift.helioho.st/httpdocs/config/connect.php';

class Status{
	private $conn;

    public function __construct() {
        $this->conn = Database::getInstance();
    }

	/*Function that selects all the records from the admins table */
	public function list_status(){
		$sql="SELECT * FROM status";
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
	/*Function for getting the status id from the database */
	function get_id_by_id($id){
		$sql="SELECT status_id FROM status WHERE status_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$id = $q->fetchColumn();
		return $id;
	}
	/*Function for getting status id by username from the database */
	function get_id_by_status_name($status_name){
		$sql="SELECT status_id FROM status WHERE status_name = :status_name";	
		$q = $this->conn->prepare($sql);
		$q->execute(['status_name' => $status_name]);
		$id = $q->fetchColumn();
		return $id;
	}
    /*Function for getting the status name from the database */
	function get_status_name($id){
		$sql="SELECT status_name FROM status WHERE status_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$status_name = $q->fetchColumn();
		return $status_name;
	}
}
?>