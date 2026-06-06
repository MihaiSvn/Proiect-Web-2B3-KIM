<?php

namespace controllers\profile_page_tabs_controllers;

use services\SessionService;
use services\TrainerService;
use services\UserService;

class ProfileActivityController
{
    private $sessionsService;

    private $userService;
    private $trainerService;
    public function __construct(SessionService $sessionsService, UserService $userService, TrainerService $trainerService){
        $this->sessionsService = $sessionsService;
        $this->userService = $userService;
        $this->trainerService = $trainerService;
    }

    public function index(){
        if(!isset($_SESSION['user_id'])){
            session_destroy();
            header("location: /kim/login?error=You are not logged in");
            exit;
        }

        $user = $this->userService->getUserById($_SESSION['user_id']);
        if(!$user){
            session_destroy();
            header("location: /kim/login?error=You are not logged in");
            exit;
        }


        if(!isset($_SESSION['user_role'])){
            session_destroy();
            header("location: /kim/login?error=There was a problem with your session");
            exit;
        }

        $userRole = $_SESSION['user_role'];

        $allSessions = [];
        if($userRole === 'member'){
            $allSessions = $this->sessionsService->getAllBookingsByUserId($_SESSION['user_id']);
        } else if($userRole === 'trainer'){
            $trainer = $this->trainerService->getTrainerByUserId($_SESSION['user_id']);
            if(!$trainer){
                session_destroy();
                header("location: /kim/login?error=No trainer with id found");
                exit;
            }
            $trainerId = $trainer->id;
            $allSessions = $this->sessionsService->getAllSessionsByTrainerId($trainerId, true);
        } else if($userRole === 'admin'){
            $allSessions = $this->sessionsService->getAllSessions(true);
        }

        $groupedSessions = [
            'ongoing' => [],
            'planned' => [],
            'completed' => [],
            'canceled' => []
        ];

        foreach($allSessions as $session){
            if(array_key_exists($session->status, $groupedSessions)){
                array_push($groupedSessions[$session->status], $session);
            }
        }

        $headerMainTitle = 'Activity History';
        $headerMainSubtitle = 'Your past sessions across all KIM facilities.';

        $sessionParticipantsMap = [];

        // map care are id sesiune ca key si lista useri ca value
        foreach ($allSessions as $session) {
            $usersForThisSession = $this->sessionsService->getAllUsersBookedBySessionId($session->session_id);

            $sessionParticipantsMap[$session->session_id] = $usersForThisSession;
        }

        $pagePath = 'components/profile_page/profile_main-activity-history.php';

        require 'views/profile.php';
    }
}