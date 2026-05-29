<?php

namespace models;
use PDO;
class UserSubscription
{
    public static function findActiveSubscriptionsByUserId($userId){
        global $pdo;
        $sql = "
            SELECT 
                us.*, 
                s.name AS subscription_name, 
                s.type, 
                s.price, 
                s.description,
                s.validity_days
            FROM USER_SUBSCRIPTIONS us
            JOIN SUBSCRIPTIONS s ON us.subscription_id = s.id
            WHERE us.user_id = :user_id 
            AND us.status = 'active'
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public static function findSubscriptionByIdAndUser($userSubscriptionId, $userId){
        global $pdo;

        $sql = "SELECT * FROM USER_SUBSCRIPTIONS WHERE user_id = :user_id AND id = :userSubscriptionId";

        $stmt= $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':userSubscriptionId', $userSubscriptionId);
        $stmt->execute();

        return $stmt->fetch();
    }

    public static function applySuspension($userSubscriptionId, $daysToSuspend){
        global $pdo;

        $sql = "UPDATE user_subscriptions 
            SET end_date = DATE_ADD(end_date, INTERVAL :days DAY),
                suspending_days_left = suspending_days_left - :days,
                status = 'suspended',
                suspended_until = DATE_ADD(NOW(), INTERVAL :days DAY)
            WHERE id = :id";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $userSubscriptionId);
        $stmt->bindParam(':days', $daysToSuspend);
        return $stmt->execute();
    }

    public static function reactivateExpiredSuspensions($userId){
        global $pdo;

        $sql = "UPDATE USER_SUBSCRIPTIONS 
            SET status = 'active', 
                suspended_until = NULL 
            WHERE user_id = :user_id 
            AND status = 'suspended' 
            AND suspended_until <= NOW()";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        return $stmt->execute();
    }
}