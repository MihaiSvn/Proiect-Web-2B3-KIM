<?php

namespace controllers;

use core\ApiClient;

class ReportsPageController
{
    public function index()
    {
        if (!isset($_SESSION['user_id'])) {

            header(
                'Location: /kim/login?error=' .
                urlencode(
                    'You need to be logged in!'
                )
            );

            exit;
        }

        $apiUrl =
            "http://localhost/kim/api/reports";

        $apiData =
            ApiClient::get($apiUrl);

        if(isset($apiData->error)){

            header(
                'Location: /kim/dashboard?error=' .
                urlencode($apiData->error)
            );

            exit;
        }

        if($apiData){

            $activeUsers = $apiData->activeUsers;
            $todaySessions = $apiData->todaySessions;
            $weekSessions = $apiData->weekSessions;
            $monthSessions = $apiData->monthSessions;
            $sessionsPerDay = (array)$apiData->sessionsPerDay;
            $topTrainers = (array)$apiData->topTrainers;
            $subscriptionStats = (array)$apiData->subscriptionStats;

        } else {

            header(
                'Location: /kim/404?error=' .
                urlencode(
                    'There was a problem retrieving reports!'
                )
            );

            exit;
        }

        require 'views/reports_view.php';
    }
}