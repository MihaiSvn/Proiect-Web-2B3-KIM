<?php

namespace services;

use models\Session;
use models\Trainer;

class SessionService
{
    public function getAllPlannedAndOngoingBookingsByUserId($userId){
        return Session::findAllPlannedAndOngoingBookingsByUserId($userId);
    }

    public function getBySessionId($sessionId){
        return Session::findById($sessionId);
    }

    public function getAllBookingsByUserId($sessionId, $includeCanceled = false){
        return Session::findAllBookingsByUserId($sessionId, $includeCanceled);
    }

    public function getAllSessionsByTrainerId($trainerId, $includeCanceled = false){
        return Session::findAllSessionsByTrainerId($trainerId, $includeCanceled);
    }

    public function getAllPlannedAndOngoingSessionsByTrainerId($trainerId){
        return Session::findAllPlannedAndOngoingSessionsByTrainerId($trainerId);
    }

    public function cancelSession($sessionId, $trainerId){
        $sessions = Session::findAllSessionsByTrainerId($trainerId);
        $isSessionHeldByTrainer = false;
        foreach($sessions as $session){
            if($session->session_id == $sessionId){
                $isSessionHeldByTrainer = true;
            }
        }

        if(!$isSessionHeldByTrainer){
            throw new \Exception("Session doesn't belong to trainer");
        }

        $success = Session::updateStatusToCanceled($sessionId);

        if(!$success){
            throw new \Exception("Session cannot be canceled");
        }

        return $success;
    }
}