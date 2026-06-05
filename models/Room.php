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
}