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

    public function purchase()
    {

        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id'])) {

            echo json_encode(['status' => 'error','message' => 'You need to be logged in!']);
            return;
        }

        if (!isset($_POST['subscription_id'])) {

            echo json_encode(['status' => 'error', 'message' => 'Invalid subscription!']);
            return;
        }

        $userId = $_SESSION['user_id'];

        $subscriptionId = (int)$_POST['subscription_id'];

        try {

            $userSubscriptionService = new UserSubscriptionsService();
            $userSubscriptionService->purchase($userId, $subscriptionId);

            echo json_encode(['status' => 'success', 'message' => 'Membership purchased successfully!']);

        } catch (\Exception $ex) {

            echo json_encode(['status' => 'error', 'message' => $ex->getMessage()]);
        }
    }

}