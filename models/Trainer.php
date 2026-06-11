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

    public static function getAllTrainersCount(){
        global $pdo;
        $query = "SELECT COUNT(*) FROM TRAINERS";
        return (int)$pdo->query($query)->fetchColumn();
    }

    public static function findAllTrainers(){
        global $pdo;
        $query = "SELECT
            t.id AS trainer_id,
            t.specialization,
            u.id AS user_id,
            u.first_name,
            u.last_name,
            u.email,
            u.profile_picture,
            u.created_at
        FROM TRAINERS t
        JOIN USERS u ON t.user_id = u.id
        ORDER BY u.first_name ASC, u.last_name ASC;";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function findTrainerById($trainerId){
        global $pdo;
        $query = "SELECT * FROM `trainers` WHERE `id` = :trainer_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':trainer_id', $trainerId);
        $stmt->execute();
        return $stmt->fetch();
    }

    public static function create($userId, $specialization)
    {
        global $pdo;

        $sql = "INSERT INTO TRAINERS (user_id, specialization) VALUES (:user_id, :specialization)";
        $stmt = $pdo->prepare($sql);

        return $stmt->execute([
            ':user_id' => $userId,
            ':specialization' => $specialization
        ]);
    }

    public static function updateByUserId($userId, $specialization) {
        global $pdo;
        $sql = "UPDATE TRAINERS SET specialization = :spec WHERE user_id = :uid";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([':spec' => $specialization, ':uid' => $userId]);
    }

    public static function deleteByUserId($userId) {
        global $pdo;
        $sql = "DELETE FROM TRAINERS WHERE user_id = :uid";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([':uid' => $userId]);
    }
}
