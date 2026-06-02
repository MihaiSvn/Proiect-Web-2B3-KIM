<?php

namespace controllers;

class AdminDashboardController
{
    private $userService;

    private $sessionService;

    private $trainerService;

    private $userSubscriptionService;

    private $notificationService;

    public function __construct($userService, $sessionService, $trainerService, $userSubscriptionService, $notificationService) {
        $this->userService = $userService;
        $this->sessionService = $sessionService;
        $this->trainerService = $trainerService;
        $this->userSubscriptionService = $userSubscriptionService;
        $this->notificationService = $notificationService;
    }

    public function index(){
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

        $trainersCount = $this->trainerService->getAllTrainersCount();

        $usersStats = $this->userService->getActiveMembersStats();

        $plannedAndOngoingBookings = $this->sessionService->getAllPlannedAndOngoingSessions();

        $monthlyRevenueStats = $this->userSubscriptionService->getMonthlyRevenueStats();

        $subscriptionTypesStats = $this->userSubscriptionService->getSubscriptionTypeStats();

        $unreadNotifications = $this->notificationService->getUnreadUserNotifications($userId);

        require 'views/dashboards/admin_dashboard.php';
    }
}