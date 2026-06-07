<?php

namespace controllers;

use core\ApiClient;

class MemberDashboardController
{
    public function index()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /kim/login?error=' . urlencode('You need to be logged in to access this page!'));
            exit;
        }

        $userId = $_SESSION['user_id'];

        $apiUrl = "http://localhost/kim/api/member-dashboard";


        $apiData = ApiClient::get($apiUrl);

        if($apiData){
            $user = $apiData->user;

            $activeSubscriptions = (array)$apiData->activeSubscriptions;

            $plannedAndOngoingBookings = (array)$apiData->plannedAndOngoingBookings;


            $allBookings = (array)$apiData->allBookings;

            $unreadNotifications = (array)$apiData->unreadNotifications;

        } else {
            header('Location: /kim/404?error=' . urlencode('There was a problem retrieving your data!'));
            exit;
        }


        if(isset($apiData->error)){
            header('Location: /kim/profile?error='. urlencode($apiData->error));
            exit;
        }




        require 'views/dashboards/member_dashboard.php';
    }

}