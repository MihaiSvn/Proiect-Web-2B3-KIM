<?php

namespace api\profile;

use services\SessionService;
use services\TrainerService;
use services\UserService;


class ProfileActivityApiController
{

    public function getActivities()
    {
        header('Content-type: application/json');

        try {
            $userId = isset($_GET['user_id']) ? intval($_GET['user_id']) : null;
            $userRole = isset($_GET['user_role']) ? $_GET['user_role'] : null;

            if (!$userId || !$userRole) {
                http_response_code(400);
                echo json_encode(array("error" => "User ID and Role are required."));
                exit;
            }

            $sessionsService = new SessionService();
            $userService = new UserService();
            $trainerService = new TrainerService();

            $user = $userService->getUserById($userId);
            if (!$user) {
                http_response_code(400);
                echo json_encode(array("error" => "User not found."));
                exit;
            }

            $allSessions = [];
            if ($userRole === 'member') {
                $allSessions = $sessionsService->getAllBookingsByUserId($userId);
            } else if ($userRole === 'trainer') {
                $trainer = $trainerService->getTrainerByUserId($userId);
                if (!$trainer) {
                    http_response_code(404);
                    echo json_encode(['error' => 'Trainer profile not found']);
                    exit;
                }
                $allSessions = $sessionsService->getAllSessionsByTrainerId($trainer->id, true);
            } else if ($userRole === 'admin') {
                $allSessions = $sessionsService->getAllSessions(true);
            }

            $groupedSessions = [
                'ongoing' => [],
                'planned' => [],
                'completed' => [],
                'canceled' => []
            ];

            foreach ($allSessions as $session) {
                if (array_key_exists($session->status, $groupedSessions)) {
                    array_push($groupedSessions[$session->status], $session);
                }
            }

            $sessionParticipantsMap = [];

            // map care are id sesiune ca key si lista useri ca value
            foreach ($allSessions as $session) {
                $usersForThisSession = $sessionsService->getAllUsersBookedBySessionId($session->session_id);

                $sessionParticipantsMap[$session->session_id] = $usersForThisSession;
            }

            http_response_code(200);
            echo json_encode([
                'user' => $user,
                'allSessions' => $allSessions,
                'groupedSessions' => $groupedSessions,
                'sessionParticipantsMap' => $sessionParticipantsMap
            ]);


        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }


}
