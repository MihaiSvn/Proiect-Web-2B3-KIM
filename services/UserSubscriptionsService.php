<?php

namespace services;

use models\UserSubscription;
class UserSubscriptionsService
{
    public function getActiveSubscriptionsByUserId($userId){
        return UserSubscription::findActiveSubscriptionsByUserId($userId);
    }

    public function getSubscriptionByIdAndUser($userSubscriptionId, $userId){
        return UserSubscription::findSubscriptionByIdAndUser($userSubscriptionId, $userId);
    }

    public function suspend($userSubscriptionId, $userId, $daysToSuspend){
        if($daysToSuspend < 1){
            throw new \Exception('Invalid number of days to suspend');
        }

        $subscription = $this->getSubscriptionByIdAndUser($userSubscriptionId, $userId);

        if(!$subscription){
            throw new \Exception('Subscription not found or access denied');
        }

        if($daysToSuspend > $subscription->suspending_days_left){
            throw new \Exception('You requested to suspend more days than you have left.');
        }

        $succes = UserSubscription::applySuspension($userSubscriptionId, $daysToSuspend);

        if(!$succes){
            throw new \Exception("Unable to apply suspension");
        }

        return true;
    }

    public function checkAndReactivateSuspensions($userId) {
        return UserSubscription::reactivateExpiredSuspensions($userId);
    }
}