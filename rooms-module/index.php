<<<<<<< HEAD
<div class="content_wrapper">
	<?php
        /*Switch case for the subpage of the Rooms Page */
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
                require_once 'remove.php';
            break; 
            case 'result':
                require_once 'search.php';
            break; 
            default:
                require_once 'records.php';
            break;
        }
    ?>
=======
<div class="content_wrapper">
	<?php
        /*Switch case for the subpage of the Rooms Page */
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
                require_once 'remove.php';
            break; 
            case 'result':
                require_once 'search.php';
            break; 
            default:
                require_once 'records.php';
            break;
        }
    ?>
>>>>>>> fa57ef9cad725f4de6f3f7079a21b6419102a7c6
</div>