<?php

namespace controllers;

use services\UserService;
use services\UserSubscriptionsService;
class ProfileController
{
    private $userService;
    private $userSubscriptionsService;

    public function __construct($userService, $userSubscriptionsService)
    {
        $this->userService = $userService;
        $this->userSubscriptionsService = $userSubscriptionsService;
    }

    public function index()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /kim/login?error=' . urlencode('You need to be logged in to access this page!'));
            exit;
        }

        $userId = $_SESSION['user_id'];

        $user = $this->userService->getUserById($userId);
        $activeSubscriptions = $this->userSubscriptionsService->getActiveSubscriptionsByUserId($userId);

        if (!$user) {
            session_destroy();
            header('Location: /kim/login?error=' . urlencode('User not found!'));
            exit;
        }

        require 'views/profile.php';
    }
}