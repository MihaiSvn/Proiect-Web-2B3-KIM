<?php

namespace services;

use models\Session;
use models\Trainer;
use models\Room;

require_once __DIR__ . '/MailService.php';
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

    public function getAllSessions($includeCanceled = false){
        return Session::findAllSessions($includeCanceled);
    }

    public function getAllPlannedAndOngoingSessionsByTrainerId($trainerId){
        return Session::findAllPlannedAndOngoingSessionsByTrainerId($trainerId);
    }

    public function cancelSession($sessionId, $userRole, $trainerId = null){
        if (empty($sessionId)) {
            throw new \InvalidArgumentException("Invalid session ID.");
        }

        global $pdo;

        try{
            $pdo->beginTransaction();

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

            Session::refundAllParticipants($sessionId);

            $pdo->commit();

            return true;
        } catch(\Exception $e) {
            if($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            throw $e;
        }



        return true;
    }

    public function getAllPlannedAndOngoingSessions(){
        return Session::findAllPlannedAndOngoingSessions();
    }

    public function getWeeklySessionsGrouped($sessionsFromDb, $startOfWeekObj, $type = null) {
        $groupedSessions = [];
        for ($i = 0; $i < 7; $i++) {
            $currentDay = (clone $startOfWeekObj)->modify("+$i days");
            $dateKey = $currentDay->format('Y-m-d'); // ex: 2026-06-01

            $groupedSessions[$dateKey] = [
                'day_name' => $currentDay->format('l'),     // 'Monday'
                'day_short' => $currentDay->format('M j'),  // 'Jun 1'
                'sessions' => []                            // aici pun sesiunile
            ];
        }

        foreach ($sessionsFromDb as $session) {
            $sessionDate = date('Y-m-d', strtotime($session->start_time));

            if (isset($groupedSessions[$sessionDate])) {
                array_push($groupedSessions[$sessionDate]['sessions'], $session);
            }
        }

        return $groupedSessions;
    }

    public function getSessionsBetweenDates($startDate, $endDate){
        if(empty($startDate) || empty($endDate)){
            throw new \Exception("Invalid start and end date.");
        }
        return Session::findSessionsBetweenDates($startDate, $endDate);
    }

    public function getAllUsersBookedBySessionId($sessionId){
        return Session::findAllUsersBookedBySessionId($sessionId);
    }

    private function validateAndFormatSessionData($trainerId, $roomId, $title, $startTimeRaw, $endTimeRaw, $maxCapacity, $sessionId = null)
    {
        //verific sa nu fie field uri empty
        if (empty(trim($title)) || empty($startTimeRaw) || empty($endTimeRaw) || empty($roomId) || empty($maxCapacity)) {
            throw new \Exception("All fields are required.");
        }

        //capcacitiate sa fie nr
        if (!is_numeric($maxCapacity) || $maxCapacity <= 0) {
            throw new \Exception("Capacity must be a positive number.");
        }

        $startTime = str_replace('T', ' ', $startTimeRaw); //schibmam formatrea de la html pt php
        if (strlen($startTime) === 16) $startTime .= ':00';

        $endTime = str_replace('T', ' ', $endTimeRaw);
        if (strlen($endTime) === 16) $endTime .= ':00';

        $startTimestamp = strtotime($startTime);
        $endTimestamp = strtotime($endTime);
        $currentTimestamp = time();

        // stat time tre sa nu fie in trecut
        if ($startTimestamp < $currentTimestamp) {
            throw new \Exception("You cannot schedule a session in the past.");
        }
        // nut pot ca end sa fie inainte de start
        if ($endTimestamp <= $startTimestamp) {
            throw new \Exception("End time must be later than Start time.");
        }

        //verific daca exista room ul
        $room = Room::findById($roomId);
        if (!$room) {
            throw new \Exception("The selected room does not exist.");
        }
        // verific daca max capacity e bun
        if ($maxCapacity > $room->capacity) {
            throw new \Exception("Requested capacity ({$maxCapacity}) exceeds room limit ({$room->capacity}).");
        }

        //verific daca sala e ocupata in acel interval
        $roomTaken = Session::hasRoomOverlap($roomId, $startTime, $endTime, $sessionId);
        if ($roomTaken) {
            throw new \Exception("The selected room is busy in that interval.");
        }

        //verific ca trainer ul sa nu aiba ceva in intervalul ala
        $trainerBusy = Session::hasTrainerOverlap($trainerId, $startTime, $endTime, $sessionId);
        if ($trainerBusy) {
            throw new \Exception("Trainer already has a class in that interval.");
        }

        return [$startTime, $endTime];
    }
    public function createSession($trainerId, $roomId, $title, $type, $startTimeRaw, $endTimeRaw, $maxCapacity){

        list($startTime, $endTime) = $this->validateAndFormatSessionData(
            $trainerId, $roomId, $title, $startTimeRaw, $endTimeRaw, $maxCapacity
        );

        $success = Session::create(
            $trainerId,
            $roomId,
            $title,
            $type,
            $startTime,
            $endTime,
            $maxCapacity
        );

        if (!$success) {
            throw new \Exception("A database error occurred while creating the session.");
        }

        return $success;

    }

    public function editSession($sessionId, $trainerId, $roomId, $title, $startTimeRaw, $endTimeRaw, $maxCapacity){

        global $pdo;

        $currentSession = Session::findById($sessionId);

        if (!$currentSession) {
            throw new \Exception("The session you are trying to edit does not exist.");
        }

        if ($trainerId !== null && $currentSession->trainer_id != $trainerId) {
            throw new \Exception("Unauthorized action: You can only edit your own classes.");
        }

        $currentBooked = Session::getBookedSpotsCount($sessionId);
        if ($maxCapacity < $currentBooked) {
            throw new \Exception("Cannot reduce the capacity below the current number of booked members ($currentBooked).");
        }

        list($startTime, $endTime) = $this->validateAndFormatSessionData(
            $trainerId, $roomId, $title, $startTimeRaw, $endTimeRaw, $maxCapacity, $sessionId
        );

        $timeChanged = ($currentSession->start_time !== $startTime || $currentSession->end_time !== $endTime);
        try{
            $pdo->beginTransaction();

            $usersWithConflicts = [];
            $allBookedUsers = [];

            if($timeChanged) {
                $usersWithConflicts = Session::findUsersWithConflictsForNewTime($sessionId, $startTime, $endTime);
                $allBookedUsers = Session::getBookedUsersForSession($sessionId);
            }
            $updated = Session::update($sessionId, $roomId, $title, $startTime, $endTime, $maxCapacity);
            if (!$updated) {
                throw new \Exception("A database error occurred while updating the session.");
            }

            if($timeChanged && !empty($allBookedUsers)) {
                //extragem doar id urile utilizatorilor cu conflicte
                $conflictIds = array_map(function($u) { return $u->user_id; }, $usersWithConflicts);
                $sessionTitle = $currentSession->title;
                $notificationService = new NotificationService();

                foreach ($allBookedUsers as $bookedUser) {
                    if (in_array($bookedUser->id, $conflictIds)) {
                        //CONFLICT DE ORAR, AR  TREBUI TRIMIS MAIL AICI
                        $notificationService->createNotification(
                            $bookedUser->id,
                            '🚨 Schedule Conflict!',
                            "The session '{$sessionTitle}' was moved to {$startTime}, creating a conflict with your other bookings."
                        );

                        $subjectConflict = 'Schedule Conflict - KIM Fitness';
                        $bodyConflict = "
                                <h2>Attention: A change in your schedule!</h2>
                                <p>Hi,</p>
                                <p>The <strong>{$sessionTitle}</strong> class has been rescheduled to <b>{$startTime}</b>.</p>
                                <p>This new time overlaps with another booking you already have. Please log into your account to manage your bookings and update your schedule.</p>
                                <br>
                                <p>The KIM Fitness Team</p>
                            ";

                        \services\MailService::sendEmail($bookedUser->email, $subjectConflict, $bodyConflict, true);
                    } else {
                        //INFOMARE A SCHIMBARII OREI, FARA CONFLICT
                        $notificationService->createNotification(
                            $bookedUser->id,
                            '📅 Session Time Changed',
                            "The session '{$sessionTitle}' has been rescheduled to {$startTime}."
                        );

                        $subjectUpdate = 'Class Time Changed - KIM Fitness';
                        $bodyUpdate = "
                                <h2>Update regarding your upcoming class!</h2>
                                <p>Hi,</p>
                                <p>We wanted to let you know that the <strong>{$sessionTitle}</strong> class you are booked for has been rescheduled.</p>
                                <p>The new start time is <b>{$startTime}</b>. Your booking remains active, but if this new time doesn't work for you, please remember to cancel via your account.</p>
                                <br>
                                <p>The KIM Fitness Team</p>
                            ";
                        \services\MailService::sendEmail($bookedUser->email, $subjectUpdate, $bodyUpdate, true);
                    }
                }
            }

            $pdo->commit();
            return $updated;

        } catch(\Throwable $e) {
            if($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            echo "<div style='background: black; color: red; padding: 20px; font-size: 20px; z-index: 9999; position: relative;'>";
            echo "<strong>EROARE FATALĂ INTERCEPTATĂ:</strong><br><br>";
            echo $e->getMessage();
            echo "<br><br><strong>Fișier:</strong> " . $e->getFile() . " (Linia " . $e->getLine() . ")";
            echo "</div>";

            die();
            throw $e;
        }
    }

    public function updateSessionStatuses()
    {
        Session::updateToOngoing();
        Session::updateToCompleted();
        return true;
    }
}