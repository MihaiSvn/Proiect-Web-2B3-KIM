<?php

namespace controllers\profile_page_tabs_controllers;

use services\UserService;

class ProfileSettingsController
{
    private $userService;
    public function __construct(UserService $userService){
        $this->userService = $userService;
    }

    public function index(){
        if(!isset($_SESSION['user_id'])){
            session_destroy();
            header("location:/kim/login?error=You are not logged in");
            exit;
        }

        $user = $this->userService->getUserById($_SESSION['user_id']);
        if(!$user){
            session_destroy();
            header("location:/kim/login?error=You are not logged in");
            exit;
        }

        $headerMainTitle = 'Settings';
        $headerMainSubtitle = 'View relevant account settings';

        $pagePath = 'components/profile_page/profile_main-settings.php';

        require 'views/profile.php';
    }
}