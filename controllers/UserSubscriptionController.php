<?php

namespace controllers;

use services\UserSubscriptionsService;
class UserSubscriptionController
{
    private $userSubscriptionService;

    public function __construct(UserSubscriptionsService $userSubscriptionsService){
        $this->userSubscriptionService = $userSubscriptionsService;
    }

    public function suspend(){
        if (!isset($_SESSION['user_id'])) {
            header('Location: /kim/login?error=' . urlencode('You need to be logged in to access this page!'));
            exit;
        }

        $userId = $_SESSION['user_id'];

        if(!isset($_POST['suspend_days'])){
            header('Location: /kim/profile?error=' . urlencode('Error at suspending subscription!'));
            exit;
        }

        $suspend_days = (int)$_POST['suspend_days'];

        if(!isset($_GET['id'])){
            header('Location: /kim/profile?error=' . urlencode('Error at suspending subscription!'));
            exit;
        }

        $userSubscriptionId = (int)$_GET['id'];

        try{
            $this->userSubscriptionService->suspend($userSubscriptionId, $userId, $suspend_days);

            header('Location: /kim/profile?success=' . urlencode('Suspended successfully!'));
            exit;
        } catch (\Exception $ex){
            $error_message = $ex->getMessage();

            header('Location: /kim/profile?error=' . urlencode($error_message));
            exit;
        }
    }

    public function purchase()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /kim/login?error=' . urlencode('You need to be logged in!'));
            exit;
        }

        if (!isset($_POST['subscription_id'])) {
            header('Location: /kim/membership?error=' . urlencode('Invalid subscription!'));
            exit;
        }

        $userId = $_SESSION['user_id'];
        $subscriptionId = (int)$_POST['subscription_id'];

        try {

            $this->userSubscriptionService->purchase(
                $userId,
                $subscriptionId
            );

            header('Location: /kim/dashboard?success=' . urlencode('Membership purchased successfully!'));
            exit;

        } catch (\Exception $ex) {

            header(
                'Location: /kim/memberships?error=' .
                urlencode($ex->getMessage())
            );
            exit;
        }
    }


}