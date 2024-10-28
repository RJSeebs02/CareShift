<div class="content_wrapper">
	<?php
        /*Switch case for the subpage of the Admins Page */
        switch($subpage){
            case 'records':
                require_once 'records.php';
            break;
            case 'new':
                require_once 'new_leave.php';
            break;
            case 'details':
                require_once 'details.php';
            break;
            default:
                require_once 'records.php';
            break;
        }
    ?>
</div>