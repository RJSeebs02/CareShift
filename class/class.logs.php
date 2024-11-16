<?php
class Log {
    private $DB_SERVER = 'localhost';
    private $DB_USERNAME = 'root';
    private $DB_PASSWORD = '';
    private $DB_DATABASE = 'db_careshift';
    private $conn;

    public function __construct() {
        $this->conn = new PDO("mysql:host=" . $this->DB_SERVER . ";dbname=" . $this->DB_DATABASE, $this->DB_USERNAME, $this->DB_PASSWORD);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
    }

    public function addLog($action, $description, $adm_id = null) {
        $log_date = date('Y-m-d');
        $log_time = date('H:i:s');

        $query = "INSERT INTO logs (log_action, log_description, log_time_managed, log_date_managed, adm_id) 
                  VALUES (:action, :description, :log_time, :log_date, :adm_id)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':action', $action);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':log_time', $log_time);
        $stmt->bindParam(':log_date', $log_date);
        $stmt->bindParam(':adm_id', $adm_id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /*Function that selects all the records from the logs table, ordered from latest to oldest */
    public function list_logs() {
        $sql = "SELECT * FROM logs ORDER BY log_date_managed DESC, log_time_managed DESC";
        $q = $this->conn->query($sql) or die("failed!");
        while ($r = $q->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $r;
        }
        if (empty($data)) {
            return false;
        } else {
            return $data;    
        }
    }

    
    /*Function for getting the log id from the database */
	function get_id($id){
		$sql="SELECT log_id FROM logs WHERE log_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$id = $q->fetchColumn();
		return $id;
	}

    /*Function for getting the log action from the database */
	function get_action($id){
		$sql="SELECT log_action FROM logs WHERE log_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$action = $q->fetchColumn();
		return $action;
	}

    /*Function for getting the log description from the database */
	function get_desc($id){
		$sql="SELECT log_description FROM logs WHERE log_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$description = $q->fetchColumn();
		return $description;
	}

    /* Function for getting the log time managed from the database */
    function get_time($id) {
        $sql = "SELECT log_time_managed FROM logs WHERE log_id = :id";    
        $q = $this->conn->prepare($sql);
        $q->execute(['id' => $id]);
        $time_managed = $q->fetchColumn();

        if ($time_managed) {
            // Format the time to 'hh mm a'
            $formatted_time = DateTime::createFromFormat('H:i:s', $time_managed)->format('h:i A');
            return $formatted_time;
        }
        return $time_managed;
    }


    /*Function for getting the log date managed from the database */
	function get_date($id){
		$sql="SELECT log_date_managed FROM logs WHERE log_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$date_managed = $q->fetchColumn();
		return $date_managed;
	}

    /*Function for getting the admin_id managed from the database */
	function get_adm_id($id){
		$sql="SELECT adm_id FROM logs WHERE log_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$adm_id = $q->fetchColumn();
		return $adm_id;
	}

    /*Function for getting the admin_id managed from the database */
	function get_adm_name($id){
		$sql="SELECT adm_fname, adm_mname, adm_lname FROM admin INNER JOIN logs WHERE logs.adm_id = admin.adm_id AND log_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		
        // Fetch the result as an associative array
        $admin = $q->fetch(PDO::FETCH_ASSOC);
    
        // Combine first and last name into a full name
        if ($admin) {
            $adm_name = $admin['adm_fname'] . ' ' . $admin['adm_mname'] .' '. $admin['adm_lname'];
            return $adm_name;
        }
	}
}
?>