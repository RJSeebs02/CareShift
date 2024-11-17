<div class="heading">
    <h1><i class="fas fa-solid fa-qrcode"></i>&nbsp<?php echo $nurse->get_fname($id).' '.$nurse->get_lname($id).'\'s ';?>QR Profile</h1>
    <a href="index.php?page=scan" class="right_button"><i class="fa fa-solid fa-left-long" aria-hidden="true"></i>&nbspBack</a>
</div>

<div class="qr_profile_container">
    <div class="form_wrapper">
    <img src="img/default_profile_pic.jpg" alt="Profile Image" class="profile_image">
        <div class="add_form_left">

            <p><span class="bold">Nurse ID:</span> <?php echo $nurse->get_id($id);?></p>

            <p><span class="bold">First Name:</span> <?php echo $nurse->get_fname($id);?></p>

            <p><span class="bold">Middle Name:</span> <?php echo $nurse->get_mname($id);?></p>

            <p><span class="bold">Last Name:</span> <?php echo $nurse->get_lname($id);?></p>

            <p><span class="bold">Email:</span> <?php echo $nurse->get_email($id);?></p>

            <p><span class="bold">Sex:</span> <?php echo $nurse->get_sex($id);?></p>

            <p><span class="bold">Position:</span> <?php echo $nurse->get_position($id);?></p>

            <p><span class="bold">Contact No.:</span> <?php echo $nurse->get_contact($id);?></p>

            <p><span class="bold">Department:</span> <?php echo $nurse->get_nurse_department_name($id);?>

        </div>
    </div>
</div>