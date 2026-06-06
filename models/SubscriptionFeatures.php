<?php

namespace models;

class SubscriptionFeatures
{
    public static function findBySubscriptionId($subscriptionId)
    {
        global $pdo;

        $sql = "
            SELECT feature_text
            FROM SUBSCRIPTION_FEATURES
            WHERE subscription_id = :subscription_id
            ORDER BY id
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':subscription_id', $subscriptionId);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}