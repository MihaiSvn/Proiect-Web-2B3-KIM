<?php

namespace controllers;

use services\UserService;
use services\UserSubscriptionsService;
use services\SessionService;
use services\NotificationService;
class DashboardController
{
    private $userService;
    private $userSubscriptionsService;

    private $sessionService;

    private $notificationService;

    public function __construct($userService, $userSubscriptionsService, $sessionService, $notificationService)
    {
        $this->userService = $userService;
        $this->userSubscriptionsService = $userSubscriptionsService;
        $this->sessionService = $sessionService;
        $this->notificationService = $notificationService;
    }

    public function index()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /kim/login?error=' . urlencode('You need to be logged in to access this page!'));
            exit;
        }

        $userId = $_SESSION['user_id'];

        $role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : 'member';

        $user = $this->userService->getUserById($userId);



        if (!$user) {
            session_destroy();
            header('Location: /kim/login?error=' . urlencode('User not found!'));
            exit;
        }

        if ($role === 'admin') {
            require 'views/dashboards/admin_dashboard.php';
        } elseif ($role === 'trainer') {
            require 'views/dashboards/trainer_dashboard.php';
        } else {
            $this->userSubscriptionsService->checkAndReactivateSuspensions($userId);

            $activeSubscriptions = $this->userSubscriptionsService->getActiveSubscriptionsByUserId($userId);

            $plannedAndOngoingBookings = $this->sessionService->getAllPlannedAndOngoingBookingsByUserId($userId);

            $unreadNotifications = $this->notificationService->getUnreadUserNotifications($userId);
            require 'views/dashboards/member_dashboard.php';
        }
    }

}