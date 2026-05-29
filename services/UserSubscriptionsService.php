<?php

namespace services;

use models\UserSubscription;
class UserSubscriptionsService
{
    public function getActiveSubscriptionsByUserId($userId){
        return UserSubscription::findActiveSubscriptionsByUserId($userId);
    }
}