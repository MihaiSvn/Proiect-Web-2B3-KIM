<?php

namespace services;

use models\Booking;
use models\Session;
use models\User;

class BookingService
{
    public function cancelUserBooking($userId, $sessionId)
    {

        global $pdo;

        $session = Session::findById($sessionId);

        if (!$session) {
            throw new \Exception("Session not found");
        }

        $user = User::findById($userId);

        if(!$user){
            throw new \Exception("User not found");
        }


        //aven nevoie de booking sa stim ce usersubid avem
        $booking = Booking::find($userId, $sessionId);
        if (!$booking) {
            throw new \Exception("Booking not found or already canceled.");
        }

        $trainerService = new TrainerService();
        $trainer = $trainerService->getTrainerById($session->trainer_id);

        if(!$trainer){
            throw new \Exception("Trainer not found");
        }


        $startTime = strtotime($session->start_time);

        // daca e eligibl pt a primi restituirea (> 24 ore)
        $eligibleForRefund = ($startTime > strtotime('+24 hours'));

        try {
            $pdo->beginTransaction();

            $deleted = Booking::delete($userId, $sessionId);
            if (!$deleted) {
                throw new \Exception('An error occurred while trying to cancel the booking. Please try again.');
            }

            if ($eligibleForRefund) {
                Booking::refundSession($booking->user_subscription_id);
            }

            $notificationService = new NotificationService();
            $notificationService->createNotification(
                $userId,
                '📅 Booking Canceled!',
                "You have successfully canceled your spot for the {$session->title} class. You'll work harder next time."
            );


            $notificationService->createNotification(
                $trainer->user_id,
                'ℹ️ Booking Canceled',
                "Just a heads-up: {$user->first_name} {$user->last_name} has canceled their spot in your {$session->title} class."
            );
            $pdo->commit();

            return true;

        } catch (\Exception $ex) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $ex;
        }
    }

    public function createBooking($userId, $sessionId){
        global $pdo;

        // verific daca exista sesiune
        $session = Session::findById($sessionId);
        error_log("Datele sesiunii: " . print_r($session, true));
        if (!$session || $session->status !== 'planned') {
            throw new \Exception("Can't book this session");
        }

        $user = User::findById($userId);

        if(!$user){
            throw new \Exception("User not found");
        }

        $trainerService = new TrainerService();
        $trainer = $trainerService->getTrainerById($session->trainer_id);

        if(!$trainer){
            throw new \Exception("Trainer not found");
        }

        // verific daca mai sunt locuri
        $currentBookingsCount = Booking::countBookingsForSession($sessionId);
        if($currentBookingsCount >= $session->max_capacity){
            throw new \Exception("We're sorry, there are no more spots for this session");
        }

        // verific daca sunt booked deja
        $alreadyBooked = Booking::find($userId, $sessionId);
        if($alreadyBooked) {
            throw new \Exception("You are already booked to this session");
        }

        // verific daca am abonament elgiibil
        $bestSubId = Booking::findBestSubscriptionForSession($userId, $session->type, $session->start_time);
        if(!$bestSubId) {
            throw new \Exception("You don't have an eligible membership for this session");
        }

        // daca exista vreun booking in aceeasi perioada
        $hasOverlap = Booking::hasOverlappingBookings($userId, $session->start_time, $session->end_time);
        if ($hasOverlap) {
            throw new \Exception("You already have a booking in this period");
        }

        //am trecut de teste
        try{
            $pdo->beginTransaction();

            Booking::createBooking($userId, $sessionId, $bestSubId);

            $notificationService = new NotificationService();
            $notificationService->createNotification(
                $userId,
                '📅 Booking Confirmed!',
                "You have successfully booked your spot for the {$session->title} class. Get ready to crush it!"
            );


            $notificationService->createNotification(
                $trainer->user_id,
                '🏋️‍♂️ New Participant!',
                "Great news! {$user->first_name} {$user->last_name} just booked a spot in your {$session->title} class."
            );

            $pdo->commit();
            return true;
        } catch (\Exception $ex) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $ex;
        }

    }
}