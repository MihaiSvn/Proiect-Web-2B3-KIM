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

        $sql .= " ORDER BY s.start_time ASC";

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

        $sql .= " ORDER BY s.start_time ASC";

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
                s.title,
                s.type AS session_type,
                s.status,
                s.start_time,
                s.end_time,
                s.max_capacity,
                r.name AS room_name,
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

    public static function updateStatusToCanceled($session_id)
    {
        global $pdo;
        $sql = "UPDATE SESSIONS SET status = 'canceled' WHERE id = :session_id AND status!='canceled'";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":session_id", $session_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

}