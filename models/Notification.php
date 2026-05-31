<?php

namespace models;

use PDO;

class Notification
{
    public static function findByUserId($user_id)
    {
        global $pdo;

        $sql = "SELECT id, user_id, title, message, is_read, created_at 
                FROM NOTIFICATIONS 
                WHERE user_id = :user_id 
                ORDER BY created_at DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public static function findUnreadByUserId($user_id)
    {
        global $pdo;

        $sql = "SELECT id, user_id, title, message, is_read, created_at 
                FROM NOTIFICATIONS 
                WHERE user_id = :user_id
                AND is_read = FALSE
                ORDER BY created_at DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }


    public static function create($user_id, $title, $message)
    {
        global $pdo;

        $sql = "INSERT INTO NOTIFICATIONS (user_id, title, message) 
                VALUES (:user_id, :title, :message)";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        $stmt->bindParam(":type", $type, PDO::PARAM_STR);
        $stmt->bindParam(":title", $title, PDO::PARAM_STR);
        $stmt->bindParam(":message", $message, PDO::PARAM_STR);

        return $stmt->execute();
    }


    public static function markAllAsRead($user_id)
    {
        global $pdo;

        $sql = "UPDATE NOTIFICATIONS SET is_read = TRUE WHERE user_id = :user_id";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public static function markAsReadById($notification_id){
        global $pdo;
        $sql = "UPDATE NOTIFICATIONS SET is_read = TRUE WHERE id = :notification_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":notification_id", $notification_id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}