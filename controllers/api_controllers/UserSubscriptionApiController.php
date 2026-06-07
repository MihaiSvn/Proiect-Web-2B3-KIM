<?php

namespace controllers\api_controllers;

use services\UserSubscriptionsService;

class UserSubscriptionApiController
{
    private $userSubscriptionService;

    public function __construct(
        UserSubscriptionsService $userSubscriptionService
    ){
        $this->userSubscriptionService =
            $userSubscriptionService;
    }

    public function purchase()
    {
        header(
            'Content-Type: application/json'
        );

        if (!isset($_SESSION['user_id'])) {

            echo json_encode([
                'status' => 'error',
                'message' =>
                    'You need to be logged in!'
            ]);

            return;
        }

        if (!isset($_POST['subscription_id'])) {

            echo json_encode([
                'status' => 'error',
                'message' =>
                    'Invalid subscription!'
            ]);

            return;
        }

        $userId =
            $_SESSION['user_id'];

        $subscriptionId =
            (int)$_POST['subscription_id'];

        try {

            $this
                ->userSubscriptionService
                ->purchase(
                    $userId,
                    $subscriptionId
                );

            echo json_encode([
                'status' => 'success',
                'message' =>
                    'Membership purchased successfully!'
            ]);

        } catch (\Exception $ex) {

            echo json_encode([
                'status' => 'error',
                'message' =>
                    $ex->getMessage()
            ]);
        }
    }
}