<?php

namespace middleware;

use services\UserSubscriptionsService;

class WebAuthMiddleware
{
    public static function checkAccess()
    {

        $requestedUserId = isset($_GET['user_id']) ? (int)$_GET['user_id'] : null;
        $loggedInUserId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
        $loggedInUserRole = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;

        if (!$loggedInUserId) {
            header('Location: /kim/login?error=You need to be logged in.');
            exit;
        }

        if($loggedInUserId){
            $userSubscriptionService = new UserSubscriptionsService();
            $userSubscriptionService->updateMembershipStatusesByUserId($loggedInUserId);
        }
    }
}