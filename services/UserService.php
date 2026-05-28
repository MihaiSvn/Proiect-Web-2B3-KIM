<?php

namespace services;

use models\User;
class UserService
{
    public function getUserByEmail($email){
        return User::findByEmail($email);
    }

    public function createUser($first_name, $last_name, $email, $password, $role){

        if (empty($first_name) || empty($last_name) || empty($email) || empty($password)) {
            throw new \Exception("All fields are required");
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception("Email address is not valid");
        }

        if($this->getUserByEmail($email)){
            throw new \Exception("User with email $email already exists");
        }

        if(strlen($password) < 6){
            throw new \Exception("Password must be at least 6 characters");
        }

        $succes = User::create($first_name, $last_name, $email, $password, $role);

        if(!$succes){
            throw new \Exception("Unable to create user");
        }

        return true;
    }

    public function authenticate($email, $password){
        if (empty($email) || empty($password)) {
            throw new \Exception("Please enter all fields");
        }

        $user = User::findByEmail($email);

        if (!$user || !password_verify($password, $user->password_hash)) {
            throw new \Exception("Invalid email or password");
        }

        return $user;
    }
}