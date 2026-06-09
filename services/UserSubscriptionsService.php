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
        $subscriptionsToExpire = UserSubscription::getSubscriptionsReadyToExpire($userId);

        if (empty($subscriptionsToExpire)) {
            return true;
        }

        $success = UserSubscription::checkAndExpireSubscriptionsByUserId($userId);

        if ($success) {
            $notificationService = new NotificationService();
            $userService = new UserService();
            $user = $userService->getUserById($userId);

            if ($user) {
                $notificationService->createNotification(
                    $userId,
                    '⚠️ Subscription Expired',
                    "Your membership has expired. Please renew it to continue booking classes."
                );

                $env = parse_ini_file(__DIR__ . "/../.env");
                $baseUrl = $env['APP_URL'] ?? 'http://localhost/kim';

                $subject = 'Your Membership Has Expired - KIM Fitness';
                $title   = 'Time to Renew!';
                $text    = "Hi <b>{$user->first_name}</b>,<br/><br/>We noticed that your KIM Fitness membership has just expired.<br/>Don't lose your momentum! Renew your subscription today to keep accessing our elite classes and expert trainers.";
                $btnText = 'Renew Membership';
                $btnUrl  = $baseUrl . '/memberships';

                MailService::sendEmail($user->email, $subject, $title, $text, $btnText, $btnUrl);
            }
        }
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

        $userService = new UserService();
        $user = $userService->getUserById($userId);
        $subscriptionService = new SubscriptionService();
        $subscription = $subscriptionService->getById($subscriptionId);
        if($user){

            $notificationService = new NotificationService();
            $notificationService->createNotification(
                $userId,
                '💳 Purchase Successful!',
                "Thank you for your purchase! Your membership is now active and ready to use."
            );


            $env = parse_ini_file(__DIR__ . "/../.env");
            $baseUrl = $env['APP_URL'] ?? 'http://localhost/kim';

            $subject = 'Your Subscription is Active - KIM Fitness';
            $title   = 'Purchase Successful!';

            $text    = "Hi <b>{$user->first_name}</b>,<br/><br/>
                    Thank you for your purchase! Your <b>{$subscription->name}</b> membership is now officially active.<br/><br/>
                    You have unlocked access to our elite facilities and expert trainers. It's time to crush your goals. Check out the schedule and book your next class!";

            $btnText = 'Book a Class';
            $btnUrl  = $baseUrl . '/sessions';

            MailService::sendEmail($user->email, $subject, $title, $text, $btnText, $btnUrl);
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