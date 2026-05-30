<?php
namespace models;
use PDO;

class User
{

    public static function create($first_name, $last_name, $email, $password, $role){ //role poate fi null daca dau register, sau poate sa existe daca e facut de admin
        global $pdo;

        $password_hash = password_hash($password, PASSWORD_DEFAULT);

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
        $sql = "SELECT * FROM USERS WHERE email = :email";
        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch(); //obiect sau false
    }

    public static function findById($id){
        global $pdo;
        $sql = "SELECT * FROM USERS WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }


}