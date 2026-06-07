<?php

namespace controllers\api_controllers;

use services\SubscriptionService;

class MembershipApiController
{
    public function getMemberships()
    {
        header('Content-Type: application/json');

        $data= ['fitness' => $this->subscriptionService->getByType('fitness'),
                         'strength' => $this->subscriptionService->getByType('strength'),
                         'physiotherapy' => $this->subscriptionService->getByType('physiotherapy'),
                         'fullAccess' => $this->subscriptionService->getByType('all')
        ];

        echo json_encode($data);
    }
}