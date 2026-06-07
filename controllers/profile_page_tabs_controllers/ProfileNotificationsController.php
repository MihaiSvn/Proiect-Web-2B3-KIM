<?php

namespace controllers\profile_page_tabs_controllers;


use core\ApiClient;

class ProfileNotificationsController
{

    public function index(){
        if(!isset($_SESSION['user_id'])){
            session_destroy();
            header('Location: /kim/login?error=You are not logged in');
            exit;
        }

        $userId = $_SESSION['user_id'];

        $apiUrl = 'http://localhost/kim/api/profile_notifications?user_id='.$userId;


        $apiData = ApiClient::get($apiUrl);

        if($apiData){
            $user = $apiData->user;
            $allNotifications = (array)$apiData->allNotifications;
            $unreadNotifications = (array)$apiData->unreadNotifications;
        } else {
            header('Location: /kim/views/404.php?error=' . urlencode('There was a problem retrieving your data!'));
            exit;
        }
        if(isset($apiData->error)){
            header('Location: /kim/profile?error=' .urlencode($apiData->error));
            exit;
        }



        $headerMainTitle = 'Notifications';
        $headerMainSubtitle = 'Stay updated with your latest alerts and account activity.';

        $pagePath = 'components/profile_page/profile_main-notifications.php';

        require 'views/profile.php';

    }
}