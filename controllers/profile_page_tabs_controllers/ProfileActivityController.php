<?php

namespace controllers\profile_page_tabs_controllers;

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




        //daca vreodata dau session start in api tre sa am asta
        session_write_close();
        //daca n am asta, da mereu sesson temporaly unavailable in loc sa mi zica ce e gresit
        $context = stream_context_create([
            'http' => ['ignore_errors' => true]
        ]);

        $jsonResponse = file_get_contents($apiUrl, false, $context);

        if(!$jsonResponse){
            header("location: /kim/profile?error=" . urlencode("Service temporarily unavailable"));
            exit;
        }

        $apiData = json_decode($jsonResponse);

        if(isset($apiData->error)){
            header("location: /kim/profile?error=" . urlencode($apiData->error));
            exit;
        }

        $user=$apiData->user;
        $allSessions=$apiData->allSessions;
        $groupedSessions=(array)$apiData->groupedSessions;
        $sessionParticipantsMap=(array)$apiData->sessionParticipantsMap;

        $headerMainTitle = 'Activity History';
        $headerMainSubtitle = 'Your past sessions across all KIM facilities.';


        $pagePath = 'components/profile_page/profile_main-activity-history.php';

        require 'views/profile.php';
    }
}