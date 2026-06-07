<?php

namespace api\dashboard;

use services\NotificationService;
use services\SessionService;
use services\TrainerService;
use services\UserService;

class TrainerDashboardApiController
{
    public function getData(){
        $userId = $_SESSION['user_id'];

        $userService = new UserService();
        $trainerService = new TrainerService();
        $sessionService = new SessionService();
        $notifService = new NotificationService();

        $user = $userService->getUserById($userId);
        $trainer = $trainerService->getTrainerByUserId($userId);
        $trainerId = $trainer->id;
        $plannedAndOngoingBookings = $sessionService->getAllPlannedAndOngoingSessionsByTrainerId($trainerId);

        $allBookings = $sessionService->getAllSessionsByTrainerId($trainerId);

        $unreadNotifications = $notifService->getUnreadUserNotifications($userId);
        foreach($unreadNotifications as $notification){
            $notification->time_ago = $notifService->getTimeAgo($notification->created_at);
        }

        $sessionParticipantsMap = [];

        // map care are id sesiune ca key si lista useri ca value
        foreach ($plannedAndOngoingBookings as $session) {
            $usersForThisSession = $sessionService->getAllUsersBookedBySessionId($session->session_id);

            $sessionParticipantsMap[$session->session_id] = $usersForThisSession;
        }
        http_response_code(200);
        echo json_encode(
            [
                'user' => $user,
                'trainer' => $trainer,
                'trainerId' => $trainerId,
                'plannedAndOngoingBookings' => $plannedAndOngoingBookings,
                'allBookings' => $allBookings,
                'unreadNotifications' => $unreadNotifications,
                'sessionParticipantsMap' => $sessionParticipantsMap
            ]
        );
    }
}