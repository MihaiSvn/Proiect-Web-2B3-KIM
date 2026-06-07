<?php

namespace api\sessions;

use services\RoomService;
use services\SessionService;
use services\TrainerService;
use services\UserService;

class SessionsApiController
{
    public function getSessionsData(){
        $requestedDate = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');


        try{
            $dateObj = new \DateTime($requestedDate);

            // ne da inapoi data la lunea aceasta
            $startOfWeek = (clone $dateObj)->modify('Monday this week');
            //ne da duminica
            $endOfWeek   = (clone $dateObj)->modify('Sunday this week');

            $startDateStr = $startOfWeek->format('Y-m-d 00:00:00');
            $endDateStr   = $endOfWeek->format('Y-m-d 23:59:59');

            $sessionService = new SessionService();
            $roomService = new RoomService();
            $userService = new UserService();
            $trainerService = new TrainerService();
            $rawSessions = $sessionService->getSessionsBetweenDates($startDateStr, $endDateStr);

            //cheia va fi id sesiune
            $sessionParticipantsMap = [];

            foreach ($rawSessions as $session) {
                $usersForThisSession = $sessionService->getAllUsersBookedBySessionId($session->session_id);

                $sessionParticipantsMap[$session->session_id] = $usersForThisSession;
            }

            $weeklySchedule = $sessionService->getWeeklySessionsGrouped($rawSessions, $startOfWeek);



            $prevWeek = (clone $startOfWeek)->modify('-1 week')->format('Y-m-d');
            $nextWeek = (clone $startOfWeek)->modify('+1 week')->format('Y-m-d');


            $activeSubscriptionData = $userService->getActiveSubscriptionsByUserId($_SESSION['user_id']);
            $allTrainers = $trainerService->getAllTrainers();

            $trainerData = $trainerService->getTrainerByUserId($_SESSION['user_id']); // va fi false daca nu e trainer

            $availableRooms = [];
            if($trainerData){
                $availableRooms = $roomService->getAllActiveRoomsByType($trainerData->specialization);
            }

            $data = [
                'requestedDate' => $requestedDate,
                'startOfWeek' => $startOfWeek->format('Y-m-d'),
                'endOfWeek' => $endOfWeek->format('Y-m-d'),
                'prevWeek' => $prevWeek,
                'nextWeek' => $nextWeek,
                'sessionParticipantsMap' => $sessionParticipantsMap,
                'weeklySchedule' => $weeklySchedule,
                'activeSubscriptionData' => $activeSubscriptionData,
                'allTrainers' => $allTrainers,
                'trainerData' => $trainerData,
                'availableRooms' => $availableRooms
            ];

            http_response_code(200);
            echo json_encode($data);
            exit;
        } catch (\Exception $ex) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => $ex->getMessage()]);
            exit;
        }

    }

    public function cancel(){

        $data = json_decode(file_get_contents('php://input'), true);

        $sessionId = isset($data['session_id']) ? $data['session_id'] : null;
        if(!$sessionId){
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Invalid session id']);
            exit;
        }

        $trainerId = isset($_SESSION['trainer_id']) ? $_SESSION['trainer_id'] : null;
        $userRole = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;

        try{
            $sessionService = new SessionService();
            $sessionService->cancelSession($sessionId, $userRole, $trainerId);

            http_response_code(200);
            echo json_encode(['status' => 'success', 'message' => 'Session cancelled successfully']);
            exit;
        } catch (\Exception $ex) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => $ex->getMessage()]);
            exit;
        }
    }

    public function create(){
        $data = json_decode(file_get_contents('php://input'), true);

        $userRole = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;

        $trainerService = new TrainerService();
        $sessionService = new SessionService();
        try{
            $trainerId = null;
            $type = null;

            if($userRole == 'trainer'){
                $trainerData = $trainerService->getTrainerByUserId($_SESSION['user_id']);

                if(!$trainerData){
                    throw new \Exception("Only trainers can create a session");
                }

                $trainerId = $trainerData->id;
                $type = $trainerData->specialization;
            } elseif($userRole == 'admin'){
                $trainerIdForm = isset($data['session_trainer']) ? $data['session_trainer'] : null;
                if($trainerIdForm){
                    throw new \Exception("Please select a trainer");
                }

                $trainerData = $trainerService->getTrainerByUserId($trainerId);
                if(!$trainerData){
                    throw new \Exception("Invalid trainer selected");
                }

                $trainerId = $trainerIdForm;
                $type = $trainerData->specialization;
            }

            $title = isset($data['session_title']) ? $data['session_title'] : '';
            $roomId = isset($data['session_room']) ? $data['session_room'] : '';
            $startTimeRaw = isset($data['start_time']) ? $data['start_time'] : '';
            $endTimeRaw = isset($data['end_time']) ? $data['end_time'] : '';
            $maxCapacity = isset($data['max_capacity']) ? $data['max_capacity'] : '';

            $sessionService->createSession(
                $trainerId,
                $roomId,
                $title,
                $type,
                $startTimeRaw,
                $endTimeRaw,
                $maxCapacity
            );

            http_response_code(200);
            echo json_encode(['status' => 'success', 'message' => 'Session created successfully']);
            exit;
        } catch(\Exception $ex) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => $ex->getMessage()]);
            exit;
        }
    }

    public function edit(){
        $data = json_decode(file_get_contents('php://input'), true);

        $userRole = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;

        $trainerService = new TrainerService();
        $sessionService = new SessionService();
        try{
            $trainerId = null;
            if($userRole == 'trainer'){
                $trainerData = $trainerService->getTrainerByUserId($_SESSION['user_id']);

                if(!$trainerData){
                    throw new \Exception("Only trainers can edit their session");
                }

                $trainerId = $trainerData->id;
            } elseif($userRole == 'admin'){
                $trainerIdForm = isset($data['session_trainer']) ? $data['session_trainer'] : null;
                if($trainerIdForm){
                    throw new \Exception("Please select a trainer");
                }

                $trainerData = $trainerService->getTrainerByUserId($trainerId);
                if(!$trainerData){
                    throw new \Exception("Invalid trainer selected");
                }
                $trainerId = $trainerIdForm;
            }

            $sessionId = isset($data['session_id']) ? $data['session_id'] : '';
            $title = isset($data['session_title']) ? $data['session_title'] : '';
            $roomId = isset($data['session_room']) ? $data['session_room'] : '';
            $startTimeRaw = isset($data['start_time']) ? $data['start_time'] : '';
            $endTimeRaw = isset($data['end_time']) ? $data['end_time'] : '';
            $maxCapacity = isset($data['max_capacity']) ? $data['max_capacity'] : '';

            $sessionService->editSession(
                $sessionId,
                $trainerId,
                $roomId,
                $title,
                $startTimeRaw,
                $endTimeRaw,
                $maxCapacity
            );

            http_response_code(200);
            echo json_encode(['status' => 'success', 'message' => 'Session updated successfully']);
            exit;
        } catch(\Exception $ex) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => $ex->getMessage()]);
            exit;
        }
    }
}