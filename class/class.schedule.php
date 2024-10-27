<!--Schedule Class File-->
<?php
class Schedule{
    private $DB_SERVER='localhost';
    private $DB_USERNAME='root';
    private $DB_PASSWORD='';
    private $DB_DATABASE='db_careshift';
    private $conn;
    public function __construct(){
        $this->conn = new PDO("mysql:host=".$this->DB_SERVER.";dbname=".$this->DB_DATABASE, $this->DB_USERNAME, $this->DB_PASSWORD);
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

        // Prepare the data for insertion
        $data = [
            [$nurse_id, $sched_date, $formatted_start_time, $formatted_end_time],
        ];

        // Insert the schedule into the database
        $stmt = $this->conn->prepare("INSERT INTO schedule (nurse_id, sched_date, sched_start_time, sched_end_time) VALUES (?,?,?,?)");
        
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
}