<?php

namespace controllers\profile_page_tabs_controllers;

use services\UserService;
use services\UserSubscriptionsService;

class ProfileMembershipHistoryController
{
    private $userSubscriptionService;
    private $userService;

    public function __construct(UserSubscriptionsService $userSubscriptionService, UserService  $userService){
        $this->userSubscriptionService = $userSubscriptionService;
        $this->userService = $userService;
    }

    public function index(){
        if(!isset($_SESSION['user_id'])){
            session_destroy();
            header('Location: /kim/login?error=You are not logged in');
            exit;
        }

        $user = $this->userService->getUserById($_SESSION['user_id']);

        if(!$user){
            session_destroy();
            header('Location: /kim/login?error=You are not logged in');
            exit;
        }

        $groupedSubs = $this->userSubscriptionService->getGroupedSubscriptionsByUserId($_SESSION['user_id']);

        $headerMainTitle = 'Membership History';
        $headerMainSubtitle = 'A record of all your KIM memberships.';

        $pagePath = 'components/profile_page/profile_main-membership-history.php';

        require 'views/profile.php';
    }

}