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
  

    public function checkAndReactivateSuspensionsByUserId($userId) {
        return UserSubscription::reactivateExpiredSuspensionsByUserId($userId);
    }

    public function checkAndReactivateAllSuspensions(){
        return UserSubscription::reactivateAllExpiredSuspensions();
    }

    public function checkAndExpireMemberships($userId) {
        return UserSubscription::checkAndExpireSubscriptionsByUserId($userId);
    }

    public function checkAndExpireAllMemberships() {
        return UserSubscription::checkAndExpireAllSubscriptions();
    }
    public function updateMembershipStatusesByUserId($userId)
    {
        $this->checkAndReactivateSuspensionsByUserId($userId);
        $this->checkAndExpireMemberships($userId);
    }

    public function updateAllMembershipStatuses(){
        $this->checkAndExpireAllMemberships();
        $this->checkAndReactivateAllSuspensions();
    }

    public function getMonthlyRevenueStats() {
        $currentRevenue = UserSubscription::getCurrentPeriodRevenue();
        $previousRevenue = UserSubscription::getPreviousPeriodRevenue();

        if ($previousRevenue == 0) {
            $trend = $currentRevenue > 0 ? 100 : 0;
        } else {
            $trend = round((($currentRevenue - $previousRevenue) / $previousRevenue) * 100);
        }

        // formatam ( 24800 devine 24.8K)
        if ($currentRevenue >= 1000) {
            // impart la 1000 si pastrez o singura zecimala
            $formattedValue = '€' . number_format($currentRevenue / 1000, 1) . 'K';
        } else {
            // afisez normal daca nu e asa mare
            $formattedValue = '€' . number_format($currentRevenue, 0);
        }

        // returnez si trend si value pentru stat card
        return [
            'displayValue' => $formattedValue,
            'trend' => $trend
        ];
    }

    public function getSubscriptionTypeStats() {
        return UserSubscription::getActiveSubscriptionsCountByType();
    }

    public function purchase($userId, $subscriptionId)
    {
        $success = UserSubscription::create(
            $userId,
            $subscriptionId
        );

        if (!$success) {
            throw new \Exception(
                'Unable to purchase subscription!'
            );
        }

        return true;
    }
  
    public function getAllSubscriptionsByUserId($userId){
        return UserSubscription::findAllSubscriptionsByUserId($userId);
    }

    public function getGroupedSubscriptionsByUserId($userId){
        $allSubscriptions = $this->getAllSubscriptionsByUserId($userId);

        $grouped = [
            'active' => [],
            'suspended' => [],
            'expired' => []
        ];

        foreach ($allSubscriptions as $subscription) {
            $status = strtolower($subscription->status);

            if(array_key_exists($status, $grouped)){
                array_push($grouped[$status], $subscription);
            }
        }

        return $grouped;
    }
}