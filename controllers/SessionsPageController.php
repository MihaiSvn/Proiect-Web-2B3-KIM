<?php

namespace controllers;

use core\ApiClient;
use services\RoomService;
use services\SessionService;
use services\TrainerService;
use services\UserService;

class SessionsPageController
{
    public function index(){
        if (!isset($_SESSION['user_id'])) {
            header('Location: /kim/login?error=' . urlencode('You need to be logged in to access this page!'));
            exit;
        }

        $dateParam = isset($_GET['date']) ? "?date=" . $_GET['date'] : "";
        $apiUrl = "http://localhost/kim/api/sessions-data" . $dateParam;

        $apiData = ApiClient::get($apiUrl);

        if($apiData){
            $requestedDate = $apiData->requestedDate;
            $startOfWeek = new \DateTime($apiData->startOfWeek);
            $endOfWeek = new \DateTime($apiData->endOfWeek);
            $prevWeek = $apiData->prevWeek;
            $nextWeek = $apiData->nextWeek;
            $trainerData = $apiData->trainerData;
            $allTrainers = (array)$apiData->allTrainers;
            $availableRooms = (array)$apiData->availableRooms;

            $weeklyScheduleRaw = (array)$apiData->weeklySchedule;
            $weeklySchedule = [];

            foreach ($weeklyScheduleRaw as $date => $dayData) {
                $weeklySchedule[$date] = [
                    'day_name'  => $dayData->day_name,
                    'day_short' => $dayData->day_short,
                    'sessions'  => $dayData->sessions //trb facut asa ca sa lasam sesiunea ca obiect ca altfel s-ar transforma in array
                ];
            }
            $sessionParticipantsMap = (array)($apiData->sessionParticipantsMap);
            $activeSubscriptionData = json_decode(json_encode($apiData->activeSubscriptionData), true);
        } else {
            header('Location: /kim/404?error=' . urlencode('There was a problem retrieving your data!'));
            exit;
        }

        require 'views/sessions.php';

    }
}