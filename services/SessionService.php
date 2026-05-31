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
}