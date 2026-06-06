<?php

namespace models;

use PDO;

class Session
{

    public static function findAllPlannedAndOngoingBookingsByUserId($user_id)
    {
        global $pdo;

        $sql = "SELECT 
                s.id AS session_id,
                s.title,
                s.type AS session_type,
                s.status,
                s.start_time,
                s.end_time,
                s.max_capacity,
                s.room_id,
                r.name AS room_name,
                u.first_name AS trainer_first_name,
                u.last_name AS trainer_last_name,
                (SELECT COUNT(*) FROM BOOKINGS b WHERE b.session_id = s.id) AS booked_spots
            FROM SESSIONS s
            JOIN BOOKINGS my_booking ON s.id = my_booking.session_id 
            JOIN ROOMS r ON s.room_id = r.id
            JOIN TRAINERS t ON s.trainer_id = t.id
            JOIN USERS u ON t.user_id = u.id
            WHERE my_booking.user_id = :current_user_id 
              AND (s.status = 'planned' OR s.status = 'ongoing') 
              AND s.end_time > NOW()
            ORDER BY s.start_time ASC";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":current_user_id", $user_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public static function findById($session_id)
    {
        global $pdo;
        $sql = "SELECT * FROM SESSIONS WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":id", $session_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public static function findAllBookingsByUserId($user_id, $includeCanceled = false)
    {
        global $pdo;

        $sql = "SELECT 
                s.id AS session_id,
                s.title,
                s.type AS session_type,
                s.status,
                s.start_time,
                s.end_time,
                s.max_capacity,
                s.room_id,
                r.name AS room_name,
                u.first_name AS trainer_first_name,
                u.last_name AS trainer_last_name,
                (SELECT COUNT(*) FROM BOOKINGS b WHERE b.session_id = s.id) AS booked_spots
            FROM SESSIONS s
            JOIN BOOKINGS my_booking ON s.id = my_booking.session_id 
            JOIN ROOMS r ON s.room_id = r.id
            JOIN TRAINERS t ON s.trainer_id = t.id
            JOIN USERS u ON t.user_id = u.id
            WHERE my_booking.user_id = :current_user_id";

        if (!$includeCanceled) {
            $sql .= " AND s.status != 'canceled'";
        }

        $sql .= " ORDER BY s.start_time ASC,
            FIELD(s.status, 'ongoing', 'planned', 'completed'), 
            FIELD(s.type, 'fitness', 'strength', 'physiotherapy', 'all')
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":current_user_id", $user_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public static function findAllSessionsByTrainerId($trainer_id, $includeCanceled = false)
    {
        global $pdo;
        $sql = "SELECT 
                s.id AS session_id,
                s.title,
                s.type AS session_type,
                s.status,
                s.start_time,
                s.end_time,
                s.max_capacity,
                s.room_id,
                s.trainer_id,
                r.name AS room_name,
                u.first_name AS trainer_first_name,
                u.last_name AS trainer_last_name,
                (SELECT COUNT(*) FROM BOOKINGS b WHERE b.session_id = s.id) AS booked_spots
            FROM SESSIONS s
            JOIN ROOMS r ON s.room_id = r.id
            JOIN TRAINERS t ON s.trainer_id = t.id
            JOIN USERS u ON t.user_id = u.id
            WHERE s.trainer_id = :current_trainer_id";


        if (!$includeCanceled) {
            $sql .= " AND s.status != 'canceled'";
        }

        $sql .= " ORDER BY s.start_time ASC, 
            FIELD(s.status, 'ongoing', 'planned', 'completed')
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":current_trainer_id", $trainer_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function findAllPlannedAndOngoingSessionsByTrainerId($trainer_id)
    {
        global $pdo;
        $sql = "SELECT 
                s.id AS session_id,
                s.trainer_id,
                s.title,
                s.type AS session_type,
                s.status,
                s.start_time,
                s.end_time,
                s.max_capacity,
                r.name AS room_name,
                s.room_id,
                u.first_name AS trainer_first_name,
                u.last_name AS trainer_last_name,
                (SELECT COUNT(*) FROM BOOKINGS b WHERE b.session_id = s.id) AS booked_spots
            FROM SESSIONS s
            JOIN ROOMS r ON s.room_id = r.id
            JOIN TRAINERS t ON s.trainer_id = t.id
            JOIN USERS u ON t.user_id = u.id
            WHERE s.trainer_id = :current_trainer_id 
              AND (s.status = 'planned' OR s.status = 'ongoing') 
              AND s.end_time > NOW()
            ORDER BY s.start_time ASC";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":current_trainer_id", $trainer_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function cancelByAdmin($sessionId)
    {
        global $pdo;
        $sql = "UPDATE SESSIONS SET status = 'canceled' WHERE id = :id AND status != 'canceled'";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $sessionId, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    public static function cancelByTrainer($sessionId, $trainerId)
    {
        global $pdo;
        $sql = "UPDATE SESSIONS 
            SET status = 'canceled' 
            WHERE id = :id 
              AND trainer_id = :trainer_id 
              AND status != 'canceled'";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $sessionId, \PDO::PARAM_INT);
        $stmt->bindParam(':trainer_id', $trainerId, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    public static function findAllPlannedAndOngoingSessions()
    {
        global $pdo;
        $sql = "SELECT 
                s.id AS session_id,
                s.title,
                s.type AS session_type,
                s.status,
                s.start_time,
                s.end_time,
                s.max_capacity,
                r.name AS room_name,
                s.trainer_id,
                s.room_id,
                u.first_name AS trainer_first_name,
                u.last_name AS trainer_last_name,
                (SELECT COUNT(*) FROM BOOKINGS b WHERE b.session_id = s.id) AS booked_spots
            FROM SESSIONS s
            JOIN ROOMS r ON s.room_id = r.id
            JOIN TRAINERS t ON s.trainer_id = t.id
            JOIN USERS u ON t.user_id = u.id
            WHERE
            (s.status = 'planned' OR s.status = 'ongoing') 
              AND s.end_time > NOW()
            ORDER BY s.start_time ASC";

        return $pdo->query($sql)->fetchAll();
    }

    public static function findAllSessions($includeCanceled = false)
    {
        global $pdo;
        $sql = "SELECT 
                s.id AS session_id,
                s.title,
                s.type AS session_type,
                s.status,
                s.start_time,
                s.end_time,
                s.max_capacity,
                r.name AS room_name,
                s.trainer_id,
                s.room_id,
                u.first_name AS trainer_first_name,
                u.last_name AS trainer_last_name,
                (SELECT COUNT(*) FROM BOOKINGS b WHERE b.session_id = s.id) AS booked_spots
            FROM SESSIONS s
            JOIN ROOMS r ON s.room_id = r.id
            JOIN TRAINERS t ON s.trainer_id = t.id
            JOIN USERS u ON t.user_id = u.id
            ";

        if (!$includeCanceled) {
            $sql .= " WHERE s.status != 'canceled'";
        }

        $sql .= " ORDER BY s.start_time ASC, 
            FIELD(s.status, 'ongoing', 'planned', 'completed', 'canceled')
        ";

        return $pdo->query($sql)->fetchAll();
    }

    public static function findSessionsBetweenDates($startDate, $endDate, $type = null)
    {
        global $pdo;

        $sql = "SELECT 
                s.id AS session_id,
                s.title,
                s.type AS session_type,
                s.status,
                s.start_time,
                s.end_time,
                s.max_capacity,
                s.trainer_id,
                s.room_id,
                r.name AS room_name,
                u.first_name AS trainer_first_name,
                u.last_name AS trainer_last_name,
                (SELECT COUNT(*) FROM BOOKINGS b WHERE b.session_id = s.id) AS booked_spots
            FROM SESSIONS s
            JOIN ROOMS r ON s.room_id = r.id
            JOIN TRAINERS t ON s.trainer_id = t.id
            JOIN USERS u ON t.user_id = u.id
                WHERE s.start_time >= :start_date AND s.start_time <= :end_date
                AND (s.status = 'planned' OR s.status = 'ongoing' OR s.status = 'completed')";


        if(isset($type)){
            if($type == 'fitness' || $type == 'strength' || $type == 'physiotherapy'){
            $sql .= " AND s.type = :type";
            }
        }

        $sql .= " ORDER BY s.start_time ASC";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':start_date', $startDate, \PDO::PARAM_STR);
        $stmt->bindParam(':end_date', $endDate, \PDO::PARAM_STR);
        if(isset($type)){
            $stmt->bindParam(':type', $type, \PDO::PARAM_STR);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function findAllUsersBookedBySessionId($sessionId)
    {
        global $pdo;
        $sql = "SELECT 
                    u.id, 
                    u.first_name, 
                    u.last_name, 
                    u.email, 
                    u.profile_picture,
                    b.booked_at
                FROM USERS u
                JOIN BOOKINGS b ON u.id = b.user_id
                WHERE b.session_id = :session_id
                ORDER BY b.booked_at ASC;";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':session_id', $sessionId, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();

    }

    public static function refundAllParticipants($sessionId)
    {
        global $pdo;

        //dam o sesiune inapoi celor care au rezervat
        $sqlRefund = "UPDATE USER_SUBSCRIPTIONS us
                  JOIN BOOKINGS b ON us.id = b.user_subscription_id
                  SET us.sessions_left = us.sessions_left + 1
                  WHERE b.session_id = :session_id";

        $stmtRefund = $pdo->prepare($sqlRefund);
        $stmtRefund->bindParam(':session_id', $sessionId, \PDO::PARAM_INT);
        $stmtRefund->execute();

        //reactivam abonamentele doar daca erau expirate si nu din cauza datei
        $sqlReactivate = "UPDATE USER_SUBSCRIPTIONS us
                      JOIN BOOKINGS b ON us.id = b.user_subscription_id
                      SET us.status = 'active'
                      WHERE b.session_id = :session_id 
                        AND us.status = 'expired' 
                        AND us.end_date >= NOW()";

        $stmtReactivate = $pdo->prepare($sqlReactivate);
        $stmtReactivate->bindParam(':session_id', $sessionId, \PDO::PARAM_INT);
        $stmtReactivate->execute();

        //stergem toate bookings
        $sqlDeleteBookings = "DELETE FROM BOOKINGS WHERE session_id = :session_id";
        $stmtDelete = $pdo->prepare($sqlDeleteBookings);
        $stmtDelete->bindParam(':session_id', $sessionId, \PDO::PARAM_INT);
        $stmtDelete->execute();

        return true;
    }

    public static function create($trainerId, $roomId, $title, $type, $startTime, $endTime, $maxCapacity)
    {
        global $pdo;

        $sql = "INSERT INTO SESSIONS 
                (trainer_id, room_id, title, type, start_time, end_time, max_capacity, status) 
                VALUES 
                (:trainer_id, :room_id, :title, :type, :start_time, :end_time, :max_capacity, 'planned')";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':trainer_id', $trainerId, PDO::PARAM_INT);
        $stmt->bindParam(':room_id', $roomId, PDO::PARAM_INT);
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':type', $type, PDO::PARAM_STR);
        $stmt->bindParam(':start_time', $startTime, PDO::PARAM_STR);
        $stmt->bindParam(':end_time', $endTime, PDO::PARAM_STR);
        $stmt->bindParam(':max_capacity', $maxCapacity, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public static function update($sessionId, $roomId, $title, $startTimeRaw, $endTimeRaw, $maxCapacity, $trainerId = null)
    {
        global $pdo;

        $sql = "UPDATE SESSIONS 
                SET room_id = :room_id, 
                    title = :title, 
                    start_time = :start_time, 
                    end_time = :end_time, 
                    max_capacity = :max_capacity
                WHERE id = :id";

        if ($trainerId !== null) {
            $sql .= " AND trainer_id = :trainer_id";
        }

        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':id', $sessionId, \PDO::PARAM_INT);
        $stmt->bindParam(':room_id', $roomId, \PDO::PARAM_INT);
        $stmt->bindParam(':title', $title, \PDO::PARAM_STR);
        $stmt->bindParam(':start_time', $startTimeRaw, \PDO::PARAM_STR);
        $stmt->bindParam(':end_time', $endTimeRaw, \PDO::PARAM_STR);
        $stmt->bindParam(':max_capacity', $maxCapacity, \PDO::PARAM_INT);

        if ($trainerId !== null) {
            $stmt->bindParam(':trainer_id', $trainerId, \PDO::PARAM_INT);
        }

        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

//    ca sa putem verifica daca e valid nr nou de capacitate
    public static function getBookedSpotsCount($sessionId)
    {
        global $pdo;
        $sql = "SELECT COUNT(*) FROM BOOKINGS WHERE session_id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $sessionId, \PDO::PARAM_INT);
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }


//    verific daca o sala e ocupata in acel interval
    public static function hasRoomOverlap($roomId, $startTime, $endTime, $excludeSessionId)
    {
        global $pdo;
        $sql = "SELECT COUNT(*) FROM SESSIONS 
                WHERE room_id = :room_id 
                  AND status != 'canceled' 
                  AND start_time < :end_time 
                  AND end_time > :start_time";

        //ca sa nu verificam cu el insusi in caz de edit
        if ($excludeSessionId) {
            $sql .= " AND id != :exclude_id";
        }

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':room_id', $roomId, \PDO::PARAM_INT);
        $stmt->bindParam(':start_time', $startTime, \PDO::PARAM_STR);
        $stmt->bindParam(':end_time', $endTime, \PDO::PARAM_STR);
        if ($excludeSessionId) {
            $stmt->bindParam(':exclude_id', $excludeSessionId, \PDO::PARAM_INT);
        }
        $stmt->execute();

        return (int) $stmt->fetchColumn() > 0;
    }

//verific daca un trainer are o sesiune in acel interval
    public static function hasTrainerOverlap($trainerId, $startTime, $endTime, $excludeSessionId)
    {
        global $pdo;
        $sql = "SELECT COUNT(*) FROM SESSIONS 
                WHERE trainer_id = :trainer_id 
                  AND status != 'canceled' 
                  AND start_time < :end_time 
                  AND end_time > :start_time";

        //ca sa nu verificam cu el insusi in caz de edit
        if ($excludeSessionId) {
            $sql .= " AND id != :exclude_id";
        }

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':trainer_id', $trainerId, \PDO::PARAM_INT);
        $stmt->bindParam(':start_time', $startTime, \PDO::PARAM_STR);
        $stmt->bindParam(':end_time', $endTime, \PDO::PARAM_STR);
        if ($excludeSessionId) {
            $stmt->bindParam(':exclude_id', $excludeSessionId, \PDO::PARAM_INT);
        }
        $stmt->execute();

        return (int) $stmt->fetchColumn() > 0;
    }


}