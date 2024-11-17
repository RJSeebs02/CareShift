<div class="content_wrapper">
	<?php
        /*Switch case for the subpage of the Scanning Page */
        switch($subpage){
            case 'main':
                require_once 'main.php';
            break;
            case 'profile':
                require_once 'profile.php';
            break;
            default:
                require_once 'main.php';
            break;
        }
    ?>
</div>