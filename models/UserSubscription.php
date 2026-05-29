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
}