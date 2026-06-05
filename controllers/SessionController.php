<?php

namespace controllers;

use mysql_xdevapi\Exception;
use services\SessionService;
use services\TrainerService;
class SessionController
{
    private $sessionService;

    public function __construct(SessionService $sessionService, TrainerService $trainerService)
    {
        $this->sessionService = $sessionService;
        $this->trainerService = $trainerService;
    }

    public function cancel()
    {

        if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
            header('Location: /kim/login?error=Unauthorized');
            exit;
        }

        $userRole = $_SESSION['user_role'];

        if ($userRole !== 'trainer' && $userRole !== 'admin') {
            header('Location: /kim/dashboard?error=You do not have permission.');
            exit;
        }

        if (!isset($_SESSION['trainer_id'])) {
            header('Location: /kim/login?error=You must be a trainer to access this');
            exit;
        }

        $statusType = '';
        $message = '';

        if (isset($_POST['session_id'])) {
            $trainerId = isset($_SESSION['trainer_id']) ? $_SESSION['trainer_id'] : null;

            $sessionId = $_POST['session_id'];
            try {
                $this->sessionService->cancelSession($sessionId, $userRole, $trainerId);
                $statusType = 'success';
                $message = 'Session cancelled';

            } catch (\Exception $ex) {
                $statusType = 'error';
                $message = $ex->getMessage();
            }
        }

        $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/kim/dashboard';

        //daca in link avem deja un parametru sau nu
        $separator = (strpos($referer, '?') !== false) ? '&' : '?';

        // ?success=Mesaj+aici)
        $queryString = ($statusType !== '') ? $separator . $statusType . '=' . urlencode($message) : '';

        header('Location: ' . $referer . $queryString);
        exit;

    }

    public function create(){
        if(!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
            header('Location: /kim/login?error=Unauthorized');
            exit;
        }
        $userRole = $_SESSION['user_role'];
        if ($userRole !== 'trainer' && $userRole !== 'admin') {
            header('Location: /kim/dashboard?error=You do not have permission.');
            exit;
        }

        $statusType = '';
        $message = '';

        try{

            $trainerId = null;
            $type = null;

            if($userRole === 'trainer') {
                $trainerData = $this->trainerService->getTrainerByUserId($_SESSION['user_id']);

                if(!$trainerData){
                    throw new Exception("Only trainers can create a session");
                }

                $trainerId = $trainerData->id;
                $type = $trainerData->specialization;
            } elseif($userRole === 'admin') {
                $trainerIdForm = isset($_POST['session_trainer']) ? $_POST['session_trainer'] : null;
                if(!$trainerIdForm){
                    throw new \Exception("Please select a trainer");
                }

                $trainerData = $this->trainerService->getTrainerById($trainerIdForm);
                if(!$trainerData){
                    throw new \Exception("Invalid trainer selected");
                }

                $trainerId = $trainerIdForm;
                $type = $trainerData->specialization;
            }


            $title = isset($_POST['session_title']) ? $_POST['session_title'] : '';
            $roomId = isset($_POST['session_room']) ? $_POST['session_room'] : '';
            $startTimeRaw = isset($_POST['start_time']) ? $_POST['start_time'] : '';
            $endTimeRaw = isset($_POST['end_time']) ? $_POST['end_time'] : '';
            $maxCapacity = isset($_POST['max_capacity']) ? $_POST['max_capacity'] : '';

            $this->sessionService->createSession(
                $trainerId,
                $roomId,
                $title,
                $type,
                $startTimeRaw,
                $endTimeRaw,
                $maxCapacity
            );

            $statusType = 'success';
            $message = 'Session successfully created!';

        } catch (\Exception $ex) {
            $statusType = 'error';
            $message = $ex->getMessage();
        }

        $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/kim/dashboard';

        //daca in link avem deja un parametru sau nu
        $separator = (strpos($referer, '?') !== false) ? '&' : '?';

        // ?success=Mesaj+aici)
        $queryString = ($statusType !== '') ? $separator . $statusType . '=' . urlencode($message) : '';

        header('Location: ' . $referer . $queryString);
        exit;
    }

    public function edit()
    {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
            header('Location: /kim/login?error=Unauthorized');
            exit;
        }

        $userRole = $_SESSION['user_role'];

        if ($userRole !== 'trainer' && $userRole !== 'admin') {
            header('Location: /kim/dashboard?error=You do not have permission.');
            exit;
        }

        $statusType = '';
        $message = '';

        try {
            $trainerId = null;
            if ($userRole === 'trainer') {
                $trainerData = $this->trainerService->getTrainerByUserId($_SESSION['user_id']);

                if (!$trainerData) {
                    throw new \Exception("Only trainers can edit their sessions.");
                }

                $trainerId = $trainerData->id;

            } elseif($userRole === 'admin') {
                $trainerIdForm = isset($_POST['session_trainer']) ? $_POST['session_trainer'] : null;
                if(!$trainerIdForm){
                    throw new \Exception("Please select a trainer");
                }

                $trainerData = $this->trainerService->getTrainerById($trainerIdForm);
                if(!$trainerData){
                    throw new \Exception("Invalid trainer selected");
                }

                $trainerId = $trainerIdForm;
            }

            $sessionId = isset($_POST['session_id']) ? $_POST['session_id'] : '';
            $title = isset($_POST['session_title']) ? $_POST['session_title'] : '';
            $roomId = isset($_POST['session_room']) ? $_POST['session_room'] : '';
            $startTimeRaw = isset($_POST['start_time']) ? $_POST['start_time'] : '';
            $endTimeRaw = isset($_POST['end_time']) ? $_POST['end_time'] : '';
            $maxCapacity = isset($_POST['max_capacity']) ? $_POST['max_capacity'] : '';

            $this->sessionService->editSession(
                $sessionId,
                $trainerId,
                $roomId,
                $title,
                $startTimeRaw,
                $endTimeRaw,
                $maxCapacity
            );

            $statusType = 'success';
            $message = 'Session successfully updated!';

        } catch (\Exception $ex) {
            $statusType = 'error';
            $message = $ex->getMessage();
        }

        $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/kim/dashboard';

        //daca in link avem deja un parametru sau nu
        $separator = (strpos($referer, '?') !== false) ? '&' : '?';

        // ?success=Mesaj+aici)
        $queryString = ($statusType !== '') ? $separator . $statusType . '=' . urlencode($message) : '';

        header('Location: ' . $referer . $queryString);
        exit;
    }
}