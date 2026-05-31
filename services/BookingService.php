<?php

namespace services;

use models\Booking;
use models\Session;

class BookingService
{
    public function cancelUserBooking($userId, $sessionId)
    {

        $session = Session::findById($sessionId);

        if (!$session) {
            throw new \Exception("Session not found");
        }

        $startTime = strtotime($session->start_time);

        if ($startTime <= strtotime('+24 hours')) {
            throw new \Exception("You can only cancel a booking with 24 hours in advance");
        }

        $deleted = Booking::delete($userId, $sessionId);
        if (!$deleted) {
            throw new \Exception('An error occurred while trying to cancel the booking. Please try again.');
        }

        return true;
    }
}