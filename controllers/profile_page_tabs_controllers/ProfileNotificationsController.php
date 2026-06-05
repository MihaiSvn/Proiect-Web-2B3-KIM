<?php

namespace controllers\profile_page_tabs_controllers;

use services\NotificationService;
use services\UserService;

class ProfileNotificationsController
{
    private $notificationService;
    private $userService;

    public function __construct(NotificationService $notificationService, UserService $userService){
        $this->notificationService = $notificationService;
        $this->userService = $userService;
    }

    public function index(){
        if(!isset($_SESSION['user_id'])){
            session_destroy();
            header('Location: /kim/login?error=You are not logged in');
            exit;
        }

        $user = $this->userService->getUserById($_SESSION['user_id']);
        if(!$user){
            session_destroy();
            header('Location: /kim/login?error=User not found');
            exit;
        }

        $allNotifications = $this->notificationService->getUserNotifications($_SESSION['user_id']);
        $unreadNotifications = $this->notificationService->getUnreadUserNotifications($_SESSION['user_id']);

        $headerMainTitle = 'Notifications';
        $headerMainSubtitle = 'Stay updated with your latest alerts and account activity.';

        $pagePath = 'components/profile_page/profile_main-notifications.php';

        require 'views/profile.php';

    }
}