<?php

namespace controllers\profile_page_tabs_controllers;

use core\ApiClient;
use services\UserService;

class ProfileSettingsController
{

    public function index(){
        if(!isset($_SESSION['user_id'])){
            session_destroy();
            header("location:/kim/login?error=You are not logged in");
            exit;
        }

        $userId = $_SESSION['user_id'];

        $apiUrl = "http://localhost/kim/api/user-data?user_id=" . $userId;

        //apelam api ul
        $apiData = ApiClient::get($apiUrl);

        if($apiData){
            $user = $apiData->user;
        } else {
            header('Location: /kim/views/404.php?error=' . urlencode('There was a problem retrieving your data!'));
            exit;
        }
        if(isset($apiData->error)){
            header('Location: /kim/profile?error='. urlencode($apiData->error));
            exit;
        }


        $headerMainTitle = 'Settings';
        $headerMainSubtitle = 'View relevant account settings';

        $pagePath = 'components/profile_page/profile_main-settings.php';

        require 'views/profile.php';
    }
}