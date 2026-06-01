<?php

namespace controllers;

class TrainerDashboardController
{
    private $userService;

    private $sessionService;

    private $notificationService;

    private $trainerService;

    public function __construct($userService, $sessionService, $notificationService, $trainerService)
    {
        $this->userService = $userService;
        $this->sessionService = $sessionService;
        $this->notificationService = $notificationService;
        $this->trainerService = $trainerService;
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



        $trainer = $this->trainerService->getTrainerByUserId($userId);

        if(!$trainer){
            session_destroy();
            header('Location: /kim/login?error=' . urlencode('Trainer not found!'));
            exit;
        }

        $trainerId = $trainer->id;

        // all planned and ongoing sessions

        $plannedAndOngoingBookings = $this->sessionService->getAllPlannedAndOngoingSessionsByTrainerId($trainerId);

        // all sessions except canceled

        $allBookings = $this->sessionService->getAllSessionsByTrainerId($trainerId);

        $unreadNotifications = $this->notificationService->getUnreadUserNotifications($userId);

        require 'views/dashboards/trainer_dashboard.php';

    }
}