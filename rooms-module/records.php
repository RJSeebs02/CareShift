<div class="heading">
    <h1><i class="fas fa-solid fa-door-closed"></i>&nbspRooms</h1>
    <a href="index.php?page=rooms&subpage=add" class="right_button"><i class="fa fa-plus"></i>&nbspAdd Room</a>
</div>
<span class="right">
    <div class="search_bar">Search:<input type="text" id="search" name="search" onkeyup="">
    </div>
</span>

<table id="tablerecords">   
    <thead>
        <tr>
            <th>Room ID</th>
            <th>Room Name</th>
            <th>Nurse Slots</th>
            <th>Department</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php
        /* Display each admin record located in the database */
        if ($rooms->list_room() != false) {
            foreach ($rooms->list_room() as $value) {
                extract($value);
                // Create a link for each row using the nurse_id
                $row_url = "index.php?page=rooms&subpage=profile&id=" . $room_id;
                ?>
                <tr onclick="location.href='<?php echo $row_url; ?>'" style="cursor: pointer;">
                    <td><?php echo $room_id; ?></td>
                    <td><?php echo $room_name; ?></td>
                    <td><?php echo $room_slots; ?></td>
                    <td><?php echo $rooms->get_room_department_name($room_id); ?></td>
                    <td><?php echo $rooms->get_room_status_name($room_id); ?></td>
                </tr>
                <?php
            }
        } else {
            ?>
            <tr>
                <td colspan="5">No Record Found.</td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>
