<<<<<<< HEAD
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
=======
<?php
$admin_id = $admin->get_id_by_username($_SESSION['adm_username']);
$access_id = $admin->get_access_id($admin_id);
$scheduler_department_id = $admin->get_department_id($admin_id);
?>
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
			case 'records':
                require_once 'records.php';
            break;
            default:
                require_once 'main.php';
            break;
        }
    ?>
>>>>>>> fa57ef9cad725f4de6f3f7079a21b6419102a7c6
</div>