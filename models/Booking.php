<?php

namespace models;
use PDO;
class Booking
{
    public static function delete($user_id, $session_id){
        global $pdo;
        $sql = "DELETE FROM BOOKINGS WHERE user_id = :user_id AND session_id = :session_id";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        $stmt->bindParam(":session_id", $session_id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}