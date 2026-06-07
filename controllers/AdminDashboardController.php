<?php

namespace controllers;

use core\ApiClient;

class AdminDashboardController
{
    public function index(){
        if (!isset($_SESSION['user_id'])) {
            header('Location: /kim/login?error=' . urlencode('You need to be logged in to access this page!'));
            exit;
        }

        $userId = $_SESSION['user_id'];

        $apiUrl = "http://localhost/kim/api/admin-dashboard";
        $apiData = ApiClient::get($apiUrl);
        if(isset($apiData->error)){
            header('Location: /kim/profile?error='. urlencode($apiData->error));
            exit;
        }

        if($apiData){
            $user = $apiData->user;

            $trainersCount = $apiData->trainersCount;

            $usersStats = (array)$apiData->userStats;

            $plannedAndOngoingBookings = (array)$apiData->plannedAndOngoingBookings;

            $monthlyRevenueStats = (array)$apiData->monthlyRevenueStats;

            $subscriptionTypesStats = (array)$apiData->subscriptionTypesStats;

            $unreadNotifications = (array)$apiData->unreadNotifications;

            $sessionParticipantsMap = (array)$apiData->sessionParticipantsMap;
        } else {
            header('Location: /kim/404?error=' . urlencode('There was a problem retrieving your data!'));
            exit;
        }



        require 'views/dashboards/admin_dashboard.php';
    }
}