<?php

namespace api\dashboard;

use services\NotificationService;
use services\SessionService;
use services\TrainerService;
use services\UserService;
use services\UserSubscriptionsService;

class AdminDashboardApiController
{
    public function getData(){
        $userId = $_SESSION['user_id'];

        $userService = new UserService();
        $subService = new UserSubscriptionsService();
        $sessionService = new SessionService();
        $notifService = new NotificationService();
        $trainerService = new TrainerService();

        $user = $userService->getUserById($userId);

        $trainersCount = $trainerService->getAllTrainersCount();
        $userStats = $userService->getActiveMembersStats();
        $plannedAndOngoingBookings = $sessionService->getAllPlannedAndOngoingSessions();

        $monthlyRevenueStats = $subService->getMonthlyRevenueStats();

        $subscriptionTypesStats = $subService->getSubscriptionTypeStats();

        $unreadNotifications = $notifService->getUnreadUserNotifications($userId);

        $sessionParticipantsMap = [];

        // map care are id sesiune ca key si lista useri ca value
        foreach ($plannedAndOngoingBookings as $session) {
            $usersForThisSession = $sessionService->getAllUsersBookedBySessionId($session->session_id);

            $sessionParticipantsMap[$session->session_id] = $usersForThisSession;
        }

        http_response_code(200);
        echo json_encode([
            'user' => $user,
            'trainersCount' => $trainersCount,
            'userStats' => $userStats,
            'plannedAndOngoingBookings' => $plannedAndOngoingBookings,
            'monthlyRevenueStats' => $monthlyRevenueStats,
            'subscriptionTypesStats' => $subscriptionTypesStats,
            'unreadNotifications' => $unreadNotifications,
            'sessionParticipantsMap' => $sessionParticipantsMap
        ]);
    }
}