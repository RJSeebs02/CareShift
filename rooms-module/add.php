<div class="heading">
    <h1><i class="fas fa-solid fa-door-closed"></i>&nbspRooms</h1>
    <a href="index.php?page=rooms" class="right_button"><i class="fa fa-list-ol" aria-hidden="true"></i>&nbspRooms List</a>
    <a href="index.php?page=rooms&subpage=add" class="right_button"><i class="fa fa-plus"></i>&nbspAdd Room</a>
</div>

<div class="add_form_wrapper">
<form class="add_form" action="processes/process.rooms.php?action=new" method="POST">
    <div class="form_wrapper">
        <div class="add_form_left">
            <label for="room_name">Room Name</label>
            <input type="text" id="room_name" name="room_name" placeholder="Enter Room Name" required>

            <label for="room_slots">Room Slots</label>
            <input type="number" id="room_slots" name="room_slots" placeholder="Enter Room Slot" required>
        </div>
        <div class="add_form-right">
            <label for="department_id">Department</label>
            <select required id="department_id" name="department_id">
                <option value="" disabled selected>Select Department</option>
                    <?php
                    if($departments->list_department() != false){
                        foreach($departments->list_department() as $value){
                            extract($value);
                            ?>
                            <option value="<?php echo $department_id;?>"><?php echo $department_name; ?></option>
                            <?php
                        }
                    }
                ?>
            </select>
        </div>
    </div>
    <button type="submit" class="submit-btn">Add Room</button>
</form>
</div>