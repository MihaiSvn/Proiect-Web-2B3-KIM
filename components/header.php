<?php
if (isset($_SESSION['user_id'])) {
    if (isset($_SESSION['user_role'])) {
        switch ($_SESSION['user_role']) {
            case 'admin':
                include 'components/headers/admin_header.php';
                break;
            case 'member':
                include 'components/headers/member_header.php';
                break;
            case 'trainer':
                include 'components/headers/trainer_header.php';
                break;
        }
    } else {
        include 'components/headers/unauth_header.php';
    }
}
?>