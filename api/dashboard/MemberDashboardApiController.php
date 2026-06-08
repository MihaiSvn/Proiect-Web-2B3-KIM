<?php

namespace api\dashboard;

use services\UserService;
use services\UserSubscriptionsService;
use services\SessionService;
use services\NotificationService;

class MemberDashboardApiController
{
    public function getData()
    {
        $userId = $_SESSION['user_id'];

        $userService = new UserService();
        $subService = new UserSubscriptionsService();
        $sessionService = new SessionService();
        $notifService = new NotificationService();


        $unreadNotifications = $notifService->getUnreadUserNotifications($userId);
        foreach($unreadNotifications as $notification){
            $notification->time_ago = $notifService->getTimeAgo($notification->created_at);
        }
        http_response_code(200);
        echo json_encode([
            'user' => $userService->getUserById($userId),
            'activeSubscriptions' => $subService->getActiveSubscriptionsByUserId($userId),
            'plannedAndOngoingBookings' => $sessionService->getAllPlannedAndOngoingBookingsByUserId($userId),
            'allBookings' => $sessionService->getAllBookingsByUserId($userId),
            'unreadNotifications' => $unreadNotifications
        ]);
    }
}