<div class="heading">
    <h1><i class="fas fa-solid fa-door-closed"></i>&nbsp<?php echo $rooms->get_room_name($id).' ';?>Details</h1>
    <a href="index.php?page=rooms" class="right_button"><i class="fa fa-list-ol" aria-hidden="true"></i>&nbspRooms List</a>
    <a href="index.php?page=rooms&subpage=add" class="right_button"><i class="fa fa-plus"></i>&nbspAdd Room</a>
</div>

<div class="add_form_wrapper">
<form class="add_form" action="processes/process.rooms.php?action=update" method="POST">
    <div class="form_wrapper">
        <div class="add_form_left">
            <label for="room_name">Room Name</label>
            <input type="text" id="room_name" name="room_name" value="<?php echo $rooms->get_room_name($id);?>" required>

            <label for="room_slots">Room Slots</label>
            <input type="number" id="room_slots" name="room_slots" value="<?php echo $rooms->get_room_slots($id);?>" required>
        </div>
        <div class="add_form-right">
            <label for="department_id">Department</label>
            <select required id="department_id" name="department_id">
                <?php
                if($departments->list_department() != false){
                    foreach($departments->list_department() as $value){
                        extract($value);
                ?>
                            <option value="<?php echo $department_id; ?>" <?php echo ($department_id == $rooms->get_room_department_id($id)) ? 'selected' : ''; ?>>
                                <?php echo $department_name; ?>
                            </option>
                                <?php
                }
            }
            ?>
            </select>

            <label for="status_id">Status</label>
            <select required id="status_id" name="status_id">
                <?php
                if($status->list_status() != false){
                    foreach($status->list_status() as $value){
                        extract($value);
                ?>
                            <option value="<?php echo $status_id; ?>" <?php echo ($status_id == $rooms->get_room_status_id($id)) ? 'selected' : ''; ?>>
                                <?php echo $status_name; ?>
                            </option>
                        <?php
                }
            }
            ?>
            </select>

            <label for="id"></label>
            <input type="text" id="id" class="text" name="id" value="<?php echo $rooms->get_id_by_id($id);?>" hidden>
        </div>
    </div>
    <button type="submit" class="submit-btn">Update Room</button>
</form>

<form action="processes/process.rooms.php?action=delete" method="POST">
    <input type="text" id="id" class="text" name="id" value="<?php echo $rooms->get_id_by_id($id);?>" hidden>
    <button type="submit" class="delete-btn">Delete Room</button>
</form>

</div>