<?php

namespace api\membership;

use services\UserService;
use services\UserSubscriptionsService;

class UserSubscriptionApiController
{
    public function getHistory()
    {
        $userId = isset($_GET['user_id']) ? $_GET['user_id'] : $_SESSION['user_id'];

        if ($_SESSION['user_role'] !== 'admin' && $userId != $_SESSION['user_id']) {
            http_response_code(403);
            echo json_encode(['error' => 'Unauthorized access.']);
            exit;
        }

        $userService = new UserService();
        $user= $userService->getUserById($userId);
        if(!$user){
            http_response_code(404);
            echo json_encode(['error' => 'User not found.']);
            exit;
        }

        $subService = new UserSubscriptionsService();
        $history = $subService->getGroupedSubscriptionsByUserId($userId);

        http_response_code(200);
        echo json_encode(['history' => $history,
            'user' => $user]);
    }
}