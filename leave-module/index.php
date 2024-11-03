<div class="content_wrapper">
	<?php
        /*Switch case for the subpage of the Admins Page */
        switch($subpage){
            case 'records':
                require_once 'records.php';
            break;
            case 'add':
                require_once 'add.php';
            break;
            case 'profile':
                require_once 'profile.php';
            break;
            default:
                require_once 'records.php';
            break;
        }
    ?>
</div>