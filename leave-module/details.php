<?php
$leave_id = $_GET['id']; // Get the leave_id from the URL

// Fetch leave details using the leave ID
$sql = "SELECT l.*, n.nurse_fname, n.nurse_lname, n.nurse_department 
        FROM `leave` l 
        JOIN nurse n ON l.nurse_id = n.nurse_id 
        WHERE l.leave_id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $leave_id); // "i" means the parameter is an integer
$stmt->execute();
$result = $stmt->get_result();
$leaveDetails = $result->fetch_assoc();

if (!$leaveDetails) {
    die("Leave application not found.");
}

// Handle status update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $status = $_POST['leave_status']; // This will be the updated status
    
    $update_sql = "UPDATE `leave` SET leave_status = ? WHERE leave_id = ?";
    $update_stmt = $con->prepare($update_sql);
    $update_stmt->bind_param("si", $status, $leave_id); // "si" means string and integer
    $update_stmt->execute();

    // Redirect back to the leave applicants list after processing
    header("Location: index.php?page=leave");
    exit();
}
?>

<div class="heading">
    <h1><i class="fas fa-regular fa-paste"></i>&nbspLeave Application Details for <?php echo $leaveDetails['nurse_fname'] . ' ' . $leaveDetails['nurse_lname']; ?></h1>
    <a href="index.php?page=leave" class="right_button"><i class="fa fa-list-ol" aria-hidden="true"></i>&nbspLeave Applicants</a>
</div>

<div class="add_form_wrapper">
<form class="add_form" action="details.php?id=<?php echo $leave_id; ?>" method="POST">
    <div class="form_wrapper">
        <div class="add_form_left">

            <label for="applicant_name">Applicant Name:</label>
            <input type="text" id="applicant_name" name="applicant_name" value="<?php echo $leaveDetails['nurse_fname'] . ' ' . $leaveDetails['nurse_lname']; ?>" disabled>

            <label for="department">Department:</label>
            <input type="text" id="department" name="department" value="<?php echo $leaveDetails['nurse_department']; ?>" disabled>

            <label for="leave_type">Leave Type:</label>
            <input type="text" id="leave_type" name="leave_type" value="<?php echo $leaveDetails['leave_type']; ?>" disabled>

            <label for="leave_start_date">Leave Start Date:</label>
            <input type="date" id="leave_start_date" name="leave_start_date" value="<?php echo $leaveDetails['leave_start_date']; ?>" disabled>

            <label for="leave_end_date">Leave End Date:</label>
            <input type="date" id="leave_end_date" name="leave_end_date" value="<?php echo $leaveDetails['leave_end_date']; ?>" disabled>

            <label for="description">Description:</label>
            <textarea id="description" name="description" disabled><?php echo $leaveDetails['leave_desc']; ?></textarea>
        </div>
        <div class="add_form_right">

            <label for="leave_status">Leave Status:</label>
            <select id="leave_status" name="leave_status" required>
                <option value="<?php echo $leaveDetails['leave_status']; ?>"><?php echo $leaveDetails['leave_status']; ?></option>
                <option value="approved">Approve</option>
                <option value="denied">Deny</option>
                <option value="pending">Pending</option>
            </select>

            <!-- Hidden input for leave_id -->
            <input type="text" id="leave_id" class="text" name="leave_id" value="<?php echo $leave_id; ?>" hidden>
        </div>
    </div>
    <button type="submit" class="submit-btn">Update Leave Status</button>
</form>
</div>
