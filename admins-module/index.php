<div class="content_wrapper">
	<?php
        /*Switch case for the subpage of the Admins Page */
        switch($subpage){
            case 'add':
                require_once 'add.php';
            break;
            case 'records':
                require_once 'records.php';
            break; 
            case 'profile':
                require_once 'profile.php';
            break; 
            case 'remove':
                require_once 'remove-admin.php';
            break; 
            case 'result':
                require_once 'search.php';
            break; 
            default:
                require_once 'records.php';
            break;
        }
    ?>
</div>