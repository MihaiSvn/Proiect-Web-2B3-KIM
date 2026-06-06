<?php

namespace controllers\profile_page_tabs_controllers;

use services\UserService;

class ProfileInfoController
{
    private $userService;

    public function __construct(UserService $userService){
        $this->userService = $userService;
    }

    public function index()
    {
        if(!isset($_SESSION['user_id'])){
            header('Location: /kim/login?error=You must be logged in to view this page');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $user = $this->userService->getUserById($userId);

        if (!$user) {
            session_destroy();
            header('Location: /kim/login?error=' . urlencode('User not found!'));
            exit;
        }

        $headerMainTitle = 'Personal Information';
        $headerMainSubtitle = 'Update your personal details and contact information.';

        $pagePath = 'components/profile_page/profile_main-personal-info.php';

        require 'views/profile.php';
    }
}