<?php

namespace api\membership;

use services\UserService;
use services\UserSubscriptionsService;

class UserSubscriptionApiController
{

    public function suspend(){
        $userId = $_SESSION['user_id'];

        $data = json_decode(file_get_contents("php://input"),true);

        $suspend_days = isset($data['suspend_days']) ? $data['suspend_days'] : null;
        $subscription_id = isset($data['subscription_id']) ? $data['subscription_id'] : null;

        if(!$suspend_days){
            http_response_code(400);
            echo json_encode(['status'=>'error', 'message'=>'Invalid days']);
            exit;
        }

        $suspend_days = (int) $suspend_days;

        if(!$subscription_id){
            http_response_code(400);
            echo json_encode(['status'=>'error', 'message'=>'Invalid subscription_id']);
            exit;
        }

        $subscription_id = (int) $subscription_id;

        $userSubscriptionService = new UserSubscriptionsService();
        try{
            $userSubscriptionService->suspend($subscription_id, $_SESSION['user_id'], $suspend_days);
            http_response_code(200);
            echo json_encode(['status'=>'success', 'message'=>'Membership successfully suspended']);
            exit;
        } catch (\Exception $e){
            http_response_code(400);
            echo json_encode(['status'=>'error', 'message'=>$e->getMessage()]);
            exit;
        }
    }
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

        $data = json_decode(file_get_contents('php://input'), true);
        $subscriptionId = isset($data['subscription_id']) ? $data['subscription_id'] : null;
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id'])) {
            http_response_code(400);
            echo json_encode(['status' => 'error','message' => 'You need to be logged in!']);
            return;
        }

        if (!$subscriptionId) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Invalid subscription!']);
            return;
        }

        $userId = $_SESSION['user_id'];


        try {

            $userSubscriptionService = new UserSubscriptionsService();
            $userSubscriptionService->purchase($userId, $subscriptionId);

            http_response_code(200);
            echo json_encode(['status' => 'success', 'message' => 'Membership purchased successfully!']);

        } catch (\Exception $ex) {

            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => $ex->getMessage()]);
        }
    }

}
