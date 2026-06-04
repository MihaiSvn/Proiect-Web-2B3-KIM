<?php

namespace models;
use PDO;
class Booking
{

    public static function find($userId, $sessionId)
    {
        global $pdo;
        $sql = "SELECT user_subscription_id FROM BOOKINGS WHERE user_id = :user_id AND session_id = :session_id";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":user_id", $userId, PDO::PARAM_INT);
        $stmt->bindParam(":session_id", $sessionId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ);
    }
    public static function delete($user_id, $session_id)
    {
        global $pdo;
        $sql = "DELETE FROM BOOKINGS WHERE user_id = :user_id AND session_id = :session_id";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        $stmt->bindParam(":session_id", $session_id, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public static function refundSession($userSubId)
    {
        global $pdo;

        $sqlRefund = "UPDATE USER_SUBSCRIPTIONS SET sessions_left = sessions_left + 1 WHERE id = :id";
        $stmtRefund = $pdo->prepare($sqlRefund);
        $stmtRefund->bindParam(":id", $userSubId, PDO::PARAM_INT);
        $stmtRefund->execute();

        $sqlReactivate = "UPDATE USER_SUBSCRIPTIONS 
                          SET status = 'active' 
                          WHERE id = :id AND status = 'expired' AND end_date >= NOW()";
        $stmtReactivate = $pdo->prepare($sqlReactivate);
        $stmtReactivate->bindParam(":id", $userSubId, PDO::PARAM_INT);
        $stmtReactivate->execute();
    }

    public static function createBooking($userId, $sessionId, $userSubId){
        global $pdo;

        //creem bookingul
        $sql = "INSERT INTO BOOKINGS (user_id, session_id, user_subscription_id) VALUES                                                   
                (:user_id, :session_id, :user_subscription_id)";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":user_id", $userId, PDO::PARAM_INT);
        $stmt->bindParam(":session_id", $sessionId, PDO::PARAM_INT);
        $stmt->bindParam(":user_subscription_id", $userSubId, PDO::PARAM_INT);
        $stmt->execute();


        //scadem sesiunea din acel abonament
        $sqlUpdateSub = "UPDATE USER_SUBSCRIPTIONS 
                     SET sessions_left = sessions_left - 1 
                     WHERE id = :user_sub_id";
        $stmtUpdateSub = $pdo->prepare($sqlUpdateSub);
        $stmtUpdateSub->bindParam(':user_sub_id', $userSubId, \PDO::PARAM_INT);
        $stmtUpdateSub->execute();

        //daca a ajuns la 0, schimbam status in expired
        $sqlExpire = "UPDATE USER_SUBSCRIPTIONS 
                  SET status = 'expired' 
                  WHERE id = :user_sub_id AND sessions_left = 0";
        $stmtExpire = $pdo->prepare($sqlExpire);
        $stmtExpire->bindParam(':user_sub_id', $userSubId, \PDO::PARAM_INT);
        $stmtExpire->execute();

        return true;
    }

    public static function findBestSubscriptionForSession($userId, $sessionType, $sessionStartTime){
        global $pdo;

//        luam abonamentele care sunt de acelasi tip ca sesiunea si cele de tip all, apoi le ordonam mai intai dupa tip
        // adica vom lua prima data cu prioritate cele care sunt de tipul sesiunii
        // apoi ordonam dupa care expira prima
        // apoi ne uitam la alea de all
        // verificam ca abonamentul sa nu expire inainte de inceperea sesiunii
        $sql = "SELECT us.id 
            FROM USER_SUBSCRIPTIONS us
            JOIN SUBSCRIPTIONS s ON us.subscription_id = s.id
            WHERE us.user_id = :user_id 
              AND us.status = 'active' 
              AND us.sessions_left > 0 
              AND us.end_date >= :start_time 
              AND s.type IN (:session_type, 'all')
            ORDER BY 
              CASE WHEN s.type = :session_type THEN 1 ELSE 2 END ASC,
              us.end_date ASC
            LIMIT 1";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":user_id", $userId, PDO::PARAM_INT);
        $stmt->bindParam(":session_type", $sessionType, PDO::PARAM_STR);
        $stmt->bindParam(":start_time", $sessionStartTime, PDO::PARAM_STR);

        $stmt->execute();

        $result = $stmt->fetch();

        // returnam id ul daca l am gasit sau null daca nu exista abonamente eligibile
        return $result ? $result->id : null;

    }

    public static function countBookingsForSession($sessionId){
        global $pdo;
        $sql = "SELECT COUNT(*) AS count FROM BOOKINGS WHERE session_id = :session_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":session_id", $sessionId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result->count;
    }

    public static function hasOverlappingBookings($userId, $newStartTime, $newEndTime)
    {
        global $pdo;


        $sql = "SELECT COUNT(*)
                FROM BOOKINGS b
                JOIN SESSIONS s ON b.session_id = s.id
                WHERE b.user_id = :user_id
                  AND s.status != 'canceled' 
                  AND s.start_time < :new_end_time 
                  AND s.end_time > :new_start_time";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $userId, \PDO::PARAM_INT);
        $stmt->bindParam(':new_start_time', $newStartTime, \PDO::PARAM_STR);
        $stmt->bindParam(':new_end_time', $newEndTime, \PDO::PARAM_STR);
        $stmt->execute();

        return (int) $stmt->fetchColumn() > 0;
    }
}