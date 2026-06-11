<?php

namespace models;
use PDO;

class Room
{
    public static function getAllActiveRoomsByType($type){
        global $pdo;

        //group concat ne va da toate echipamentele separate prin virgula
        $sql = "SELECT r.*, GROUP_CONCAT(e.name SEPARATOR ', ') AS equipment_list
                FROM ROOMS r
                LEFT JOIN EQUIPMENT e ON r.id = e.room_id AND e.is_functional = TRUE
                WHERE r.type = :type AND r.is_active = TRUE
                GROUP BY r.id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':type', $type);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function getAllActiveRooms(){
        global $pdo;

        //group concat ne va da toate echipamentele separate prin virgula
        $sql = "SELECT r.*, GROUP_CONCAT(e.name SEPARATOR ', ') AS equipment_list
                FROM ROOMS r
                LEFT JOIN EQUIPMENT e ON r.id = e.room_id AND e.is_functional = TRUE
                WHERE r.is_active = TRUE
                GROUP BY r.id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function findById($id){
        global $pdo;
        $sql = 'SELECT * FROM rooms WHERE id = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    public static function getAllRooms()
    {
        global $pdo;

        $query = "
        SELECT
            r.*,

            GROUP_CONCAT(
                DISTINCT e.name
                SEPARATOR ', '
            ) AS equipment_list,

            COUNT(
                DISTINCT e.id
            ) AS equipment_count,

            COUNT(
    DISTINCT CASE
        WHEN DATE(s.start_time) = CURDATE()
        THEN s.id
    END
) AS sessions_count,

            (
    SELECT COUNT(*)

    FROM BOOKINGS b

    JOIN SESSIONS s
        ON b.session_id = s.id

    WHERE s.room_id = r.id

    AND s.status != 'canceled'

    AND NOW() BETWEEN
        s.start_time
        AND
        s.end_time

) AS current_bookings

        FROM ROOMS r

        LEFT JOIN EQUIPMENT e
            ON r.id = e.room_id

        LEFT JOIN SESSIONS s
            ON r.id = s.room_id
            AND s.status != 'canceled'

        LEFT JOIN BOOKINGS b
            ON s.id = b.session_id

        GROUP BY r.id

        ORDER BY r.name
    ";

        return $pdo
            ->query($query)
            ->fetchAll();
    }

    public static function updateRoom(
        $data
    )
    {
        global $pdo;

        $query = "
        UPDATE ROOMS
        SET
            name = :name,
            capacity = :capacity,
            type = :type,
            is_active = :is_active
        WHERE id = :id
    ";

        $stmt =
            $pdo->prepare(
                $query
            );

        return $stmt->execute([

            ':name' =>
                $data['name'],

            ':capacity' =>
                $data['capacity'],

            ':type' =>
                $data['type'],

            ':is_active' =>
                $data['is_active'],

            ':id' =>
                $data['room_id']
        ]);
    }
    public static function createRoom(
        $data
    )
    {
        global $pdo;

        $query = "
        INSERT INTO ROOMS
        (
            name,
            capacity,
            type,
            is_active
        )
        VALUES
        (
            :name,
            :capacity,
            :type,
            :is_active
        )
    ";

        $stmt =
            $pdo->prepare(
                $query
            );

        return $stmt->execute([

            ':name' =>
                $data['name'],

            ':capacity' =>
                $data['capacity'],

            ':type' =>
                $data['type'],

            ':is_active' =>
                $data['is_active']
        ]);
    }

}