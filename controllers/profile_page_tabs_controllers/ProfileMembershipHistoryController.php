<?php

namespace controllers\profile_page_tabs_controllers;

use core\ApiClient;

class ProfileMembershipHistoryController
{
    public function index(){
        if(!isset($_SESSION['user_id'])){
            session_destroy();
            header('Location: /kim/login?error=You are not logged in');
            exit;
        }

        $apiUrl = "http://localhost/kim/api/membership-history?user_id=" . $_SESSION['user_id'];
        $apiData = ApiClient::get($apiUrl);

        if($apiData){
            $groupedSubs = (array)$apiData->history;
            $user = $apiData->user;
        } else {
            header('Location: /kim/404?error=' . urlencode('There was a problem retrieving your data!'));
            exit;
        }



        $headerMainTitle = 'Membership History';
        $headerMainSubtitle = 'A record of all your KIM memberships.';

        $pagePath = 'components/profile_page/profile_main-membership-history.php';

        require 'views/profile.php';
    }

}