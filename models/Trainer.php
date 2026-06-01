<?php

namespace models;
use PDO;

class Trainer
{
    public static function findTrainerByUserId($userId){
        global $pdo;
        $query = "SELECT * FROM `trainers` WHERE `user_id` = :user_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        return $stmt->fetch();
    }
}