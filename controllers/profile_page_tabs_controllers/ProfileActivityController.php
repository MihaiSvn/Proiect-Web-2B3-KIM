<?php

namespace controllers\profile_page_tabs_controllers;

use core\ApiClient;

class ProfileActivityController
{
    public function index(){
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
            session_destroy();
            header("location: /kim/login?error=" . urlencode("You are not logged in or session is invalid"));
            exit;
        }

        $userId = $_SESSION['user_id'];
        $userRole = $_SESSION['user_role'];

        $apiUrl = "http://localhost/kim/api/profile_activity?user_id={$userId}&user_role={$userRole}";

        $apiData = ApiClient::get($apiUrl);

        if(isset($apiData->error)){
            header("location: /kim/profile?error=" . urlencode($apiData->error));
            exit;
        }

        if($apiData){
            $user=$apiData->user;
            $allSessions=$apiData->allSessions;
            $groupedSessions=(array)$apiData->groupedSessions;
            $sessionParticipantsMap=(array)$apiData->sessionParticipantsMap;
        } else {
            header('Location: /kim/views/404.php?error=' . urlencode('There was a problem retrieving your data!'));
            exit;
        }


        $headerMainTitle = 'Activity History';
        $headerMainSubtitle = 'Your past sessions across all KIM facilities.';


        $pagePath = 'components/profile_page/profile_main-activity-history.php';

        require 'views/profile.php';
    }
}