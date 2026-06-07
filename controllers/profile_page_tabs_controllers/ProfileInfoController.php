<?php

namespace controllers\profile_page_tabs_controllers;

use core\ApiClient;

class ProfileInfoController
{
    public function index()
    {
        if(!isset($_SESSION['user_id'])){
            header('Location: /kim/login?error=You must be logged in to view this page');
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


        $headerMainTitle = 'Personal Information';
        $headerMainSubtitle = 'Update your personal details and contact information.';

        $pagePath = 'components/profile_page/profile_main-personal-info.php';

        require 'views/profile.php';
    }
}