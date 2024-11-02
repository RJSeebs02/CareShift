<div class="heading">
<h1><i class="fas fa-solid fa-user-shield"></i>&nbspAdd User Access</h1>
    <a href="index.php?page=useraccess" class="right_button"><i class="fa fa-list-ol" aria-hidden="true"></i>&nbspAccess Level List</a>
</div>

<div class="add_form_wrapper">
<form class="add_form" action="processes/process.useraccess.php?action=new" method="POST">
    <div class="form_wrapper">
        <div class="add_form_left">
            <label for="useraccess_name">Access Level Name</label>
            <input type="text" id="useraccess_name" name="useraccess_name" placeholder="Enter User Access Level Name" required>
        </div>
        <div class="add_form-right">
            <label for="useraccess_desc">Description</label>
            <input type="text" id="useraccess_desc" name="useraccess_desc" placeholder="Enter Description" required>
        </div>
    </div>
    <button type="submit" class="submit-btn">Add Access Level</button>
</form>
</div>