<div class="content_wrapper">
	<?php
        /*Switch case for the subpage of the Schedule Page */
        switch($subpage){
            case 'main':
                require_once 'main.php';
            break; 
            case 'add':
                require_once 'add_schedule.php';
            break;
            case 'generate':
                require_once 'generate_schedule.php';
            break; 
            case 'edit':
                require_once 'edit.php';
            break; 
            case 'result':
                require_once 'search.php';
            break; 
            default:
                require_once 'main.php';
            break;
        }
    ?>
</div>