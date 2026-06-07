<?php
namespace models;
use PDO;

class User
{

    public static function create($first_name, $last_name, $email, $password_hash, $role){ //role poate fi null daca dau register, sau poate sa existe daca e facut de admin
        global $pdo;


        $sql = "INSERT INTO USERS (first_name, last_name, email, password_hash, role) VALUES
        (:first_name, :last_name, :email, :password_hash, :role)";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':first_name', $first_name, PDO::PARAM_STR);
        $stmt->bindParam(':last_name', $last_name, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password_hash', $password_hash, PDO::PARAM_STR);

        $final_role = is_null($role) ? "member" : $role;

        $stmt->bindParam(':role', $final_role, PDO::PARAM_STR);

        return $stmt->execute(); //true sau false
    }
    public static function findByEmail($email){
        global $pdo;
        $sql = "SELECT id, first_name, last_name, email, role, profile_picture, created_at  FROM USERS WHERE email = :email";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch(); //obiect sau false
    }

    public static function findByEmailWithPassword($email){
        global $pdo;
        $sql = "SELECT * FROM USERS WHERE email = :email";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch(); //obiect sau false
    }

    public static function findById($id){
        global $pdo;
        $sql = "SELECT id, first_name, last_name, email, role, profile_picture, created_at 
                                FROM users WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    public static function findWithPasswordById($id){
        global $pdo;
        $sql = "SELECT *  FROM users WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    public static function findAllMembers(){
        global $pdo;
        $sql = "SELECT * FROM USERS WHERE role = 'member' ORDER BY created_at DESC";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll();
    }

    public static function getNewMembersCountCurrentPeriod() { //cati s-au inscris in ultimele 30 de zile
        global $pdo;
        $sql = "SELECT COUNT(*) FROM USERS WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
        return (int) $pdo->query($sql)->fetchColumn();
    }

    public static function getNewMembersCountPreviousPeriod() { //cati s-au inscris intre acum 60 si 30 de zile
        global $pdo;
        $sql = "SELECT COUNT(*) FROM USERS WHERE created_at >= DATE_SUB(NOW(), INTERVAL 60 DAY) AND created_at < DATE_SUB(NOW(), INTERVAL 30 DAY)";
        return (int) $pdo->query($sql)->fetchColumn();
    }

    public static function getAllUsersCount(){
        global $pdo;

        $sql = "SELECT COUNT(*) FROM USERS";
        return (int) $pdo->query($sql)->fetchColumn();
    }

    public static function findActiveSubscriptionsByUserId($user_id){
        global $pdo;

        $sql = "SELECT 
                s.id as subscription_id,
                s.name as subscription_name,
                s.type,
                us.id as user_subscription_id,
                us.sessions_left,
                us.start_date,
                us.end_date
            FROM USER_SUBSCRIPTIONS us
            JOIN SUBSCRIPTIONS s ON us.subscription_id = s.id
            WHERE us.user_id = :user_id
              AND us.status = 'active'
              AND us.sessions_left > 0
              AND us.end_date >= NOW()
            ORDER BY us.end_date ASC";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();

    }

    public static function updateProfile($userId, $first_name, $last_name, $email, $profilePicture = null){
        global $pdo;
        $sql = "UPDATE USERS SET
        first_name = :first_name,
        last_name = :last_name,
        email = :email";

        if($profilePicture !== null){
            $sql .= ", profile_picture = :profile_picture";
        }

        $sql .= " WHERE id = :id";


        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':first_name', $first_name, PDO::PARAM_STR);
        $stmt->bindParam(':last_name', $last_name, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);

        if($profilePicture !== null){
            $stmt->bindParam(':profile_picture', $profilePicture, PDO::PARAM_STR);
        }
        return $stmt->execute();
    }

    public static function updatePassword($userId, $newPasswordHash){
        global $pdo;

        $sql = "UPDATE USERS SET
        password_hash = :password_hash
        WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':password_hash', $newPasswordHash, PDO::PARAM_STR);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        return $stmt->execute();

    }

    public static function deleteUser($userId){
        global $pdo;
        $sql = "DELETE FROM USERS WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        return $stmt->execute();
    }


}