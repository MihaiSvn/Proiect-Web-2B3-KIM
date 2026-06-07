<?php

namespace controllers\profile_page_tabs_controllers;


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


        //daca vreodata dau session start in api tre sa am asta
        session_write_close();
        //daca n am asta, da mereu sesson temporaly unavailable in loc sa mi zica ce e gresit
        $context = stream_context_create([
            'http' => ['ignore_errors' => true]
        ]);

        $json_response = file_get_contents($apiUrl, false, $context);

        if(!$json_response){
            header('Location: /kim/profile?error=' .urlencode('Service temporarily unavailable'));
            exit;
        }

        $apiData = json_decode($json_response);
        if(isset($apiData->error)){
            header('Location: /kim/profile?error=' .urlencode($apiData->error));
            exit;
        }

        $user = $apiData->user;
        $allNotifications = (array)$apiData->allNotifications;
        $unreadNotifications = (array)$apiData->unreadNotifications;

        $headerMainTitle = 'Notifications';
        $headerMainSubtitle = 'Stay updated with your latest alerts and account activity.';

        $pagePath = 'components/profile_page/profile_main-notifications.php';

        require 'views/profile.php';

    }
}