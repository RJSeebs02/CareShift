<?php
class Nurse {
 
    // database connection and table name
    private $conn;
    private $table_name = "nurse";
 
    // object properties
    public $nurse_email;
    public $nurse_password;
    public $nurse_fname;
    public $nurse_mname;
    public $nurse_lname;
    public $nurse_sex;
    public $nurse_contact;
    public $nurse_position;
    public $department_id;

    public function __construct($db) {
        $this->conn = $db; 
    }

    function create() {

        $query = "INSERT INTO " . $this->table_name . "
                SET nurse_email = :nurse_email,
                    nurse_password = :nurse_password,
                    nurse_fname = :nurse_fname,
                    nurse_mname = :nurse_mname,
                    nurse_lname = :nurse_lname,
                    nurse_sex = :nurse_sex,
                    nurse_contact = :nurse_contact,
                    nurse_position = :nurse_position,
                    department_id = :department_id";

        $stmt = $this->conn->prepare($query);

        // Sanitize and bind parameters
        $stmt->bindParam(":nurse_email", $this->nurse_email);
        $stmt->bindParam(":nurse_password", $this->nurse_password); // Directly bind the plain password
        $stmt->bindParam(":nurse_fname", $this->nurse_fname);
        $stmt->bindParam(":nurse_mname", $this->nurse_mname);
        $stmt->bindParam(":nurse_lname", $this->nurse_lname);
        $stmt->bindParam(":nurse_sex", $this->nurse_sex);
        $stmt->bindParam(":nurse_contact", $this->nurse_contact);
        $stmt->bindParam(":nurse_position", $this->nurse_position);
        $stmt->bindParam(":department_id", $this->department_id);

        return $stmt->execute();
    }
	
	function update() {
		// Update query without image
		$query = "UPDATE " . $this->table_name . "
				  SET 
					  nurse_email = :nurse_email,
					  nurse_fname = :nurse_fname,
					  nurse_mname = :nurse_mname,
					  nurse_lname = :nurse_lname,
					  nurse_contact = :nurse_contact
				  WHERE nurse_id = :nurse_id";

		$stmt = $this->conn->prepare($query);

		// Bind parameters for the text fields
		$stmt->bindParam(":nurse_email", $this->nurse_email);
		$stmt->bindParam(":nurse_fname", $this->nurse_fname);
		$stmt->bindParam(":nurse_mname", $this->nurse_mname);
		$stmt->bindParam(":nurse_lname", $this->nurse_lname);
		$stmt->bindParam(":nurse_contact", $this->nurse_contact);
		$stmt->bindParam(":nurse_id", $this->nurse_id);

		// Execute the query
		if ($stmt->execute()) {
			return true;
		}

		// If execution fails, return error info for debugging
		print_r($stmt->errorInfo());
		return false;
	}

	
	public function updatePassword() {
		$query = "UPDATE " . $this->table_name . " SET nurse_password = :nurse_password WHERE nurse_id = :nurse_id";
		$stmt = $this->conn->prepare($query);

		// Bind parameters
		$stmt->bindParam(":nurse_password", $this->nurse_password);
		$stmt->bindParam(":nurse_id", $this->nurse_id);

		// Execute the query
		if ($stmt->execute()) {
			return true; // Password updated successfully
		}

		return false; // Unable to update password
	}

    public function validate() {
        // Query to select nurse with matching email
        $query = "SELECT * FROM " . $this->table_name . " WHERE nurse_email = :nurse_email LIMIT 1";
        
        // Prepare query
        $stmt = $this->conn->prepare($query);
        
        // Sanitize input
        $this->nurse_email = htmlspecialchars(strip_tags(trim($this->nurse_email)));
        
        // Bind parameter
        $stmt->bindParam(":nurse_email", $this->nurse_email);

        if ($stmt->execute()) {
            if ($stmt->rowCount() == 1) {
                // Fetch the user data
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                // Directly compare the provided password with the stored one
                if ($this->nurse_password === $row['nurse_password']) {
                    return true; // Valid credentials
                }
            }
        }

        return false; // Invalid credentials or error
    }
	
	public function validatePassword($inputPassword) {
		// Query to retrieve the nurse's stored password (plaintext) by nurse_id
		$query = "SELECT nurse_password FROM " . $this->table_name . " WHERE nurse_id = :nurse_id LIMIT 1";

		$stmt = $this->conn->prepare($query);
		$this->nurse_id = htmlspecialchars(strip_tags(trim($this->nurse_id)));
		$stmt->bindParam(":nurse_id", $this->nurse_id);

		if ($stmt->execute()) {
			if ($stmt->rowCount() == 1) {
				$row = $stmt->fetch(PDO::FETCH_ASSOC);

				// Compare the entered password (plaintext) with the stored password (plaintext)
				if ($inputPassword === $row['nurse_password']) {
					return true; // Password is valid
				}
			}
		}

		return false; // Invalid password or error
	}

	public function readSingle($nurseId) {
		$query = '
			SELECT 
				n.nurse_id, 
				n.nurse_fname, 
				n.nurse_mname, 
				n.nurse_lname, 
				n.nurse_email, 
				n.nurse_contact, 
				n.nurse_position, 
				n.department_id, 
				d.department_name, 
				n.nurse_img
			FROM 
				nurse n
			LEFT JOIN 
				department d ON n.department_id = d.department_id
			WHERE 
				n.nurse_id = :nurse_id
			LIMIT 1
		';

		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':nurse_id', $nurseId);

		if ($stmt->execute()) {
			if ($stmt->rowCount() > 0) {
				$result = $stmt->fetch(PDO::FETCH_ASSOC);

				// Check if the nurse image exists and set the correct URL
				if ($result['nurse_img']) {
					$imagePath = $result['nurse_img'];
					// Make sure the image file exists
					if (file_exists('../../../upload/' . $imagePath)) {
						$result['nurse_img_url'] = 'http://careshift.helioho.st/upload/' . $imagePath;
					} else {
						$result['nurse_img_url'] = null; // If the image doesn't exist
					}
				}

				return $result;
			} else {
				return null;
			}
		} else {
			print_r($stmt->errorInfo());
			return null;
		}
	}
	
	public function getProfileImage() {
        // Query to get nurse image based on nurse_id
        $query = "SELECT nurse_img FROM " . $this->table_name . " WHERE nurse_id = :nurse_id LIMIT 1";

        // Prepare statement
        $stmt = $this->conn->prepare($query);
        
        // Bind the nurse_id parameter to the query
        $stmt->bindParam(':nurse_id', $this->nurse_id, PDO::PARAM_INT);

        // Execute the query
        $stmt->execute();

        // Check if any result was returned
        if ($stmt->rowCount() > 0) {
            // Fetch the result
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row;
        } else {
            return null; // Return null if no image is found
        }
    }

    // Get nurse details by email
    public function getNurseByEmail($email) {
        // Query to retrieve nurse details by email
        $query = "SELECT nurse_id, nurse_password FROM " . $this->table_name . " WHERE nurse_email = :email LIMIT 1";
        // Prepare query
        $stmt = $this->conn->prepare($query);
        
        // Bind parameter
        $stmt->bindParam(":email", $email);
        
        // Execute query
        $stmt->execute();
        
        // Check if a record was found
        if ($stmt->rowCount() > 0) {
            // Return nurse details as an associative array
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return false; // No nurse found
    }
}
?>
