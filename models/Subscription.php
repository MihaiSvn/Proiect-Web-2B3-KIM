<?php

namespace models;

use models\SubscriptionFeatures;

class Subscription
{
    public static function findAll()
    {
        global $pdo;

        $sql = "
            SELECT *
            FROM SUBSCRIPTIONS
            ORDER BY id
        ";

        $stmt = $pdo->query($sql);

        return $stmt->fetchAll();
    }

    public static function findByType($type)
    {
        global $pdo;

        $sql = "
            SELECT *
            FROM SUBSCRIPTIONS
            WHERE type = :type
            ORDER BY
                CASE
                    WHEN sessions = 4 THEN 1
                    WHEN sessions = 12 THEN 2
                    WHEN sessions = 8 THEN 3
                END
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':type', $type);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public static function findById($id)
    {
        global $pdo;

        $sql = "
            SELECT *
            FROM SUBSCRIPTIONS
            WHERE id = :id
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch();
    }

    public static function findByIdWithFeatures($id)
    {
        $subscription = self::findById($id);

        if (!$subscription) {
            return null;
        }

        $subscription->features =
            SubscriptionFeatures::findBySubscriptionId($id);

        return $subscription;
    }

    public static function findWithFeaturesByType($type)
    {
        $subscriptions = self::findByType($type);

        foreach ($subscriptions as $subscription) {

            $features =
                SubscriptionFeatures::findBySubscriptionId(
                    $subscription->id
                );

            $subscription->features = [];

            foreach ($features as $feature) {

                $subscription->features[] =
                    $feature->feature_text;

            }
        }

        return $subscriptions;
    }
}