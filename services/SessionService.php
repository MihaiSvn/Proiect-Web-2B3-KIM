<?php

namespace services;

use models\Session;
class SessionService
{
    public function getAllPlannedAndOngoingBookingsByUserId($userId){
        return Session::findAllPlannedAndOngoingBookingsByUserId($userId);
    }

    public function getBySessionId($sessionId){
        return Session::findById($sessionId);
    }

    public function getAllBookingsByUserId($sessionId){
        return Session::findAllBookingsByUserId($sessionId);
    }

    public function getAllSessionsByTrainerId($trainerId){
        return Session::findAllSessionsByTrainerId($trainerId);
    }

    public function getAllPlannedAndOngoingSessionsByTrainerId($trainerId){
        return Session::findAllPlannedAndOngoingSessionsByTrainerId($trainerId);
    }
}