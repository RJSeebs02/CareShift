<div class="heading">
    <h1><i class="fas fa-solid fa-user-shield"></i>&nbsp<?php echo $useraccess->get_useraccess_name($id).'\'s ';?>Profile</h1>
    <a href="index.php?page=useraccess" class="right_button"><i class="fa fa-list-ol" aria-hidden="true"></i>&nbspAccess Level List</a>
</div>

<div class="add_form_wrapper">
<form class="add_form" action="processes/process.useraccess.php?action=update" method="POST">
    <div class="form_wrapper">
        <div class="add_form_left">
            <label for="useraccess_name">Access Level Name</label>
            <input type="text" id="useraccess_name" name="useraccess_name" value="<?php echo $useraccess->get_useraccess_name($id);?>" required>
        </div>
        <div class="add_form-right">
            <label for="useraccess_desc">Description</label>
            <input type="text" id="useraccess_desc" name="useraccess_desc" value="<?php echo $useraccess->get_useraccess_desc($id);?>" required>
            
            <label for="id"></label>
            <input type="text" id="id" class="text" name="id" value="<?php echo $useraccess->get_id_by_id($id);?>" hidden>
        </div>
    </div>
    <button type="submit" class="submit-btn">Update Access Level</button>
</form>

<form action="processes/process.useraccess.php?action=delete" method="POST">
    <input type="text" id="id" class="text" name="id" value="<?php echo $useraccess->get_id_by_id($id);?>" hidden>
    <button type="submit" class="delete-btn">Delete User Access</button>
</form>

</div>