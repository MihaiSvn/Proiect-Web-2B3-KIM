<?php

namespace controllers;

use services\RoomService;
use services\SessionService;
use services\TrainerService;
use services\UserService;

class SessionsPageController
{
    private $sessionService;
    private $userService;
    private $trainerService;
    private $roomService;

    public function __construct(SessionService $sessionService, UserService $userService, TrainerService $trainerService, RoomService $roomService)
    {
        $this->sessionService = $sessionService;
        $this->userService = $userService;
        $this->trainerService = $trainerService;
        $this->roomService = $roomService;
    }

    public function index(){
        if (!isset($_SESSION['user_id'])) {
            header('Location: /kim/login?error=' . urlencode('You need to be logged in to access this page!'));
            exit;
        }

        // daca URL-ul are '?date=2026-06-08' o luam pe aceea. daca nu luam ziua de azi
        $requestedDate = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
        $dateObj = new \DateTime($requestedDate);

        // ne da inapoi data la lunea aceasta
        $startOfWeek = (clone $dateObj)->modify('Monday this week');
        //ne da duminica
        $endOfWeek   = (clone $dateObj)->modify('Sunday this week');

        $startDateStr = $startOfWeek->format('Y-m-d 00:00:00');
        $endDateStr   = $endOfWeek->format('Y-m-d 23:59:59');

        try{
            $rawSessions = $this->sessionService->getSessionsBetweenDates($startDateStr, $endDateStr);

            //cheia va fi id sesiune
            $sessionParticipantsMap = [];

            foreach ($rawSessions as $session) {
                $usersForThisSession = $this->sessionService->getAllUsersBookedBySessionId($session->session_id);

                $sessionParticipantsMap[$session->session_id] = $usersForThisSession;
            }

        } catch (\Exception $ex){
            header('Location: /kim/sessions?error=' . urlencode($ex->getMessage()));
            exit;
        }


        $weeklySchedule = $this->sessionService->getWeeklySessionsGrouped($rawSessions, $startOfWeek);



        $prevWeek = (clone $startOfWeek)->modify('-1 week')->format('Y-m-d');
        $nextWeek = (clone $startOfWeek)->modify('+1 week')->format('Y-m-d');


        $activeSubscriptionData = $this->userService->getActiveSubscriptionsByUserId($_SESSION['user_id']);
        $allTrainers = $this->trainerService->getAllTrainers();

        $trainerData = $this->trainerService->getTrainerByUserId($_SESSION['user_id']); // va fi false daca nu e trainer

        $availableRooms = [];
        if($trainerData){
            $availableRooms = $this->roomService->getAllActiveRoomsByType($trainerData->specialization);
        }

        require 'views/sessions.php';

    }
}