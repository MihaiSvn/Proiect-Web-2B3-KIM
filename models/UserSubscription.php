<?php

namespace models;

use models\Subscription;
use PDO;

class UserSubscription
{
    public static function findActiveSubscriptionsByUserId($userId)
    {
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


    public static function findSubscriptionByIdAndUser($userSubscriptionId, $userId)
    {
        global $pdo;

        $sql = "SELECT * FROM USER_SUBSCRIPTIONS WHERE user_id = :user_id AND id = :userSubscriptionId";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':userSubscriptionId', $userSubscriptionId);
        $stmt->execute();

        return $stmt->fetch();
    }

    public static function applySuspension($userSubscriptionId, $daysToSuspend)
    {
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

    public static function reactivateExpiredSuspensionsByUserId($userId)
    {
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

    public static function reactivateAllExpiredSuspensions()
    {
        global $pdo;

        $sql = "UPDATE USER_SUBSCRIPTIONS 
            SET status = 'active', 
                suspended_until = NULL 
            WHERE status = 'suspended' 
            AND suspended_until <= NOW()";

        $stmt = $pdo->prepare($sql);
        return $stmt->execute();
    }


    public static function checkAndExpireSubscriptionsByUserId($userId)
    {
        global $pdo;
        $sql = "UPDATE USER_SUBSCRIPTIONS 
                SET status = 'expired' 
                WHERE user_id = :user_id 
                  AND status = 'active' 
                  AND (end_date < NOW() OR sessions_left <= 0)";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        return $stmt->execute();
    }

    public static function checkAndExpireAllSubscriptions()
    {
        global $pdo;
        $sql = "UPDATE USER_SUBSCRIPTIONS 
                SET status = 'expired' 
                WHERE status = 'active' 
                  AND (end_date < NOW() OR sessions_left <= 0)";

        $stmt = $pdo->prepare($sql);
        return $stmt->execute();
    }


    public static function getCurrentPeriodRevenue()
    { //din ultimele 30 zile
        global $pdo;
        $sql = "SELECT SUM(s.price) 
            FROM USER_SUBSCRIPTIONS us
            JOIN SUBSCRIPTIONS s ON us.subscription_id = s.id
            WHERE us.start_date >= DATE_SUB(NOW(), INTERVAL 30 DAY)";

        return (float)$pdo->query($sql)->fetchColumn();
    }

    public static function getPreviousPeriodRevenue()
    { //acum intre 60 si 30 zile
        global $pdo;
        $sql = "SELECT SUM(s.price) 
            FROM USER_SUBSCRIPTIONS us
            JOIN SUBSCRIPTIONS s ON us.subscription_id = s.id
            WHERE us.start_date >= DATE_SUB(NOW(), INTERVAL 60 DAY) 
              AND us.start_date < DATE_SUB(NOW(), INTERVAL 30 DAY)";

        return (float)$pdo->query($sql)->fetchColumn();
    }

    public static function getActiveSubscriptionsCountByType()
    {
        global $pdo;

        $sql = "SELECT 
                s.type AS subscription_type, 
                COUNT(us.id) AS total_active
            FROM USER_SUBSCRIPTIONS us
            JOIN SUBSCRIPTIONS s ON us.subscription_id = s.id
            WHERE us.status = 'active'
            GROUP BY s.type
            ORDER BY total_active DESC";

        $stmt = $pdo->query($sql);

        return $stmt->fetchAll();
    }

    public static function create($userId, $subscriptionId)
    {
        global $pdo;

        $subscription =
            Subscription::findById(
                $subscriptionId
            );

        if (!$subscription) {
            return false;
        }

        $sql = "
        INSERT INTO USER_SUBSCRIPTIONS
        (
            user_id,
            subscription_id,
            start_date,
            end_date,
            status,
            suspending_days_left,
            sessions_left
        )
        VALUES
        (
            :user_id,
            :subscription_id,
            NOW(),
            DATE_ADD(
                NOW(),
                INTERVAL :validity_days DAY
            ),
            'active',
            :suspending_days,
            :sessions_left
        )
    ";

        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(
            ':user_id',
            $userId
        );

        $stmt->bindParam(
            ':subscription_id',
            $subscriptionId
        );

        $stmt->bindParam(
            ':validity_days',
            $subscription->validity_days
        );

        $stmt->bindParam(
            ':suspending_days',
            $subscription->max_suspending_days
        );

        $stmt->bindParam(
            ':sessions_left',
            $subscription->sessions
        );

        return $stmt->execute();

    }

    public static function findAllSubscriptionsByUserId($userId)
    {
        global $pdo;
        //le orodnez in functie de stauts (active, apoi suspended, apoi expired)
        // si apoi dupa cate zile maie e valide, de la putin la mai mult,
        // apoi dupa tip fitness strength physio all
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
        ORDER BY 
            FIELD(us.status, 'active', 'suspended', 'expired'),
            us.end_date ASC, 
            FIELD(s.type, 'fitness', 'strength', 'physiotherapy', 'all')
    ";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public static function getSubscriptionTypeStats()
    {
        global $pdo;

        $query = "
        SELECT
            s.type,
            COUNT(*) as total
        FROM USER_SUBSCRIPTIONS us
        JOIN SUBSCRIPTIONS s
            ON us.subscription_id = s.id
        GROUP BY s.type
    ";

        return $pdo->query($query)->fetchAll();
    }

    public static function getSubscriptionsReadyToExpire($userId)
    {
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
          AND (us.end_date < NOW() OR us.sessions_left <= 0)
    ";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}