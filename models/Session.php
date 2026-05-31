<?php

namespace models;
use PDO;
class Session
{

    public static function findAllPlannedAndOngoingBookingsByUserId($user_id){
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
}