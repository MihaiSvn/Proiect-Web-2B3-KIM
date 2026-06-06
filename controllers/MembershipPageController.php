<?php

namespace controllers;
use services\SubscriptionService;

class MembershipPageController
{
    private $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService){
        $this->subscriptionService = $subscriptionService;
    }

    public function index(){
        $fitnessSubscriptions = $this->subscriptionService->getByType('fitness');
        $strengthSubscriptions = $this->subscriptionService->getByType('strength');
        $physiotherapySubscriptions = $this->subscriptionService->getByType('physiotherapy');
        $allSubscriptions = $this->subscriptionService->getByType('all');

        require 'views/membership.php';
    }
}