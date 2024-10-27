<div class="content_wrapper">
	<?php
        /*Switch case for the subpage of the Admins Page */
        switch($subpage){
            case 'records':
                require_once 'records.php';
            break;
            default:
                require_once 'records.php';
            break;
        }
    ?>
</div>