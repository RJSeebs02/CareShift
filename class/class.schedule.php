<?php
require_once '/home/careshift.helioho.st/httpdocs/config/connect.php';

class Schedule{
	private $conn;

    public function __construct() {
        $this->conn = Database::getInstance();
    }

    public function getCurrentWeekDates($weekOffset = 0) {
        $dates = [];
        $currentDate = new DateTime();
        $currentDate->modify("Sunday this week");
        $currentDate->modify("{$weekOffset} week");

        for ($i = 0; $i < 7; $i++) {
            $dates[] = $currentDate->format('Y-m-d');
            $currentDate->modify('+1 day'); 
        }
    
        return $dates;
    }
    
    public function schedule_exists($nurse_id, $sched_date) {
        $query = "SELECT * FROM schedule WHERE nurse_id = :nurse_id AND sched_date = :sched_date";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':nurse_id', $nurse_id);
        $stmt->bindValue(':sched_date', $sched_date);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
	
	public function is_nurse_on_leave($nurse_id, $sched_date) {
		$query = "SELECT * FROM `leave` WHERE nurse_id = :nurse_id 
				  AND (leave_start_date <= :sched_date AND leave_end_date >= :sched_date) 
				  AND leave_status = 'Approved'"; // Ensure leave is approved
		$stmt = $this->conn->prepare($query);
		$stmt->bindValue(':nurse_id', $nurse_id);
		$stmt->bindValue(':sched_date', $sched_date);
		$stmt->execute();
		return $stmt->rowCount() > 0;
	}

    public function new_schedule($nurse_id, $sched_date, $sched_start_time, $work_hours){
        // Calculate the end time based on work hours
        $start_time = new DateTime($sched_date . ' ' . $sched_start_time);
        $end_time = clone $start_time; // Clone to avoid modifying the original start time
        $end_time->modify("+{$work_hours} hours");

        // Format the times for insertion into the database
        $formatted_start_time = $start_time->format('H:i:s');
        $formatted_end_time = $end_time->format('H:i:s');
        $end_date = $end_time->format('Y-m-d'); // In case the shift crosses over to the next day
		
		$sched_type = "Work";

        // Prepare the data for insertion
        $data = [
            [$nurse_id, $sched_date, $formatted_start_time, $formatted_end_time, $sched_type],
        ];

        // Insert the schedule into the database
        $stmt = $this->conn->prepare("INSERT INTO schedule (nurse_id, sched_date, sched_start_time, sched_end_time, sched_type) VALUES (?,?,?,?,?)");
        
        try {
            $this->conn->beginTransaction();
            foreach ($data as $row) {
                $stmt->execute($row);
            }
            $this->conn->commit();
        } catch (Exception $e) {
            $this->conn->rollback();
            throw $e;
        }

        return true;
    }

    public function generate_schedule($nurse_id, $sched_date, $sched_start_time, $sched_end_time) {
        // Prepare the data for insertion with provided start and end times
		$sched_type = "Work";
		
        $data = [
            [$nurse_id, $sched_date, $sched_start_time, $sched_end_time, $sched_type],
        ];
    
        // Insert the schedule into the database
        $stmt = $this->conn->prepare("INSERT INTO schedule (nurse_id, sched_date, sched_start_time, sched_end_time, sched_type) VALUES (?, ?, ?, ?, ?)");
        
        try {
            $this->conn->beginTransaction();
            foreach ($data as $row) {
                $stmt->execute($row);
            }
            $this->conn->commit();
        } catch (Exception $e) {
            $this->conn->rollback();
            throw $e;
        }
    
        return true;
    }
    

    public function fetch_schedule($nurse_id = 'all') {
        // Prepare the base query
        $query = "
            SELECT s.nurse_id, s.sched_date, s.sched_start_time, s.sched_end_time, n.nurse_lname, n.nurse_position
            FROM schedule s
            JOIN nurse n ON s.nurse_id = n.nurse_id
        ";

        // If a specific nurse is selected, modify the query
        if ($nurse_id !== 'all') {
            $query .= " WHERE s.nurse_id = :nurse_id";
        }

        $stmt = $this->conn->prepare($query);

        // If a specific nurse is selected, bind the parameter
        if ($nurse_id !== 'all') {
            $stmt->bindParam(':nurse_id', $nurse_id, PDO::PARAM_INT);
        }

        $stmt->execute();

        $events = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Format the start time
            $start_time = $row['sched_date'] . 'T' . $row['sched_start_time'];

            // Check if the shift ends after midnight
            if ($row['sched_end_time'] < $row['sched_start_time']) {
                $end_date = new DateTime($row['sched_date']);
                $end_date->modify('+1 day');
                $end_time = $end_date->format('Y-m-d') . 'T' . $row['sched_end_time'];
            } else {
                $end_time = $row['sched_date'] . 'T' . $row['sched_end_time'];
            }

            $nurse_name = htmlspecialchars($row['nurse_lname'] . ', ' . $row['nurse_position']);

            $events[] = [
                'title' => $nurse_name,
                'start' => $start_time,
                'end' => $end_time,
                'allDay' => false,
            ];
        }

        return json_encode($events);
    }

    /*Function for updating a schedule */
	public function update_schedule($eventSchedId,$eventDate,$eventStart,$eventEnd){
		/*Updates data from the database using the parameters passed from the schedule updating page */
		$sql = "UPDATE schedule SET sched_date=:eventDate, sched_start_time=:eventStart, sched_end_time=:eventEnd WHERE sched_id=:id";

		$q = $this->conn->prepare($sql);
		$q->execute(array(':eventDate'=>$eventDate, ':eventStart'=>$eventStart, ':eventEnd'=>$eventEnd, ':id'=>$eventSchedId));
		return true;
	}

	/* Function to get the shift code for a nurse on a specific date */
public function get_shift_code($nurse_id, $date) {
    // Format date for consistency (ensure it follows the 'YYYY-MM-DD' format)
    $formatted_date = date('Y-m-d', strtotime($date));
    
    // First, check if the nurse's schedule is a leave
    $sql = "SELECT sched_type, sched_start_time, sched_end_time
            FROM schedule
            WHERE nurse_id = :nurse_id AND DATE(sched_date) = :date";

    $q = $this->conn->prepare($sql);
    $q->execute(['nurse_id' => $nurse_id, 'date' => $formatted_date]);
    $shift = $q->fetch();

    if ($shift) {
        // Check if the sched_type is 'Leave'
        if (strtolower($shift['sched_type']) == 'leave') {
            return 'L';  // Return 'L' if the schedule type is Leave
        }

        // If it's not a leave, determine the shift code based on start and end times
        // You can use LIKE to account for shifts starting at specific times
        if (strpos($shift['sched_start_time'], '06:00') === 0 && strpos($shift['sched_end_time'], '14:00') === 0) {
            return 'A';  // 6am to 2pm
        } elseif (strpos($shift['sched_start_time'], '14:00') === 0 && strpos($shift['sched_end_time'], '22:00') === 0) {
            return 'P';  // 2pm to 10pm
        } elseif (strpos($shift['sched_start_time'], '22:00') === 0 && strpos($shift['sched_end_time'], '06:00') === 0) {
            return 'G';  // 10pm to 6am
        } else {
            return 'C'; // Custom Time
        }
    }

    return '';  // Return empty if no shift found or not a leave
}

	
	public function get_last_shift($nurse_id) {
		$sql = "SELECT sched_start_time, sched_end_time 
				FROM schedule 
				WHERE nurse_id = ? 
				ORDER BY sched_start_date DESC, sched_start_time DESC 
				LIMIT 1";
		$stmt = $this->conn->prepare($sql);
		$stmt->bindValue(1, $nurse_id, PDO::PARAM_INT);
		$stmt->execute();

		$result = $stmt->fetch(PDO::FETCH_ASSOC);

		if ($result) {
			return $result; // Return the last shift details
		}

		return null; // No previous shifts found
	}

	function get_schedule_nurse_id($id){
		$sql="SELECT nurse_id FROM schedule WHERE sched_id = :id";
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$nurse_id = $q->fetchColumn();
		return $nurse_id;
	}

	/*Function for getting the admin_id managed from the database */
	function get_nurse_name($nurse_id) {
		$sql = "SELECT nurse_fname, nurse_mname, nurse_lname 
				FROM nurse 
				WHERE nurse_id = :nurse_id";  // Corrected to use nurse_id directly
		$q = $this->conn->prepare($sql);
		$q->execute(['nurse_id' => $nurse_id]);

		// Fetch the result as an associative array
		$nurse = $q->fetch(PDO::FETCH_ASSOC);

		// Combine first, middle, and last names into a full name
		if ($nurse) {
			$nurse_name = $nurse['nurse_fname'] . ' ' . $nurse['nurse_mname'] . ' ' . $nurse['nurse_lname'];
			return $nurse_name;
		}
	}
	
	/*Function for getting the sched type from the database */
	function get_sched_type($id){
		$sql="SELECT sched_type FROM schedule WHERE sched_id = :id";	
		$q = $this->conn->prepare($sql);
		$q->execute(['id' => $id]);
		$sched_id = $q->fetchColumn();
		return $sched_id;
	}
	
	/*Function for deleting a sched */
	public function delete_schedule($id)
	{
		/*Deletes data from sched selected by the user*/
		$sql = "DELETE FROM schedule WHERE sched_id = :id";
		$stmt = $this->conn->prepare($sql);
		$stmt->bindParam(':id', $id);

		if ($stmt->execute()) {
			return true;
		} else {
			return false;
		}
	}

}