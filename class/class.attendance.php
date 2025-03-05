<?php
require_once '/home/careshift.helioho.st/httpdocs/config/connect.php';

class Attendance{
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

	public function new_attendance($nurse_id, $att_date, $att_time) {
		$stmt = $this->conn->prepare("INSERT INTO attendance (nurse_id, att_date, att_time) VALUES (?, ?, ?)");

		try {
			$this->conn->beginTransaction();
			$stmt->execute([$nurse_id, $att_date, $att_time]);
			$this->conn->commit();
		} catch (Exception $e) {
			$this->conn->rollback();
			throw $e;
		}

		return true;
	}
	
	public function update_checkout_time($nurse_id, $att_date, $att_out_time) {
		// Prepare the UPDATE statement
		$stmt = $this->conn->prepare("UPDATE attendance SET att_out_time = ? WHERE nurse_id = ? AND att_date = ?");

		try {
			// Begin a transaction
			$this->conn->beginTransaction();

			// Execute the statement with the provided parameters
			$stmt->execute([$att_out_time, $nurse_id, $att_date]);

			// Commit the transaction if the execution was successful
			$this->conn->commit();
		} catch (Exception $e) {
			// Rollback the transaction in case of an error
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
		// First, check if the nurse's schedule is a leave
		$sql = "SELECT sched_type, sched_start_time, sched_end_time
				FROM schedule
				WHERE nurse_id = :nurse_id AND sched_date = :date";

		$q = $this->conn->prepare($sql);
		$q->execute(['nurse_id' => $nurse_id, 'date' => $date]);
		$shift = $q->fetch();

		if ($shift) {
			// Check if the sched_type is 'Leave'
			if (strtolower($shift['sched_type']) == 'leave') {
				return 'L';  // Return 'L' if the schedule type is Leave
			}

			// If it's not a leave, determine the shift code based on start and end times
			if ($shift['sched_start_time'] == '06:00:00' && $shift['sched_end_time'] == '14:00:00') {
				return 'A';  // 6am to 2pm
			} elseif ($shift['sched_start_time'] == '14:00:00' && $shift['sched_end_time'] == '22:00:00') {
				return 'P';  // 2pm to 10pm
			} elseif ($shift['sched_start_time'] == '22:00:00' && $shift['sched_end_time'] == '06:00:00') {
				return 'G';  // 10pm to 6am
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
	
	public function check_attendance($nurse_id, $att_date) {
		$sql = "SELECT att_id, att_date, att_time, att_out_time 
				FROM attendance 
				WHERE nurse_id = :nurse_id AND att_date = :att_date";

		$stmt = $this->conn->prepare($sql);
		$stmt->execute([':nurse_id' => $nurse_id, ':att_date' => $att_date]);

		return $stmt->fetch(PDO::FETCH_ASSOC); // Return attendance record with check-in and check-out time
	}
	
	public function check_out($nurse_id, $att_date) {
		$sql = "UPDATE attendance 
				SET att_out_time = NOW(), status = 'Off Duty' 
				WHERE nurse_id = :nurse_id AND att_date = :att_date";

		$stmt = $this->conn->prepare($sql);
		return $stmt->execute([':nurse_id' => $nurse_id, ':att_date' => $att_date]);
	}

	
	function get_nurse_id_by_schedule($selectedDate) {
		$selectedDate = date('Y-m-d', strtotime($selectedDate));

		$sql = "SELECT n.nurse_id, n.nurse_fname, n.nurse_mname, n.nurse_lname 
				FROM schedule s
				INNER JOIN nurse n ON s.nurse_id = n.nurse_id
				WHERE DATE(s.sched_date) = :selectedDate
				AND s.sched_type != 'Leave'";

		$q = $this->conn->prepare($sql);
		$q->execute([':selectedDate' => $selectedDate]);

		$results = $q->fetchAll(PDO::FETCH_ASSOC);
		if (empty($results)) {
			echo "No records found for the selected date.";
		}

		return $results;
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

	public function calculate_total_time($nurse_id, $att_date) {
		// SQL query to fetch check-in and check-out times
		$sql = "SELECT att_time, att_out_time 
				FROM attendance 
				WHERE nurse_id = :nurse_id AND att_date = :att_date";

		$stmt = $this->conn->prepare($sql);
		$stmt->execute([':nurse_id' => $nurse_id, ':att_date' => $att_date]);

		$result = $stmt->fetch(PDO::FETCH_ASSOC);

		if ($result) {
			$check_in = new DateTime($result['att_time']);
			$check_out = new DateTime($result['att_out_time']);

			// Calculate the interval between check-in and check-out
			$interval = $check_in->diff($check_out);

			// Return the total time in hours, minutes, and seconds
			return [
				'hours' => $interval->h,
				'minutes' => $interval->i,
				'seconds' => $interval->s,
				'formatted' => $interval->format('%H:%I:%S') // Optional: formatted string
			];
		}

		// If no attendance record is found, return null or an appropriate message
		return null;
	}
	
	public function calculate_total_timechart($nurse_id, $att_date) {
		// SQL query to fetch check-in and check-out times
		$sql = "SELECT att_time, att_out_time 
				FROM attendance 
				WHERE nurse_id = :nurse_id AND att_date = :att_date";

		$stmt = $this->conn->prepare($sql);
		$stmt->execute([':nurse_id' => $nurse_id, ':att_date' => $att_date]);

		$result = $stmt->fetch(PDO::FETCH_ASSOC);

		if ($result) {
			$check_in = new DateTime($result['att_time']);
			$check_out = new DateTime($result['att_out_time']);

			// Calculate the interval between check-in and check-out
			$interval = $check_in->diff($check_out);

			// Return total hours as a decimal (e.g., 7.5 hours)
			return round($interval->h + $interval->i / 60 + $interval->s / 3600, 2);
		}

		// If no attendance record is found, return 0
		return 0;
	}
	
	public function calculate_daily_hours($nurse_id, $date) {
		echo "Date passed: " . $date . "\n";
		$stmt = $this->conn->prepare("
			SELECT 
				att_time, 
				att_out_time
			FROM attendance
			WHERE nurse_id = :nurse_id 
			  AND (DATE(att_time) = :date OR DATE(att_out_time) = :date)
			  AND att_out_time IS NOT NULL
		");
		$stmt->execute([
			'nurse_id' => $nurse_id,
			'date' => $date
		]);

		$totalMinutes = 0;

		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$att_time = new DateTime($row['att_time']);
			$att_out_time = new DateTime($row['att_out_time']);

			// Handle shifts spanning midnight
			if ($att_out_time < $att_time) {
				$att_out_time->modify('+1 day');
			}

			// Calculate the difference
			$interval = $att_time->diff($att_out_time);
			$totalMinutes += ($interval->h * 60) + $interval->i; // Convert hours and minutes to total minutes
		}

		// Convert minutes to hours, rounded to two decimal places
		echo "Hours: " . round($totalMinutes / 60, 2) . "\n";
		return round($totalMinutes / 60, 2);
		
		
	}

	


}