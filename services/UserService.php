<?php

namespace services;

use models\User;
class UserService
{
    public function getUserByEmail($email){
        return User::findByEmail($email);
    }

    public function getUserById($id){
        return User::findById($id);
    }

    public function getAllMembers(){
        return User::findAllMembers();
    }

    public function createUser($first_name, $last_name, $email, $password, $confirm_password, $role){

        if (empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($confirm_password)) {
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

        if($password != $confirm_password){
            throw new \Exception("Passwords do not match");
        }

        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $success = User::create($first_name, $last_name, $email, $password_hash, $role);

        if(!$success){
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


    public function getActiveMembersTrend(){
        $currentPeriodCount = User::getNewMembersCountCurrentPeriod();
        $previousPeriodCount = User::getNewMembersCountPreviousPeriod();


        if ($previousPeriodCount === 0) {
            return $currentPeriodCount > 0 ? 100 : 0;
        }

        return round((($currentPeriodCount - $previousPeriodCount) / $previousPeriodCount) * 100);
    }

    public function getAllUsersCount(){
        return User::getAllUsersCount();
    }

    public function getActiveMembersStats(){
        $trend = $this->getActiveMembersTrend();
        $displayValue = $this->getAllUsersCount();

        return [
            'displayValue' => $displayValue,
            'trend' => $trend
        ];
    }

    public function getActiveSubscriptionsByUserId($user_id){

        $activeSubscriptions = User::findActiveSubscriptionsByUserId($user_id);

        $result = [
            'can_book_anything' => false, //devine true daca are o sesiune la all
            'sessions_by_type' => [
                'fitness' => 0,
                'physiotherapy' => 0,
                'strength' => 0,
                'all' => 0
            ]
        ];

        if (empty($activeSubscriptions)) {
            return $result;
        }

        foreach ($activeSubscriptions as $sub) {
            $result['sessions_by_type'][$sub->type] += $sub->sessions_left;

            if($sub->sessions_left > 0 && $sub->type == 'all'){
                $result['can_book_anything'] = true;
            }
        }

        return $result;
    }

    public function updateUserProfile($userId, $first_name, $last_name, $email, $newAvatarName = null){
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            throw new \Exception("Email address is not valid");
        }

        //trb sa verificam daca exista deja utilizator cu acest mail
        $existingUser = $this->getUserByEmail($email);

        if($existingUser && $existingUser->id != $userId){
            throw new \Exception("User with email $email already exists");
        }

        $success = User::updateProfile($userId, $first_name, $last_name, $email, $newAvatarName);

        if(!$success){
            throw new \Exception("Unable to update user");
        }
        return true;
    }

    public function updateUserPassword($userId, $oldPassword, $newPassword){
        $user = $this->getUserById($userId);
        if(!$user || !password_verify($oldPassword, $user->password_hash)){
            throw new \Exception("Old password is incorrect");
        }

        if(strlen($newPassword) < 6){
            throw new \Exception("Password must be at least 6 characters");
        }

        $new_password_hash = password_hash($newPassword, PASSWORD_DEFAULT);
        if(password_verify($newPassword, $user->password_hash)){
            throw new \Exception("New password is the same as the old password");
        }
        $success = User::updatePassword($userId, $new_password_hash);
        if(!$success){
            throw new \Exception("Unable to update user");
        }
        return true;
    }

    public function deleteUser($userId){
        $user = $this->getUserById($userId);
        if(!$user){
            throw new \Exception("User not found");
        }

        $success = User::deleteUser($userId);
        if(!$success){
            throw new \Exception("Unable to delete user");
        }
        return true;
    }
}