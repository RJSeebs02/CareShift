<?php
$admin_id = $admin->get_id_by_username($_SESSION['adm_username']);
$access_id = $admin->get_access_id($admin_id);
$scheduler_department_id = $admin->get_department_id($admin_id);

if ($access_id == 3) { 
    $nurses = $nurse->list_nurses_by_department($scheduler_department_id);
} else {
    $nurses = $nurse->list_nurses(); 
}
?>

<div class="heading">
    <h1><i class="fas fa-solid fa-qrcode"></i>&nbspScan QR Nurse</h1>
	<a href="index.php?page=scan" class="right_button <?= $page == 'scan' && !isset($_GET['subpage']) ? 'active' : '' ?>">
		<i class="fas fa-solid fa-qrcode"></i></i>&nbspScan</a>
	<a href="index.php?page=scan&subpage=records" class="right_button <?= $page == 'scan' && $subpage == 'records' ? 'active' : '' ?>">
		<i class="fa fa-list-ol" aria-hidden="true"></i>&nbspAttendance</a>
</div>

<div class="qr_container">
    <h1>Scan a Nurse QR Using the QR Code Scanner</h1>
    <i class="fas fa-solid fa-qrcode"></i>
</div>

