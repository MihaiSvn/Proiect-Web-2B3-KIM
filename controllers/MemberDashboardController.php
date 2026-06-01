<?php

namespace controllers;

use services\UserService;
use services\UserSubscriptionsService;
use services\SessionService;
use services\NotificationService;

class MemberDashboardController
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


        $user = $this->userService->getUserById($userId);


        if (!$user) {
            session_destroy();
            header('Location: /kim/login?error=' . urlencode('User not found!'));
            exit;
        }

        $this->userSubscriptionsService->checkAndReactivateSuspensions($userId); // UNDE REACTIV ASTEA SUSPENDATE !!!!

        $activeSubscriptions = $this->userSubscriptionsService->getActiveSubscriptionsByUserId($userId);

        $plannedAndOngoingBookings = $this->sessionService->getAllPlannedAndOngoingBookingsByUserId($userId);

        $allBookings = $this->sessionService->getAllBookingsByUserId($userId);

        $unreadNotifications = $this->notificationService->getUnreadUserNotifications($userId);
        require 'views/dashboards/member_dashboard.php';
    }

}