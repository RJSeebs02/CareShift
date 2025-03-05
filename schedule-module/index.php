<?php
$admin_id = $admin->get_id_by_username($_SESSION['adm_username']);
$access_id = $admin->get_access_id($admin_id);
$scheduler_department_id = $admin->get_department_id($admin_id);

if ($access_id == 3) { 
    $nurses = $nurse->list_nurses_by_department($scheduler_department_id);
} else {
    $nurses = $nurse->list_nurses(); 
}
?>

<div class="content_wrapper">
	<?php
        /*Switch case for the subpage of the Schedule Page */
        switch($subpage){
            case 'main':
                require_once 'main.php';
            break;
            case 'calendar':
                require_once 'calendar.php';
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
			case 'profile':
                require_once 'profile.php';
            break; 
            default:
                require_once 'main.php';
            break;
        }
    ?>
</div>