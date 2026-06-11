<?php

namespace models;

use PDO;

class Equipment
{
    public static function create($roomId, $name, $isFunctional)
    {
        global $pdo;

        try {
            $sql = "INSERT INTO `EQUIPMENT` (`room_id`, `name`, `is_functional`)
                    VALUES (:room_id, :name, :is_functional)";

            $stmt = $pdo->prepare($sql);

            return $stmt->execute([
                ':room_id'       => $roomId,
                ':name'          => $name,
                ':is_functional' => $isFunctional
            ]);

        } catch (\PDOException $e) {
            return false;
        }
    }
}
