<?php

namespace api\membership;
use services\SubscriptionService;

class MembershipApiController
{
    public function getMemberships()
    {
        header('Content-Type: application/json');
        $subscriptionService = new SubscriptionService();
        $data = [
            'fitness' =>
                $subscriptionService->getByType('fitness'),

            'strength' =>
                $subscriptionService->getByType('strength'),

            'physiotherapy' =>
                $subscriptionService->getByType('physiotherapy'),

            'fullAccess' =>
                $subscriptionService->getByType('all')
        ];

        echo json_encode($data);
    }
}