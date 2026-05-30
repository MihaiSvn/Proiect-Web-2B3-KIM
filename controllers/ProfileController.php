<?php

namespace controllers;

use services\UserService;
use services\UserSubscriptionsService;
use services\SessionService;
class ProfileController
{
    private $userService;
    private $userSubscriptionsService;

    private $sessionService;

    public function __construct($userService, $userSubscriptionsService, $sessionService)
    {
        $this->userService = $userService;
        $this->userSubscriptionsService = $userSubscriptionsService;
        $this->sessionService = $sessionService;
    }

    public function index()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /kim/login?error=' . urlencode('You need to be logged in to access this page!'));
            exit;
        }

        $userId = $_SESSION['user_id'];

        $user = $this->userService->getUserById($userId);

        $this->userSubscriptionsService->checkAndReactivateSuspensions($userId);

        $activeSubscriptions = $this->userSubscriptionsService->getActiveSubscriptionsByUserId($userId);

        $plannedAndOngoingBookings = $this->sessionService->getAllPlannedAndOngoingBookingsByUserId($userId);

        if (!$user) {
            session_destroy();
            header('Location: /kim/login?error=' . urlencode('User not found!'));
            exit;
        }

        require 'views/profile.php';
    }
}