<?php
require_once '/home/careshift.helioho.st/httpdocs/config/connect.php';

class Notifications{
	private $conn;

    public function __construct() {
        $this->conn = Database::getInstance();
    }

    public function addNotifications($notif_type, $notif_title, $notif_msg, $nurse_id) {

        // Get current date and time
        $notif_date = date("Y-m-d");  // Current date (YYYY-MM-DD)
        $notif_time = date("H:i:s");  // Current time (HH:MM:SS)

        // Insert the notification into the database
        $query = "INSERT INTO notifications (notif_type, notif_title, notif_msg, notif_date, notif_time, nurse_id) 
                  VALUES (:notif_type, :notif_title, :notif_msg, :notif_date, :notif_time, :nurse_id)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':notif_type', $notif_type);
        $stmt->bindParam(':notif_title', $notif_title);
        $stmt->bindParam(':notif_msg', $notif_msg);
        $stmt->bindParam(':notif_date', $notif_date);
        $stmt->bindParam(':notif_time', $notif_time);
        $stmt->bindParam(':nurse_id', $nurse_id);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
?>