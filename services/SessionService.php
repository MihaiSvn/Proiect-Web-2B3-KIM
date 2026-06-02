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

    public function cancelSession($sessionId, $userRole, $trainerId = null){
        if (empty($sessionId)) {
            throw new \InvalidArgumentException("Invalid session ID.");
        }

        $success = false;

        if ($userRole === 'admin') {
            $success = Session::cancelByAdmin($sessionId);
        }
        else if ($userRole === 'trainer' && $trainerId !== null) {
            $success = Session::cancelByTrainer($sessionId, $trainerId);
        }
        else {
            throw new \Exception("Unauthorized role.");
        }

        if (!$success) {
            throw new \Exception("Could not cancel session. It may not exist, already be canceled, or you lack permissions.");
        }

        return true;
    }

    public function getAllPlannedAndOngoingSessions(){
        return Session::findAllPlannedAndOngoingSessions();
    }
}